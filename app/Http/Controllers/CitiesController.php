<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CityCreateRequest;
use App\Http\Requests\CityUpdateRequest;
use App\Repositories\CityRepository;
use App\Validators\CityValidator;

class CitiesController extends Controller
{
    protected $repository;
    protected $validator;

    public function __construct(CityRepository $repository, CityValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    public function index()
    {
        // $cities_list  = $this->repository->all();

        // return view('city.index', [
        //     'namepage'      => 'Cidade',
        //     'threeview'     => 'Cadastros',
        //     'titlespage'    => ['Cadastro de cidades'],
        //     'titlecard'     => 'Lista de cidades cadastrados',
        //     'titlemodal'    => 'Cadastrar cidade',

        //     //List of entitie
        //     'table' => $this->repository->getTable(),
        //     'thead_for_datatable' => ['Nome da cidade', 'UF', 'Status', 'Bairros', 'Criado', 'Última atualização'],
        //     'cities_list' => $cities_list
        // ]);
    }

    public function store(CityCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $city = $this->repository->create($request->all());

            $response = [
                'message' => 'Cidade criada',
                'data'    => $city->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            $response = [
                'message' =>  $e->getMessageBag(),
                'error'    => true
            ];

            if ($request->wantsJson()) 
            {
                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
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
        $city = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $city,
            ]);
        }

        return view('cities.show', compact('city'));
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
        $city = $this->repository->find($id);

        return view('cities.edit', compact('city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CityUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(CityUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $city = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'City updated.',
                'data'    => $city->toArray(),
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

        $response = [
            'message' => 'Cidade deletada',
            'deleted' => $deleted,
        ];

        if (request()->wantsJson()) {

            return response()->json($response);
        }

        return redirect()->back()->with('message', $response['message']);
    }
}
