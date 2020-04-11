<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\NeighborhoodCreateRequest;
use App\Http\Requests\NeighborhoodUpdateRequest;
use App\Repositories\NeighborhoodRepository;
use App\Repositories\CityRepository;
use App\Validators\NeighborhoodValidator;

class NeighborhoodsController extends Controller
{
    protected $repository, $cityRepository;
    protected $validator;

    public function __construct(NeighborhoodRepository $repository, NeighborhoodValidator $validator, CityRepository $cityRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->cityRepository  = $cityRepository;
    }

    public function index()
    {
        $neighborhoods  = $this->repository->all();
        $cities_list  = $this->cityRepository->all(); // for card city
        $regions_list = $this->repository->regions_list(); 
        $cities_pluck_list  = $this->cityRepository->pluck('name', 'id'); // for select in neighborhood

        return view('neighborhood.index', [
            'namepage'      => 'Bairro',
            'numberModal'   => '2',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de bairros'],
            'titlecard'     => 'Bairros cadastrados',
            'titlemodal'    => 'Cadastrar bairro',
            'titlecard2'    => 'Cidades cadastradas',
            'titlemodal2'   => 'Cadastrar cidade',
            'add'           => true,
            //Lists of selects
            'regions_list' => $regions_list,
            'cities_list' => $cities_list,
            'cities_pluck_list' => $cities_pluck_list,
            //Info of entitie
            'table' => $this->repository->getTable(),
            'table2' => $this->cityRepository->getTable(),
            'thead_for_datatable' => ['Nome', 'Taxa', 'Região', 'Status', 'Cidade', 'Criado', 'Última atualização'],
            'thead_for_datatable2' => ['Nome', 'UF', 'Status'],
            'neighborhoods' => $neighborhoods,
        ]);
    }

    public function store(NeighborhoodCreateRequest $request)
    {
        try 
        {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $neighborhood = $this->repository->create($request->all());

            $response = [
                'message' => 'Bairro criado',
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
        return redirect()->route('neighborhood.index');
    }
    
    public function edit($id)
    {
        $neighborhood = $this->repository->find($id);
        $regions_list = $this->repository->regions_list();
        $cities_pluck_list  = $this->cityRepository->pluck('name', 'id');

        return view('neighborhood.edit', [
            'namepage'      => 'Bairro',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de bairros'],
            'titlecard'     => 'Bairros cadastrados',
            'titlemodal'    => 'Cadastrar bairro',
            'add'           => false,
            'goback'        => true,
            //Lists of selects
            'regions_list'      => $regions_list,
            'cities_pluck_list' => $cities_pluck_list,
            //Info of entitie
            'table'        => $this->repository->getTable(),
            'neighborhood' => $neighborhood
        ]);
    }

    public function update(NeighborhoodUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $neighborhood = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Bairro atualizado',
                'type'   => 'info',
            ];
        } catch (ValidatorException $e) {
            $response = [
                'message' =>  $e->getMessageBag(),
                'type'    => 'error'
            ];
        }
        session()->flash('return', $response);
        return redirect()->route('neighborhood.index');
    }

    //Method not used
    public function destroy($id){}
    public function show($id){}
}
