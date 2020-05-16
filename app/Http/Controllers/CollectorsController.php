<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Entities\Util;

use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CollectorCreateRequest;
use App\Http\Requests\CollectorUpdateRequest;
use App\Repositories\CollectorRepository;
use App\Repositories\UserRepository;
use App\Repositories\NeighborhoodRepository;
use App\Repositories\CollectRepository;
use App\Validators\CollectorValidator;

date_default_timezone_set('America/Recife');

class CollectorsController extends Controller
{

    protected $repository, $userRepository, $neighborhoodRepository;
    protected $validator;

    public function __construct(CollectorRepository $repository, CollectorValidator $validator, UserRepository $userRepository, NeighborhoodRepository $neighborhoodRepository,
                                CollectRepository $collectRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->userRepository = $userRepository;
        $this->neighborhoodRepository = $neighborhoodRepository;
        $this->collectRepository = $collectRepository;
    }

    public function index()
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $collectors_list  = $this->repository->all();
            $user_list        = $this->userRepository->where('type', 2)->pluck('name', 'id');
            $schedules        = $this->repository->schedules();
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
                'collectors_list'     => $collectors_list
            ]);
        }
    }

    public function store(CollectorCreateRequest $request)
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
                $arrayMondayToFriday = null;
                $arraySaturday = null;
                $arraySunday = null;
                // dd($request->all());
                //Send data to array
                if($request->has('mondayToFriday'))
                {
                    $arrayMondayToFriday = $request->get('mondayToFriday');
                    $request->merge(['mondayToFriday' => implode(',', $request->get('mondayToFriday'))]);
                }
                if($request->has('saturday'))
                {
                    $arraySaturday = $request->get('saturday');
                    $request->merge(['saturday' => implode(',', $request->get('saturday'))]);
                }
                if($request->has('sunday'))
                {
                    $arraySunday = $request->get('sunday');
                    $request->merge(['sunday' => implode(',', $request->get('sunday'))]);
                }

                $request->merge(['showInSite' => $request->has('showInSite') ? 'on' : null]);

                // UPDATE COLLECTOR
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                $collector = $this->repository->create($request->all());

                $this->repository->setAvailableCollects($arrayMondayToFriday, $arraySaturday, $arraySunday, $request->get('dateStart'), $collector->id);

                $response = [
                    'message' => 'Coletador criado',
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
            return redirect()->route('collector.index');
        }
    }

    public function attachCollectorNeighborhoods(Request $request, $collect_id)
    {
        if(!\Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                $collector = $this->repository->find($collect_id);
                $neighborhoods = $request->has('neighborhood_id') ? $request->all()['neighborhood_id'] : null;

                if($neighborhoods == null){
                    $response = [
                        'message' =>  'Bairros não informados.',
                        'type'    => 'error'
                    ];
                    session()->flash('return', $response);
                    return redirect()->route('collector.index', $collector->id);
                }
                else
                {
                    for ($i=0; $i < count($neighborhoods); $i++)
                        $collector->neighborhoods()->attach($neighborhoods[$i]);

                    $response = [
                        'message' => 'Bairros relacionados',
                        'type'   => 'info',
                    ];
                }
            }
            catch (ValidatorException $e)
            {
                $response = [
                    'message' =>  $e->getMessageBag(),
                    'type'    => 'error'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('collector.index', $collector->id);
        }
    }

    public function show($id)
    {
        if(!\Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $collector = $this->repository->find($id);
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
                'neighborhoods' => $neighborhoods
            ]);
        }
    }

    public function edit($id)
    {
        if(!\Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $collector = $this->repository->find($id);
            $user_list = $this->userRepository->where('type', 2)->pluck('name', 'id');
            $schedules  = $this->repository->schedules();

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
                'table' => $this->repository->getTable(),
                'collector' => $collector
            ]);
        }
    }

    public function update(CollectorUpdateRequest $request, $id)
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
                $collector_old = $this->repository->find($id);
                $arrayMondayToFriday = null;
                $arraySaturday = null;
                $arraySunday = null;
                //Send data to array
                // SEG A SEX
                if($request->has('mondayToFriday'))
                {
                    $arrayMondayToFriday = $request->get('mondayToFriday');
                    $request->merge(['mondayToFriday' => implode(',', $request->get('mondayToFriday'))]);
                }
                else
                    $arrayMondayToFriday = $collector_old->mondayToFriday ? explode(',', $collector_old->mondayToFriday) : null;
                // SÁBADO
                if($request->has('saturday'))
                {
                    $arraySaturday = $request->get('saturday');
                    $request->merge(['saturday' => implode(',', $request->get('saturday'))]);
                }
                else
                    $arraySaturday = $collector_old->saturday ? explode(',', $collector_old->saturday) : null;
                // DOMINGO
                if($request->has('sunday'))
                {
                    $arraySunday = $request->get('sunday');
                    $request->merge(['sunday' => implode(',', $request->get('sunday'))]);
                }
                else
                    $arraySunday = $collector_old->sunday ? explode(',', $collector_old->sunday) : null;

                // CHECK IF SITE
                $request->merge(['showInSite' => $request->has('showInSite') ? 'on' : null]);

                //UPDATE COLLECTOR
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
                $collector = $this->repository->update($request->all(), $id);

                //REMOVE OLD DATES AVAILABLES
                $collects = $this->collectRepository->where('collector_id', $id)->get();
                foreach($collects as $collect)
                    if($collect->status < 2) $this->collectRepository->destroy($collect->id);

                $this->repository->setAvailableCollects($arrayMondayToFriday, $arraySaturday, $arraySunday, $request->get('dateStart'), $collector->id);

                $response = [
                    'message' => 'Coletador atualizado',
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
            return redirect()->route('collector.index');
        }
    }

    public function detachCollectorNeighborhoods($collector_id, $neighborhood_id)
    {
        if(!\Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                $collector = $this->repository->find($collector_id);
                $neighborhood = $collector->neighborhoods()->where('neighborhood_id', $neighborhood_id)->get();

                $detach = $collector->neighborhoods()->detach($neighborhood);

                $response = [
                    'message' => 'Coletador atualizado',
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
            return redirect()->route('collector.index');
        }
    }

    public function destroy($id)
    {
        if(!\Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                $deleted = $this->repository->update(['active' => 'off'], $id);
                $collects = $this->collectRepository->where('collector_id', $id)->get();

                if($deleted){
                    foreach($collects as $collect){
                        if($collect->status < 2) $this->collectRepository->destroy($collect->id);
                    }
                }

                $response = [
                    'message' => 'Coletador deletado',
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
            return redirect()->route('collector.index');
        }
    }
}
