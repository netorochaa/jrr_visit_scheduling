<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CollectCreateRequest;
use App\Http\Requests\CollectUpdateRequest;
use App\Repositories\CollectRepository;
use App\Repositories\NeighborhoodRepository;
use App\Repositories\CancellationTypeRepository;
use App\Repositories\PatientTypeRepository;
use App\Repositories\PersonRepository;
use App\Validators\CollectValidator;
use App\Entities\Util;
use DateTime;
use Exception;

date_default_timezone_set('America/Recife');

class PublicCollectController extends Controller
{

    protected $repository;
    protected $validator;

    public function __construct(CollectRepository $repository, CollectValidator $validator, NeighborhoodRepository $neighborhoodRepository, 
                                CancellationTypeRepository $cancellationTypeRepository ,PersonRepository $peopleRepository, PatientTypeRepository $patientTypeRepository)
    {
        $this->repository                 = $repository;
        $this->validator                  = $validator;
        $this->neighborhoodRepository     = $neighborhoodRepository;   
        $this->cancellationTypeRepository = $cancellationTypeRepository;
        $this->peopleRepository           = $peopleRepository;
        $this->patientTypeRepository      = $patientTypeRepository;
    }

    // COLLECT PUBLIC
    public function index(Request $request)
    {
        $sessionActive = null;
        if($request->session()->has('collect'))
            $sessionActive = $request->session()->get('collect');

        // dd($request->session()->all());

        if(!$request->has('neighborhood'))
        {
            $neighborhood_list  = $this->neighborhoodRepository->where('active', 'on')->get();
        }
        else
        {
            $neighborhood_model = $this->neighborhoodRepository->find($request->get('neighborhood'));
        }
        return view('collect.public.index', [
            'titlespage' => ['Coleta Domiciliar'],
            'titlecard'  => 'Agendar coleta',
            'sessionActive'         => $sessionActive,
            'neighborhood_list'     => $neighborhood_list ?? null,
            'neighborhood_model'    => $neighborhood_model ?? null,
        ]);
    }

    public function schedule($id)
    {
        try
        {
            $collect = $this->repository->find($id);

            $cancellationType_list  = $this->cancellationTypeRepository->where('active', 'on')->pluck('name', 'id');
            $patientType_list       = $this->patientTypeRepository->patientTypeWithResponsible_list();
            $collectType_list       = $this->repository->collectType_list();
            // $statusCollects_list    = $this->repository->statusCollects_list();
            $payment_list           = $this->repository->payment_list(true);
            $typeResponsible_list   = $this->peopleRepository->typeResponsible_list();
            $covenant_list          = $this->peopleRepository->covenant_list();
            $quant                  = count($collect->people);
            $price                  = count($collect->people) > 2 ? (count($collect->people)-1) * $collect->neighborhood->displacementRate : count($collect->people) == 0 ? "0" : $collect->neighborhood->displacementRate;
            $priceString            = "R$ " . (string) $price;

            return view('collect.public.edit', [
                'titlespage'    => ['Coleta Domiciliar'],
                'titlecard'     => 'Dados de coleta',
                'titlemodal'    => 'Cadastrar paciente',
                //Lists for select
                'cancellationType_list' => $cancellationType_list,
                'patientType_list'      => $patientType_list,
                'collectType_list'      => $collectType_list,
                // 'statusCollects_list'   => $statusCollects_list,
                'payment_list'          => $payment_list,
                'typeResponsible_list'  => $typeResponsible_list,
                'covenant_list'         => $covenant_list,
                'quant'                 => $quant,
                'price'                 => $priceString,
                //Info of entitie
                'table' => $this->repository->getTable(),
                'collect' => $collect
            ]);
        }
        catch(Exception $e)
        {
            $response = [
                'message' =>  $e->getMessage(),
                'type'    => 'error'
            ];
            session()->flash('return', $response);
            return redirect()->route('collect.public');
        }
    }

    public function reserve(Request $request)
    {
        try 
        {
            $id_collect      = $request->get('infoCollect');
            $id_neighborhood = (int)$request->get('neighborhood_id');
            $id_origin       = 2;
            $id_status       = 2;

            //SESSION ACTIVE? PREVIOUSLY RESERVED COLLECTION?
            $sessionActive = null;
            if($request->session()->has('collect')){
                $sessionActive = $request->session()->get('collect');
                if($sessionActive->id != (int)$id_collect) return redirect()->route('collect.public');
            }

            $collect = $this->repository->find($id_collect);
            if($collect->neighborhood != null && $collect->status > 1)
            {
                $response = [
                    'message' => 'Este horário já estava ou acabou de ser reservado! Escolha outro horário disponível na lista abaixo.',
                    'type'    => 'error'
                ];
                session()->flash('return', $response);
                return redirect()->route('collect.public');
            }
            else
            {
                $new_collect = $this->repository->update(['user_id' => $id_origin, 'neighborhood_id' => $id_neighborhood, 'status' => $id_status, 'reserved_at' => new DateTime()], $collect->id);
                
                //START SESSION
                $request->session()->put('collect', $new_collect);
                $request->session()->put('timer', date('Y-m-d H:i'));

                $response = [
                    'message' => 'Data e horário reservados por 10 minutos, preencha os demais dados para confirmar reserva',
                    'type'    => 'info'
                ];
            } 
        }
        catch (Exception $e) 
        {
            $response = [
                'message' => $e->getMessage(),
                'type'    => 'erro'
            ];
        }

        session()->flash('return', $response);
        return redirect()->route('collect.public.schedule', $collect->id);
    }

    public function update(Request $request, $id)
    {
        try
        {
            $collect    = $this->repository->find($id);
            $idUser     = 2;
            // UPDATE DATA
            $collect = $this->repository->update($request->except('cancellationType_id'), $id);

            $response = [
                'message' => 'Coleta atualizada',
                'type'    => 'info'
            ];

            // CANCELLATION COLLECT
            if($request->has('cancellationType_id'))
            {
                $collect['closed_at']           = Util::dateNowForDB();;
                $collect['cancellationType_id'] = (integer) $request->get('cancellationType_id');
                $collect['user_id_cancelled']   = 2;
                $collect['status']              = 7;

                // UPDATE DATA WITH TYPE CANCELLATION COLLECT
                $this->repository->update($collect->toArray(), $collect->id);
                // Reset values for new releasing collect
                $collect = $this->repository->collectReset($collect);
                $arrayCollect = $collect->toArray();
                // remove id of array
                unset($arrayCollect['id']);
                // insert new releasing, available for schedule
                $this->repository->insert($arrayCollect);

                $response = [
                    'message' => 'Coleta cancelada',
                    'type'    => 'info'
                ];
            }
        }
        catch (ValidatorException $e)
        {
            $response = [
                'message' => $e->getMessageBag(),
                'type'    => 'error'
            ];
        }
        $request->session()->flush();
        
        session()->flash('return', $response);
        return view('collect.public.sent');
    }

    public function cancellation(Request $request, $id)
    {
        $request['cancellationType_id'] = 1;

        return $this->update($request, $id);
    }

    /**
     * Methods not used
     */
    public function destroy($id){}
    public function show($id){}
    public function edit($id){}
    public function store(Request $request){}
}
