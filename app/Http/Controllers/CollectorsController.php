<?php
namespace App\Http\Controllers;

use App\Entities\Util;
use App\Http\Requests\{CollectorCreateRequest, CollectorUpdateRequest};
use App\Repositories\{CollectRepository, CollectorRepository, NeighborhoodRepository, UserRepository};

use App\Validators\CollectorValidator;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Log;
use Prettus\Validator\Contracts\ValidatorInterface;

date_default_timezone_set('America/Recife');

class CollectorsController extends Controller
{
    protected $repository;

    protected $userRepository;

    protected $neighborhoodRepository;

    protected $collectRepository;

    protected $validator;

    public function __construct(
        CollectorRepository $repository,
        CollectorValidator $validator,
        UserRepository $userRepository,
        NeighborhoodRepository $neighborhoodRepository,
        CollectRepository $collectRepository
    )
    {
        $this->repository             = $repository;
        $this->validator              = $validator;
        $this->userRepository         = $userRepository;
        $this->neighborhoodRepository = $neighborhoodRepository;
        $this->collectRepository      = $collectRepository;
    }

    public function index()
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        $collectors_list = $this->repository->whereNotNull('active')->get();
        $user_list       = $this->userRepository->where('type', 2)->pluck('name', 'id');
        $schedules       = $this->repository->schedules();

        return view('collector.index', [
            'namepage'      => 'Coletador',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de coletadores'],
            'titlecard'     => 'Lista de coletadores',
            'titlemodal'    => 'Cadastrar coletador',
            'add'           => true,
            //List for select
            'user_list'     => $user_list,
            'schedules'     => $schedules,
            //Info of entitie
            'table'               => $this->repository->getTable(),
            'thead_for_datatable' => ['Nome', 'Horários', 'Disponível site', 'Colaborador', 'Bairros', 'Status'],
            'collectors_list'     => $collectors_list,
        ]);
    }

    public function store(CollectorCreateRequest $request)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $arrayMondayToFriday = null;
                $arraySaturday       = null;
                $arraySunday         = null;
                //Send data to array
                if ($request->has('mondayToFriday')) {
                    $arrayMondayToFriday = $request->get('mondayToFriday');
                    $request->merge(['mondayToFriday' => implode(',', $request->get('mondayToFriday'))]);
                }
                if ($request->has('saturday')) {
                    $arraySaturday = $request->get('saturday');
                    $request->merge(['saturday' => implode(',', $request->get('saturday'))]);
                }
                if ($request->has('sunday')) {
                    $arraySunday = $request->get('sunday');
                    $request->merge(['sunday' => implode(',', $request->get('sunday'))]);
                }
                    
                $request->merge(['showInSite' => $request->has('showInSite') ? 'on' : null]);
                $request->merge(['date_start' => Util::setDateLocalBRToDb($request->get('date_start'), true)]);
                    
                // UPDATE COLLECTOR
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                $collector = $this->repository->create($request->all());

                Log::channel('mysql')->info(Auth::user()->name . ' CADASTROU a coletador: ' . $collector);

                $this->repository->setAvailableCollects($arrayMondayToFriday, $arraySaturday, $arraySunday, $request->get('date_start'), $collector->id);

                $response = [
                    'message' => 'Coletador criado',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('collector.index');
    }

    public function attachCollectorNeighborhoods(Request $request, $collect_id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $collector     = $this->repository->find($collect_id);
                $neighborhoods = $request->has('neighborhood_id') ? $request->all()['neighborhood_id'] : null;

                if ($neighborhoods == null) {
                    $response = [
                        'message' => 'Bairros não informados.',
                        'type'    => 'error',
                    ];
                    session()->flash('return', $response);

                    return redirect()->route('collector.index', $collector->id);
                }
                    
                for ($i = 0; $i < count($neighborhoods); $i++) {
                    $collector->neighborhoods()->attach($neighborhoods[$i]);
                    Log::channel('mysql')->info(Auth::user()->name . ' RELACIONOU a bairro: ' . $neighborhoods[$i] . ' ao coletador ' . $collector->name);
                }
                $response = [
                    'message' => 'Bairros relacionados',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('collector.index', $collector->id);
    }

    public function show($id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        if (Auth::user()->type > 3) {
            $collector     = $this->repository->find($id);
            $neighborhoods = $this->neighborhoodRepository->neighborhoodsCities_list()->pluck('name', 'id');

            return view('collector.collector_neighborhood.index', [
                'namepage'      => 'Coletador',
                'threeview'     => 'Cadastros',
                'titlespage'    => ['Vincular bairro'],
                'titlecard'     => 'Bairros vinculados ao ' . $collector->getAttribute('name'),
                'titlemodal'    => 'Relacionar bairros ao coletador ' . $collector->getAttribute('name'),
                'goback'        => true,
                'add'           => true,
                'collector'     => $collector,
                'neighborhoods' => $neighborhoods,
            ]);
        }
            
        return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
    }

    public function edit($id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        if (Auth::user()->type > 3) {
            $collector             = $this->repository->find($id);
            $user_list             = $this->userRepository->where('type', 2)->pluck('name', 'id');
            $schedules             = $this->repository->schedules();
            $collector->date_start = Util::setDate($collector->date_start, false);

            return view('collector.edit', [
                'namepage'      => 'Coletador',
                'threeview'     => 'Cadastros',
                'titlespage'    => ['Cadastro de coletadores'],
                'titlecard'     => 'Lista de coletadores cadastrados',
                'goback'        => true,
                'add'           => false,
                //Lists for select
                'user_list' => $user_list,
                'schedules' => $schedules,
                //Info of entitie
                'table'     => $this->repository->getTable(),
                'collector' => $collector,
            ]);
        }
            
        return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
    }

    public function update(CollectorUpdateRequest $request, $id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $collector_old       = $this->repository->find($id);
                $arrayMondayToFriday = null;
                $arraySaturday       = null;
                $arraySunday         = null;
                //Send data to array
                if (!$request->has('not_update_hours')) {
                    // SEG A SEX
                    if ($request->has('mondayToFriday')) {
                        $arrayMondayToFriday = $request->get('mondayToFriday');
                        $request->merge(['mondayToFriday' => implode(',', $request->get('mondayToFriday'))]);
                    } else {
                        $arrayMondayToFriday = $collector_old->mondayToFriday ? explode(',', $collector_old->mondayToFriday) : null;
                    }
                    // SÁBADO
                    if ($request->has('saturday')) {
                        $arraySaturday = $request->get('saturday');
                        $request->merge(['saturday' => implode(',', $request->get('saturday'))]);
                    } else {
                        $arraySaturday = $collector_old->saturday ? explode(',', $collector_old->saturday) : null;
                    }
                    // DOMINGO
                    if ($request->has('sunday')) {
                        $arraySunday = $request->get('sunday');
                        $request->merge(['sunday' => implode(',', $request->get('sunday'))]);
                    } else {
                        $arraySunday = $collector_old->sunday ? explode(',', $collector_old->sunday) : null;
                    }
                }

                // CHECK IF SITE
                $request->merge(['showInSite' => $request->has('showInSite') ? 'on' : null]);
                if ($request->has('date_start_last_modify')) {
                    $request->merge(['date_start_last_modify' => Util::setDateLocalBRToDb($request->get('date_start_last_modify'), true)]);
                }

                //UPDATE COLLECTOR
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
                $collector = $this->repository->update($request->all(), $id);
                    
                // UPDATE HOURS
                if (!$request->has('not_update_hours')) {
                    //REMOVE OLD DATES AVAILABLES
                    $collects = $this->collectRepository->where('date', '>', $collector->date_start_last_modify)->where('collector_id', $id)->get();
                        
                    foreach ($collects as $collect) {
                        if ($collect->status < 2) {
                            $this->collectRepository->destroy($collect->id);
                        }
                    }
                            
                    $this->repository->setAvailableCollects($arrayMondayToFriday, $arraySaturday, $arraySunday, $request->get('date_start_last_modify'), $collector->id, true);
                }
                    
                Log::channel('mysql')->info(Auth::user()->name . ' ATUALIZOU a coletador: ' . $collector);
                    
                $response = [
                    'message' => 'Coletador atualizado',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('collector.index');
    }

    public function reactivate($id)
    {
        try {
            $this->repository->update(['active' => 'on'], $id);

            $response = [
                'message' => 'Coletador Atualizado',
                'type'    => 'info',
            ];
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('collector.index');
    }

    public function detachCollectorNeighborhoods($collector_id, $neighborhood_id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $collector    = $this->repository->find($collector_id);
                $neighborhood = $collector->neighborhoods()->where('neighborhood_id', $neighborhood_id)->get();

                $collector->neighborhoods()->detach($neighborhood);
                Log::channel('mysql')->info(Auth::user()->name . ' REMOVEU a bairro: ' . $neighborhood . ' do coletador ' . $collector->name);

                $response = [
                    'message' => 'Coletador atualizado',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('collector.index');
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $deleted  = $this->repository->update(['active' => 'off'], $id);
                $collects = $this->collectRepository->where('collector_id', $id)->get();

                if ($deleted) {
                    foreach ($collects as $collect) {
                        if ($collect->status < 2) {
                            $this->collectRepository->destroy($collect->id);
                        }
                    }
                }

                $response = [
                    'message' => 'Coletador deletado',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('collector.index');
    }
}
