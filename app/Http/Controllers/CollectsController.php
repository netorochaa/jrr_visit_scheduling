<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CollectCreateRequest;
use App\Http\Requests\CollectUpdateRequest;
use App\Repositories\CollectRepository;
use App\Repositories\NeighborhoodRepository;
use App\Repositories\CollectorRepository;
use App\Repositories\CancellationTypeRepository;
use App\Repositories\UserRepository;
use App\Repositories\PersonRepository;
use App\Repositories\FreeDayRepository;
use App\Repositories\PatientTypeRepository;
use App\Validators\CollectValidator;
use App\Entities\Collect;
use App\Entities\Util;
use DateTime;
use Carbon;
use Exception;
use Auth;
use DB;

date_default_timezone_set('America/Recife');

class CollectsController extends Controller
{
    protected $repository, $neighborhoodRepository, $collectorRepository, $cancellationTypeRepository, $userRepository, $peopleRepository, $freeDayRepository, $patientTypeRepository;
    protected $validator;

    public function __construct(CollectRepository $repository, CollectValidator $validator, NeighborhoodRepository $neighborhoodRepository,
                                CollectorRepository $collectorRepository, CancellationTypeRepository $cancellationTypeRepository, UserRepository $userRepository,
                                PersonRepository $peopleRepository, FreeDayRepository $freeDayRepository, PatientTypeRepository $patientTypeRepository)
    {
        $this->repository                 = $repository;
        $this->validator                  = $validator;
        $this->neighborhoodRepository     = $neighborhoodRepository;
        $this->collectorRepository        = $collectorRepository;
        $this->cancellationTypeRepository = $cancellationTypeRepository;
        $this->userRepository             = $userRepository;
        $this->peopleRepository           = $peopleRepository;
        $this->freeDayRepository          = $freeDayRepository;
        $this->patientTypeRepository      = $patientTypeRepository;
    }

    // CRUD AND MARK COLLECT
    public function index(Request $request)
    {
        if(auth()->check())
        {
            $dateNow                = date("Y-m-d h:i");
            $collector_list         = $this->collectorRepository->with('neighborhoods')->get();
            $freeDay_list           = $this->freeDayRepository->where('dateStart', '>', $dateNow)->get();
            $collect_list           = $this->repository->all();
            $collectAvailables_list = $collect_list->where('date', '>', $dateNow)->sortBy('date')->sortBy('collector_id');
            // 
            for ($i=0; $i < count($freeDay_list); $i++) 
                $collectAvailables_list = $collectAvailables_list->whereNotBetween('date', [$freeDay_list[$i]['dateStart'], $freeDay_list[$i]['dateEnd']]);
                
            return view('collect.index', [
                'namepage'   => 'Coletas',
                'threeview'  => null,
                'titlespage' => ['Coletas'],
                'titlecard'  => 'Lista de coletas',
                'titlemodal' => 'Agendar coleta',
                'add'        => true,
                //List for select
                'freeDay_list'           => $freeDay_list,
                'collector_list'         => $collector_list,
                'collectAvailables_list' => $collectAvailables_list,
                //Info of entitie
                'table'               => $this->repository->getTable(),
                'thead_for_datatable' => ['Data/Hora', 'Código', 'Status', 'Pagamento Taxa', 'Bairro', 'Endereço', 'Coletador'],
                'collect_list'        => $collect_list
            ]);
        }
        else return view('auth.login');
    }

    public function schedule($id)
    {
        try
        {
            $collect = $this->repository->find($id);

            $cancellationType_list  = $this->cancellationTypeRepository->pluck('name', 'id');
            $patientType_list       = $this->patientTypeRepository->patientTypeWithResponsible_list();
            $collectType_list       = $this->repository->collectType_list();
            $statusCollects_list    = $this->repository->statusCollects_list();
            $payment_list           = $this->repository->payment_list();
            $userAuth_list          = $this->userRepository->where('type', '>', 2)->pluck('name', 'name');
            $people_list            = $this->peopleRepository->all();
            $typeResponsible_list   = $this->peopleRepository->typeResponsible_list();
            $covenant_list          = $this->peopleRepository->covenant_list();
            $quant                  = count($collect->people);
            $price                  = "R$ " . (string) count($collect->people) * $collect->neighborhood->displacementRate;

            return view('collect.edit', [
                'namepage'      => 'Coletas',
                'numberModal'   => '2',
                'threeview'     => null,
                'titlespage'    => ['Coletas'],
                'titlecard'     => 'Agendamento de coleta',
                'titlecard2'    => 'Adicionar paciente',
                'titlemodal'    => 'Cadastrar paciente',
                'goback'        => false,
                'add'           => false,
                //Lists for select
                'cancellationType_list' => $cancellationType_list,
                'patientType_list'      => $patientType_list,
                'collectType_list'      => $collectType_list,
                'statusCollects_list'   => $statusCollects_list,
                'cancellationType'      => $statusCollects_list,
                'payment_list'          => $payment_list,
                'userAuth_list'         => $userAuth_list,
                'people_list'           => $people_list,
                'typeResponsible_list'  => $typeResponsible_list,
                'covenant_list'         => $covenant_list,
                'quant'                 => $quant,
                'price'                 => $price,
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
            return redirect()->route('collect.index');
        }
    }

    public function store(CollectCreateRequest $request)
    {
        try 
        {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $collect = $this->repository->create($request->all());

            $response = [
                'message' => 'Collect created.',
                'data'    => $collect->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } 
        catch (ValidatorException $e) 
        {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    public function update(CollectUpdateRequest $request, $id)
    {
        try 
        {
            $collect = $this->repository->find($id);
           
            // UPDATE
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $collect = $this->repository->update($request->except('cancellationType_id'), $id);

            $response = [
                'message' => 'Coleta atualizada',
                'type'    => 'info'
            ];
            
            // CANCELLATION COLLECT
            if($request->has('cancellationType_id'))
            {
                $dateNow                        = Util::dateNowForDB();
                $collect['closed_at']           = $dateNow;
                $collect['cancellationType_id'] = (integer) $request->get('cancellationType_id');
                $collect['status']              = '7';

                // UPDATE FOR CANCELLATION COLLECT
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

                session()->flash('return', $response);
                return redirect()->route('collect.index');
            }
        } 
        catch (ValidatorException $e) 
        {
            $response = [
                'message' => $e->getMessageBag(),
                'type'    => 'error'
            ];
        }
        session()->flash('return', $response);
        return redirect()->route('collect.schedule', $collect->id);
    }

    public function reserve(Request $request)
    {
        $ids = explode(",", $request->all()['infoCollect']);
        $idCollect = $ids[0];
        $idNeighborhood = $ids[1];
        $idOrigin = Auth::user()->id;

        $collect = $this->repository->find($idCollect);

        if($collect->neighborhood != null && $collect->status > 1)
        {
            $response = [
                'message' => 'Este horário já estava ou acabou de ser reservado! Escolha outro horário disponível na lista abaixo.',
                'type'    => 'error'
            ];
            session()->flash('return', $response);
            return redirect()->route('collect.index');
        }
        else
        {
            try {
                Collect::where('id', $idCollect)->update(['user_id' => $idOrigin, 'neighborhood_id' => $idNeighborhood, 'status' => 3, 'reserved_at' => new DateTime()]);
                $response = [
                    'message' => 'Data e horário reservados',
                    'type'    => 'info'
                ];
            } catch (\Exception $e) {
                $response = [
                    'message' => $e->getMessage(),
                    'type'    => 'erro'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('collect.schedule', $collect->id);
        }
    }

    public function confirmed($id)
    {
        $collect = $this->repository->find($id);        
        if($collect->status > 3)
        {
            $response = [
                'message' => 'Coleta já havia sido confirmada',
                'type'    => 'error'
            ];
            session()->flash('return', $response);
            return redirect()->route('collect.index');
        }
        else
        {
            try 
            {
                Collect::where('id', $collect->id)->update(['status' => 4]);
                $response = [
                    'message' => 'Coleta ' . $collect->id . ' confirmada',
                    'type'    => 'info'
                ];
            } 
            catch (Exception $e) 
            {
                $response = [
                    'message' => $e->getMessage(),
                    'type'    => 'error'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('collect.index');
        }
    }

    /**
     * Methods not used
     */
    public function destroy($id){}
    public function edit($id){}
    public function show($id){}
}
