<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Repositories\CollectRepository;
use App\Repositories\NeighborhoodRepository;
use App\Repositories\CancellationTypeRepository;
use App\Repositories\PatientTypeRepository;
use App\Repositories\PersonRepository;
use App\Repositories\CollectorRepository;
use App\Repositories\FreeDayRepository;
use App\Validators\CollectValidator;
use App\Entities\Util;
use App\Mail\SendMailSchedule;
use DateTime;
use Exception;
use DB;
use Log;

date_default_timezone_set('America/Recife');

class PublicCollectController extends Controller
{

    protected $repository;
    protected $validator;

    public function __construct(CollectRepository $repository, CollectValidator $validator, NeighborhoodRepository $neighborhoodRepository,
                                CancellationTypeRepository $cancellationTypeRepository ,PersonRepository $peopleRepository, PatientTypeRepository $patientTypeRepository,
                                CollectorRepository $collectorRepository, FreeDayRepository $freeDayRepository)
    {
        $this->repository                 = $repository;
        $this->validator                  = $validator;
        $this->neighborhoodRepository     = $neighborhoodRepository;
        $this->cancellationTypeRepository = $cancellationTypeRepository;
        $this->peopleRepository           = $peopleRepository;
        $this->patientTypeRepository      = $patientTypeRepository;
        $this->collectorRepository        = $collectorRepository;
        $this->freeDayRepository          = $freeDayRepository;
    }

    // API TO GET AVAILABLES
    public function available(Request $request)
    {
        try
        {
            $site = $request->has('site') ? true : false;
            $where = $site ? ['active' => 'on', 'showInSite' => 'on'] : ['active' => 'on'];

            $neighborhood_id = $request->get('neighborhood_id');
            $dateOfCollect   = Util::setDateLocalBRToDb($request->get('datecollect'), false);
            $dateNow         = date("Y-m-d h:i");
            $collector_list  = $this->collectorRepository->where($where)->get();

            $array_collectors = [];
            foreach($collector_list as $collector)
            {
                foreach($collector->neighborhoods as $neighborhood)
                    if($neighborhood->id == $neighborhood_id) array_push($array_collectors, $collector->id);
            }

            $freeDay_list = $this->freeDayRepository->where('dateStart', '>', $dateNow)->get();
            $collect_list = DB::table('collects')
                                ->select('collects.id', 'collects.date', 'collects.hour', 'collects.status', 'collectors.name')
                                ->join('collectors', 'collects.collector_id', '=', 'collectors.id')
                                ->whereDate('date', $dateOfCollect)
                                ->whereIn('collector_id', $array_collectors)
                                ->where('status', '1')
                                ->orderBy('date')->orderBy('collector_id');

            for ($i=0; $i < count($freeDay_list); $i++)
                $collect_list = $collect_list->whereNotBetween('date', [$freeDay_list[$i]['dateStart'], $freeDay_list[$i]['dateEnd']]);

            Log::channel('mysql')->info('Get Api available: ' . $dateOfCollect . " - Bairro: " . $neighborhood_id);

            return $collect_list->get()->toJson();
        }
        catch(Exception $e)
        {
            Log::channel('mysql')->info('Erro api available: ' . Util::getException($e));
        }
    }

    // API RELEASE IN 10 MIN. COLLECTS WITH STATUS = NEW
    public function release(Request $request)
    {
        $auth = $request->has('list_all_new_collects_more_10_min') ? true : false;
        if($auth)
        {
            $collects = $this->repository->where('status', 2)->get();
            if(count($collects) > 0)
            {
                try
                {
                    foreach($collects as $collect)
                    {
                        $reserved_at = new DateTime($collect->reserved_at);
                        $diff_date = $reserved_at->diff(new DateTime());
                        // dd($diff_date->i);
                        if($diff_date->i > 10)
                        {
                            $id_user = 2;
                            // UPDATE DATA WITH TYPE CANCELLATION COLLECT
                            $this->repository->update([
                                'status'                => 7,
                                'cancellationType_id'   => 2,
                                'user_id_cancelled'     => $id_user,
                                'closed_at'             => Util::dateNowForDB()
                            ], $collect->id);
                            // Reset values for new releasing collect
                            $collect = $this->repository->collectReset($collect);
                            $arrayCollect = $collect->toArray();
                            // remove id of array
                            unset($arrayCollect['id']);
                            // insert new releasing, available for schedule
                            $this->repository->insert($arrayCollect);
                            Log::channel('mysql')->info('Get Api release: ' . $collect->date . ' - Tempo: ' . $diff_date->i);
                        }
                    }
                }
                catch(Exception $e)
                {
                    Log::channel('mysql')->info('Erro api release: ' . Util::getException($e));

                    return false;
                }
            }
            return count($collects) . " - " . date('d/m/Y H:i');
        }
    }

    // COLLECT PUBLIC
    public function index(Request $request)
    {
        // dd($request->session()->all());
        $sessionActive = null;
        if($request->session()->has('collect'))
        {
            $sessionActive = $request->session()->get('collect');
            $collect = $this->repository->find($sessionActive->id);
            if($collect->status == 7) $request->session()->flush();
        }

        if(!$request->has('neighborhood')) $neighborhood_list = $this->neighborhoodRepository->where('active', 'on')->get();
        else $neighborhood_model = $this->neighborhoodRepository->find($request->get('neighborhood'));

        return view('collect.public.index', [
            'titlespage' => ['Solicitação de Coleta Domiciliar'],
            'titlecard'  => 'Solicitar Agendamento',
            'ambulancy'  => true,
            'sessionActive'         => $sessionActive,
            'neighborhood_list'     => $neighborhood_list ?? null,
            'neighborhood_model'    => $neighborhood_model ?? null,
        ]);
    }

    public function schedule(Request $request, $id)
    {
        try
        {
            $collect = $this->repository->find($id);
            if($request->session()->has('collect'))
            {
                $sessionActive = $request->session()->get('collect');
                $collect = $this->repository->find($sessionActive->id);
                if($collect->status == 7)
                {
                    $request->session()->flush();
                    $response = [
                        'message' => 'Período de solicitação expirado. A sessão foi encerrada.',
                        'type'    => 'info'
                    ];
                    session()->flash('return', $response);
                    return redirect()->route('public.index');
                }
            }
            else return redirect()->route('public.index');

            $cancellationType_list  = $this->cancellationTypeRepository->where('active', 'on')->pluck('name', 'id');
            $patientType_list       = $this->patientTypeRepository->patientTypeWithResponsible_list();
            $collectType_list       = $this->repository->collectType_list();
            $payment_list           = $this->repository->payment_list(true);
            $typeResponsible_list   = $this->peopleRepository->typeResponsible_list();
            $covenant_list          = $this->peopleRepository->covenant_list();
            $quant                  = count($collect->people);
            $price                  = $quant == 0   ? 0 : $collect->neighborhood->displacementRate;
            $price                  = $quant  > 2   ? ($quant-1) * $collect->neighborhood->displacementRate : $collect->neighborhood->displacementRate;
            $priceString            = "R$ " . (string) $price;
            // dd($price);
            return view('collect.public.edit', [
                'titlespage' => ['Solicitação de Coleta Domiciliar'],
                'titlecard'  => 'Dados da solicitação',
                'titlemodal' => 'Cadastrar paciente',
                'ambulancy'  => true,
                //Lists for select
                'cancellationType_list' => $cancellationType_list,
                'patientType_list'      => $patientType_list,
                'collectType_list'      => $collectType_list,
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
                'message' =>  'Ocorreu um erro. Nossos técnicos foram avisados. Pedimos desculpas pelo transtorno.',
                'type'    => 'error'
            ];
            Log::channel('mysql')->info('Agendamento público: ' . Util::getException($e));
            session()->flash('return', $response);
            return redirect()->route('public.index');
        }
    }

    public function reserve(Request $request)
    {
        try
        {
            $id_collect      = $request->get('infoCollect');
            $id_neighborhood = $request->get('neighborhood_id');
            $id_origin       = 2;
            $id_status       = 2;

            if($id_collect != "Selecione")
            {
                //SESSION ACTIVE? PREVIOUSLY RESERVED COLLECTION?
                $sessionActive = null;
                if($request->session()->has('collect')){
                    $sessionActive = $request->session()->get('collect');
                    if($sessionActive->id != (int)$id_collect) return redirect()->route('public.index');
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
                        'message' => 'Data e horário reservados por 10 minutos, preencha os demais dados para confirmar a solicitação',
                        'type'    => 'info'
                    ];
                    session()->flash('return', $response);
                    return redirect()->route('collect.public.schedule', $collect->id);
                }
            }
            else
            {
                $response = [
                    'message' => 'Horário não selecionado',
                    'type'    => 'error'
                ];
                session()->flash('return', $response);
                return redirect()->route('public.index');
            }
        }
        catch (Exception $e)
        {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'erro'
            ];
            session()->flash('return', $response);
            return redirect()->route('public.index');
        }
    }

    public function update(Request $request, $id)
    {
        try
        {
            //SESSION ACTIVE? PREVIOUSLY RESERVED COLLECTION?
            if($request->session()->has('collect'))
            {
                $collect = $this->repository->find($id);
                // USER SITE = 2
                $id_user = 2;
                // UPDATE DATA
                $request->merge(['status' => 3]);
                $collect = $this->repository->update($request->except('cancellationType_id'), $id);

                $request->session()->flush();
                // CANCELLATION COLLECT
                if($request->has('cancellationType_id'))
                {
                    $collect['closed_at']           = Util::dateNowForDB();;
                    $collect['cancellationType_id'] = (integer) $request->get('cancellationType_id');
                    $collect['user_id_cancelled']   = $id_user;
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
                        'message' => 'Solicitação de agendamento cancelada',
                        'type'    => 'info'
                    ];
                    session()->flash('return', $response);
                    return redirect()->route('public.index');
                }
                $response = [
                    'message'   => 'Solicitação de agendamento enviada',
                    'text'      => 'Anote o número da sua solicitação: Nº ' . $collect->id,
                    'describe'  => count($collect->people) . ' paciente(s) na data: ' . $collect->formatted_date . ' às ' . $collect->hour . ' no seguinte endereço: ' . $collect->address . ', ' . $collect->numberAddress . ', ' . $collect->neighborhood->name . ' ' . $collect->cep,
                    'type'      => 'info'
                ];
                // send email
                foreach ($collect->people as $person) {
                    session()->flash('return', $response);
                    Mail::to($person->email)->queue(new SendMailSchedule());
                }
            }
            else
            {
                $response = [
                    'message' => 'Sessão encerrada',
                    'type'    => 'info'
                ];
                session()->flash('return', $response);
                return redirect()->route('public.index');
            }
        }
        catch (Exception $e)
        {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error'
            ];
            Log::channel('mysql')->info('Reservar Agendamento público: ' . Util::getException($e));
            session()->flash('return', $response);
            return redirect()->route('public.index');
        }

        session()->flash('return', $response);
        return view('collect.public.sent');

    }

    public function cancellation(Request $request, $id)
    {
        $request['cancellationType_id'] = 2;
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
