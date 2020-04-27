<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PersonCreateRequest;
use App\Http\Requests\PersonUpdateRequest;
use App\Repositories\PersonRepository;
use App\Repositories\PatientTypeRepository;
use App\Validators\PersonValidator;

class PeopleController extends Controller
{
    protected $repository, $patientTypeRepository;
    protected $validator;

    public function __construct(PersonRepository $repository, PersonValidator $validator, PatientTypeRepository $patientTypeRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->patientTypeRepository = $patientTypeRepository;
    }

    public function store(PersonCreateRequest $request)
    {
        //Ver a possibilidade de atrelar endereço da coleta com endereço do paciente, para assim os endereço entrar automaticamente na coleta
        // dd($request->all());
        $idCollect = $request->all()['idCollect'];
        try
        {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $person = $this->repository->create($request->except('idCollect'));

            if($person != null) $person->collects()->attach($idCollect);

            $response = [
                'message' => 'Paciente cadastrado',
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
        return redirect()->route('collect.schedule', $idCollect);
    }

    public function edit($id)
    {
        $person = $this->repository->find($id);
        $patientType_list = $this->patientTypeRepository; //pluck in page register

        return view('collect.person.edit', [
            'namepage'   => 'Coletas',
            'threeview'  => null,
            'titlespage' => ['Cadastro de pessoas'],
            'titlecard'  => 'Editar paciente',
            'patientType_list' => $patientType_list,
            'table'      => $this->repository->getTable(),
            'goback'     => true,
            'add'        => false,
            'person'     => $person
        ]);
    }

    public function update(PersonUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $person = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Paciente atualizado',
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
        return redirect()->route('collect.index');
    }

    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Person deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Person deleted.');
    }
    
    public function attachPeopleCollect(Request $request)
    {
        $registeredPatient = $request->all()['registeredPatient'];
        $collect = $request->all()['idCollect'];

        try
        {
            $person = $this->repository->find($registeredPatient);

            $person->collects()->attach($collect);

            $response = [
                'message' => 'Paciente adicionado',
                'type'    => 'error'
            ];
        }
        catch(Exception $e)
        {
            $response = [
                'message' =>  $e->getMessage(),
                'type'    => 'error'
            ];
        }

        session()->flash('return', $response);
        return redirect()->route('collect.schedule', $collect);
    }

    public function detachPeopleCollect($people_id, $collect_id)
    {
        try
        {
            $person = $this->repository->find($people_id);

            $person->collects()->detach($collect_id);

            $response = [
                'message' => 'Paciente adicionado',
                'type'    => 'error'
            ];
        }
        catch(Exception $e)
        {
            $response = [
                'message' =>  $e->getMessage(),
                'type'    => 'error'
            ];
        }

        session()->flash('return', $response);
        return redirect()->route('collect.schedule', $collect_id);
    }


    //Methods not used
    public function show($id){}
    public function index(){}
}
