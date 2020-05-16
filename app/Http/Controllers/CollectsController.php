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
use App\Repositories\ActivityRepository;
use App\Validators\CollectValidator;
use App\Entities\Util;
use DateTime;
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
                                PersonRepository $peopleRepository, FreeDayRepository $freeDayRepository, PatientTypeRepository $patientTypeRepository,
                                ActivityRepository $activityRepository)
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
        $this->activityRepository         = $activityRepository;
    }

    // CRUD AND MARK COLLECT
    public function index(Request $request)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $goback = false;
            if(!$request->has('neighborhood'))
                $neighborhood_list  = $this->neighborhoodRepository->where('active', 'on')->get();
            else
            {
                $goback = true;
                $neighborhood_model = $this->neighborhoodRepository->find($request->get('neighborhood'));
            }
            return view('collect.index', [
                'namepage'   => 'Agendar coleta',
                'threeview'  => 'Coletas',
                'titlespage' => ['Coletas'],
                'titlecard'  => 'Agendar coleta',
                'goback'     => $goback,
                //List for select
                'neighborhood_list'  => $neighborhood_list  ?? null,
                'neighborhood_model' => $neighborhood_model ?? null,
            ]);
        }
    }

    // EXTRA COLLECT
    public function extra()
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                $cancellationType_list  = $this->cancellationTypeRepository->where('active', 'on')->pluck('name', 'id');
                $patientType_list       = $this->patientTypeRepository->patientTypeWithResponsible_list();
                $collectType_list       = $this->repository->collectType_list();
                $payment_list           = $this->repository->payment_list(false);
                $userAuth_list          = $this->userRepository->where([['id', '>', 1], ['active', 'on'], ['type', '>', 2]])->pluck('name', 'name');
                $people_list            = $this->peopleRepository->all();
                $typeResponsible_list   = $this->peopleRepository->typeResponsible_list();
                $covenant_list          = $this->peopleRepository->covenant_list();
                $collector_list         = $this->collectorRepository->where('active', 'on')->pluck('name', 'id');
                $hour_list              = $this->collectorRepository->schedules();
                $neighborhood_list      = $this->neighborhoodRepository->where('active', 'on')->pluck('name', 'id');

                return view('collect.extra', [
                    'namepage'      => 'Coleta extra',
                    'numberModal'   => '2',
                    'threeview'     => 'Coletas',
                    'titlespage'    => ['Coleta extra'],
                    'titlecard'     => 'Cadastrar coleta extra',
                    'titlecard2'    => 'Adicionar paciente',
                    'titlemodal'    => 'Cadastrar paciente',
                    'goback'        => false,
                    'add'           => false,
                    //Lists for select
                    'cancellationType_list' => $cancellationType_list,
                    'patientType_list'      => $patientType_list,
                    'collectType_list'      => $collectType_list,
                    'payment_list'          => $payment_list,
                    'userAuth_list'         => $userAuth_list,
                    'people_list'           => $people_list,
                    'typeResponsible_list'  => $typeResponsible_list,
                    'covenant_list'         => $covenant_list,
                    'collector_list'        => $collector_list,
                    'hour_list'             => $hour_list,
                    'neighborhood_list'     => $neighborhood_list,
                    //Info of entitie
                    'table' => $this->repository->getTable()
                ]);
            }
            catch(Exception $e)
            {
                $response = [
                    'message' =>  $e->getMessage(),
                    'type'    => 'error'
                ];
                session()->flash('return', $response);
                return redirect()->route('home');
            }
        }
    }
    public function store(Request $request)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            // RESERVED
            $status = 3;
            try
            {
                $request->merge(['date' => Util::setDateLocalBRToDb($request->get('date') . " " . $request->get('hour'), true)]);
                $request->merge(['status' => $status]);

                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                $collect = $this->repository->create($request->all());

                $response = [
                    'message' => 'Coleta criada',
                    'type'   => 'info',
                ];
            }
            catch (ValidatorException $e)
            {
                $response = [
                    'message' =>  $e->getMessageBag(),
                    'type'    => 'error'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('collect.schedule', $collect->id);
        }
    }

    // LIST PAGES - DEIXAR APENAS UM MÉTODO
        public function listReserved()
        {
            if(!Auth::check())
            {
                session()->flash('return');
                return view('auth.login');
            }
            else
            {
                $collect_list = $this->repository->where('neighborhood_id', '!=', null)
                                                    ->where([['status', '>', 1], ['status', '<', 7]])->get();

                return view('collect.reserved', [
                    'namepage'   => 'Coletas reservadas',
                    'threeview'  => 'Coletas',
                    'titlespage' => ['Coletas reservadas'],
                    'titlecard'  => 'Lista de coletas reservadas',
                    //Info of entitie
                    'table'               => $this->repository->getTable(),
                    'thead_for_datatable' => ['Data/Hora', 'Código', 'Pagamento Taxa', 'Bairro', 'Endereço', 'Coletador', 'Status'],
                    'collect_list'        => $collect_list
                ]);
            }
        }
        public function listCancelled()
        {
            if(!Auth::check())
            {
                session()->flash('return');
                return view('auth.login');
            }
            else
            {
                $collect_list = $this->repository->where('neighborhood_id', '!=', null)
                                                    ->where('status', '>', 6)->get();

                return view('collect.reserved', [
                    'namepage'   => 'Coletas canceladas',
                    'threeview'  => 'Coletas',
                    'titlespage' => ['Coletas canceladas'],
                    'titlecard'  => 'Lista de coletas canceladas',
                    //Info of entitie
                    'table'               => $this->repository->getTable(),
                    'thead_for_datatable' => ['Data/Hora', 'Código', 'Pagamento Taxa', 'Bairro', 'Endereço', 'Coletador', 'Status'],
                    'collect_list'        => $collect_list
                ]);
            }
        }
    // END LIST PAGES

    // API TO GET AVAILABLES
    public function available(Request $request)
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
                            ->whereBetween('date', [$dateOfCollect . ' 00:00:01', $dateOfCollect .  ' 23:59:59'])
                            ->whereIn('collector_id', $array_collectors)
                            ->where('status', '1')
                            ->orderBy('date')->orderBy('collector_id')
                            ->get();

        for ($i=0; $i < count($freeDay_list); $i++)
            $collect_list = $collect_list->whereNotBetween('date', [$freeDay_list[$i]['dateStart'], $freeDay_list[$i]['dateEnd']]);

        return $collect_list->toJson();
    }

    public function schedule($id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                $collect = $this->repository->find($id);

                $cancellationType_list  = $this->cancellationTypeRepository->where('active', 'on')->pluck('name', 'id');
                $patientType_list       = $this->patientTypeRepository->patientTypeWithResponsible_list();
                $collectType_list       = $this->repository->collectType_list();
                $payment_list           = $this->repository->payment_list(false);
                $userAuth_list          = $this->userRepository->where([['id', '>', 1], ['active', 'on'], ['type', '>', 2]])->pluck('name', 'name');
                $people_list            = $this->peopleRepository->orderBy('created_at', 'desc')->distinct('cpf')->get();
                $typeResponsible_list   = $this->peopleRepository->typeResponsible_list();
                $covenant_list          = $this->peopleRepository->covenant_list();
                $quant                  = count($collect->people);
                $priceString            = "R$ " . count($collect->people) > 2 ? (count($collect->people)-1) * $collect->neighborhood->displacementRate : $collect->neighborhood->displacementRate;

                return view('collect.edit', [
                    'namepage'      => 'Agendar coleta',
                    'numberModal'   => '2',
                    'threeview'     => 'Coletas',
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
                    'payment_list'          => $payment_list,
                    'userAuth_list'         => $userAuth_list,
                    'people_list'           => $people_list,
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
                return redirect()->route('collect.index');
            }
        }
    }

    public function update(Request $request, $id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                $collect = $this->repository->find($id);
                $id_user = Auth::user()->id;

                // UPDATE DATA
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
                $collect = $this->repository->update($request->except('cancellationType_id', 'site'), $id);

                $response = [
                    'message' => 'Coleta atualizada',
                    'type'    => 'info'
                ];

                // CANCELLATION COLLECT
                if($request->has('cancellationType_id'))
                {
                    $collect['closed_at']           = Util::dateNowForDB();;
                    $collect['cancellationType_id'] = $request->get('cancellationType_id');
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
                        'message' => 'Coleta cancelada',
                        'type'    => 'info'
                    ];

                    session()->flash('return', $response);
                    return redirect()->route('collect.index') ;
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
    }

    public function reserve(Request $request)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $id_collect       = $request->get('infoCollect');
            $id_neighborhood = $request->get('neighborhood_id');
            $id_origin       = Auth::user()->id;
            // Reservada
            $status          = 3;
            $collect         = $this->repository->find($id_collect);

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
                try
                {
                    $this->repository->update(['user_id' => $id_origin, 'neighborhood_id' => $id_neighborhood, 'status' => $status, 'reserved_at' => new DateTime()], $collect->id);

                    $response = [
                        'message' => 'Data e horário reservados',
                        'type'    => 'info'
                    ];
                }
                catch (Exception $e)
                {
                    $response = [
                        'message' => $e->getMessage(),
                        'type'    => 'erro'
                    ];
                }
                session()->flash('return', $response);
                return redirect()->route('collect.schedule', $collect->id);
            }
        }
    }

    public function confirmed($id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $collect = $this->repository->find($id);
            $id_user = Auth::user()->id;

            if($collect->status > 3)
            {
                $response = [
                    'message' => 'Não é possível confirmar uma coleta que não esta reservada',
                    'type'    => 'error'
                ];
                session()->flash('return', $response);
                return redirect()->route('collect.index');
            }
            else
            {
                try
                {
                    // 4 = CONFIRMADA
                    $this->repository->update(['status' => 4, 'user_id_confirmed' => $id_user, 'confirmed_at' =>  Util::dateNowForDB()], $collect->id);
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
    }

    public function close(Request $request, $id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $collect = $this->repository->find($id);
            $id_user = Auth::user()->id;
            try
            {
                // CANCELLATION COLLECT
                if($request->get('cancellationType_id'))
                {
                    $collect['closed_at'] = Util::dateNowForDB();
                    // 8 = CANCELADO EM ROTA
                    // UPDATE DATA WITH TYPE CANCELLATION COLLECT
                    $this->repository->update(['status' => 8, 'cancellationType_id' => (integer)$request->get('cancellationType_id'), 'user_id_cancelled' => $id_user, 'closed_at' => Util::dateNowForDB()], $collect->id);
                }
                else
                    $this->repository->update(['status' => 6, 'closed_at' => Util::dateNowForDB()], $collect->id); //CONCLUÍDA

                $response = [
                        'message' => 'Coleta ' . $collect->id . ' finalizada',
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
            return redirect()->route('activity.index');
        }
    }

    /**
     * Methods not used
     */
    public function destroy($id){}
    public function edit($id){}
    public function show($id){}
}
