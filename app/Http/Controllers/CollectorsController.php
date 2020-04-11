<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CollectorCreateRequest;
use App\Http\Requests\CollectorUpdateRequest;
use App\Repositories\CollectorRepository;
use App\Repositories\UserRepository;
use App\Repositories\NeighborhoodRepository;
use App\Validators\CollectorValidator;

class CollectorsController extends Controller
{

    protected $repository, $userRepository, $neighborhoodRepository;
    protected $validator;

    public function __construct(CollectorRepository $repository, CollectorValidator $validator, UserRepository $userRepository, NeighborhoodRepository $neighborhoodRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->userRepository = $userRepository;
        $this->neighborhoodRepository = $neighborhoodRepository;
    }

    public function index()
    {
        $collectors_list  = $this->repository->all();
        $user_list        = $this->userRepository->where('type', 2)->pluck('name', 'id');

        return view('collector.index', [
            'namepage'      => 'Coletador',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de coletadores'],
            'titlecard'     => 'Lista de coletadores cadastrados',
            'titlemodal'    => 'Cadastrar coletador',
            'add'           => true,
            //List for select
            'user_list'     => $user_list,
            //Info of entitie
            'table'               => $this->repository->getTable(),
            'thead_for_datatable' => ['Nome', 'Hora inicial', 'Hora final', 'Intervalo entre coletas', 'Endereço inicial', 'Status', 'Colaborador', 'Bairros', 'Criado', 'Última atualização'],
            'collectors_list'     => $collectors_list
        ]);
    }

    public function store(CollectorCreateRequest $request)
    {
        try 
        {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $collector = $this->repository->create($request->all());

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

    public function storeCollectorNeighborhoods(Request $request, $collect_id)
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

    public function show($id)
    {
        $collector = $this->repository->find($id);
        $neighborhoods = DB::table('neighborhoods')
                                    ->join('cities', 'neighborhoods.city_id', '=', 'cities.id')
                                    ->select(DB::raw('concat(neighborhoods.name , " - ", cities.name ,"-", cities.UF) as nameWithCity'), 'neighborhoods.id')
                                    ->pluck('nameWithCity', 'neighborhoods.id');

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

    public function edit($id)
    {
        $collector = $this->repository->find($id);
        $user_list = $this->userRepository->where('type', 2)->pluck('name', 'id');

        return view('collector.edit', [
            'namepage'      => 'Coletador',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de coletadores'],
            'titlecard'     => 'Lista de coletadores cadastrados',
            'goback'        => true,
            'add'           => false,
            //Lists for select
            'user_list' => $user_list,
            //Info of entitie
            'table' => $this->repository->getTable(),
            'collector' => $collector
        ]);
    }

    public function update(CollectorUpdateRequest $request, $id)
    {
        try 
        {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $collector = $this->repository->update($request->all(), $id);

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

    public function detachCollectorNeighborhoods($collector_id, $neighborhood_id)
    {   
        $collector = $this->repository->find($collector_id);
        $neighborhood = $collector->neighborhoods()->where('neighborhood_id', $neighborhood_id)->get();

        $detach = $collector->neighborhoods()->detach($neighborhood);

        $response = [
            'message' => 'Coletador atualizado',
            'type'   => 'info',
        ];
        session()->flash('return', $response);
        return redirect()->route('collector.collector_neighborhood.index');
    }
    
    //Methods not used
    public function destroy($id){}
}
