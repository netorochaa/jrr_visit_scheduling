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
        $neighborhoods_list  = $this->repository->all();
        $cities_list  = $this->cityRepository->all();
        $regions_list = $this->repository->regions_list();
        $cities_pluck_list  = $this->cityRepository->pluck('name', 'id');

        return view('neighborhood.index', [
            'namepage'      => 'Bairro',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de bairros', 'Cadastro de Cidade'],
            'titlecard'     => 'Lista de bairros cadastrados',
            'titlemodal'    => 'Cadastrar bairro',
            'titlecard2'     => 'Lista de cidades cadastrados',
            'titlemodal2'    => 'Cadastrar cidade',
            'number'        => '2',
            //List of entitie
            'table' => $this->repository->getTable(),
            'table2' => $this->cityRepository->getTable(),
            'thead_for_datatable' => ['Nome', 'Taxa', 'Região', 'Status', 'Cidade', 'Criado', 'Última atualização'],
            'thead_for_datatable2' => ['Nome', 'UF', 'Status','Criado', 'Última atualização'],
            'neighborhoods_list' => $neighborhoods_list,
            'regions_list' => $regions_list,
            'cities_list' => $cities_list,
            'cities_pluck_list' => $cities_pluck_list
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  NeighborhoodCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(NeighborhoodCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $neighborhood = $this->repository->create($request->all());

            $response = [
                'message' => 'Bairro criado',
                'type'   => 'info',
            ];

            session()->flash('return', $response);

            return redirect()->route('neighborhood.index');
        } catch (ValidatorException $e) {

            $response = [
                'message' =>  $e->getMessageBag(),
                'type'    => 'error'
            ];

            session()->flash('return', $response);

            return redirect()->route('neighborhood.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $neighborhood = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $neighborhood,
            ]);
        }

        return view('neighborhoods.show', compact('neighborhood'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $neighborhood = $this->repository->find($id);

        return view('neighborhoods.edit', compact('neighborhood'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  NeighborhoodUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(NeighborhoodUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $neighborhood = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Neighborhood updated.',
                'data'    => $neighborhood->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Neighborhood deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Neighborhood deleted.');
    }
}
