<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PatientTypeCreateRequest;
use App\Http\Requests\PatientTypeUpdateRequest;
use App\Repositories\PatientTypeRepository;
use App\Validators\PatientTypeValidator;

class PatientTypesController extends Controller
{
   
    protected $repository;
    protected $validator;

    public function __construct(PatientTypeRepository $repository, PatientTypeValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    public function index()
    {
        $patientTypes  = $this->repository->all();

        return view('patientType.index', [
            'namepage'      => 'Tipo de paciente',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de tipo de paciente'],
            'titlecard'     => 'Lista dos tipos cadastrados',
            'titlemodal'    => 'Cadastrar tipo de paciente',
            'add'           => true,

            //List of entitie
            'table' => $this->repository->getTable(),
            'thead_for_datatable' => ['Nome', 'Responsável', 'Status', 'Criado', 'Última atualização'],
            'patientTypes' => $patientTypes
        ]);
    }

    public function store(PatientTypeCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $patientType = $this->repository->create($request->all());

            $response = [
                'message' => 'Tipo de paciente cadastrado',
                'type'   => 'info',
            ];

            session()->flash('return', $response);

            return redirect()->route('patientType.index');
        } catch (ValidatorException $e) {
            $response = [
                'message' =>  $e->getMessageBag(),
                'type'    => 'error'
            ];

            session()->flash('return', $response);

            return redirect()->route('patientType.index');
        }
    }

    public function show($id)
    {
        $patientType = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $patientType,
            ]);
        }

        return view('patientTypes.show', compact('patientType'));
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
        $patientType = $this->repository->find($id);

        return view('patientTypes.edit', compact('patientType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PatientTypeUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(PatientTypeUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $patientType = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'PatientType updated.',
                'data'    => $patientType->toArray(),
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
                'message' => 'PatientType deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'PatientType deleted.');
    }
}
