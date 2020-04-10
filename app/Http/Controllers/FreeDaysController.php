<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\FreeDayCreateRequest;
use App\Http\Requests\FreeDayUpdateRequest;
use App\Repositories\FreeDayRepository;
use App\Repositories\CollectorRepository;
use App\Repositories\CityRepository;
use App\Validators\FreeDayValidator;

class FreeDaysController extends Controller
{
  
    protected $repository, $collectorRepository, $cityRepository;
    protected $validator;

    public function __construct(FreeDayRepository $repository, FreeDayValidator $validator, CollectorRepository $collectorRepository, CityRepository $cityRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->collectorRepository = $collectorRepository;
        $this->cityRepository = $cityRepository;
    }

    public function index()
    {
        $freedays       = $this->repository->all();
        $collectors     = $this->collectorRepository->all();
        $collectors_list    = $this->collectorRepository->pluck('name', 'id');
        $cities_list        = $this->cityRepository->pluck('name', 'id');
        $type_list          = $this->repository->type_list();

        return view('freedays.index', [
            'namepage'      => 'Dias sem coletas',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de dias sem coletas'],
            'titlecard'     => 'Dias sem coletas cadastrados',
            'titlemodal'    => 'Cadastrar dia',
            'add'           => true,

            //Lists for select
            'collectors_list' => $collectors_list,
            'cities_list' => $cities_list,
            'type_list' => $type_list,

            //List of entitie
            'table' => $this->repository->getTable(),
            'thead_for_datatable' => ['Nome', 'Tipo', 'Período', 'Ativo'],
            'freedays_list' => $freedays,
        ]);
    }

    public function store(FreeDayCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $freeDay = $this->repository->create($request->except(['city_id', 'collector_id']));

            $collectors = $request->has('collector_id') ? $request->all()['collector_id'] : null;
            $cities = $request->has('city_id') ? $request->all()['city_id'] : null;
            
            if($collectors != null){
                for ($i=0; $i < count($collectors); $i++) {
                    $freeDay->collectors()->attach($collectors[$i]);
                }
            }else if($cities != null){
                for ($i=0; $i < count($cities); $i++) {
                    $freeDay->cities()->attach($cities[$i]);
                }
            }

            $response = [
                'message' => 'Dias sem coletas cadastrados',
                'type'   => 'info',
            ];

            session()->flash('return', $response);

            return redirect()->route('freedays.index');
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
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $freeDay = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $freeDay,
            ]);
        }

        return view('freeDays.show', compact('freeDay'));
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
        $freeDay = $this->repository->find($id);

        return view('freeDays.edit', compact('freeDay'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FreeDayUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(FreeDayUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $freeDay = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'FreeDay updated.',
                'data'    => $freeDay->toArray(),
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
                'message' => 'FreeDay deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'FreeDay deleted.');
    }
}
