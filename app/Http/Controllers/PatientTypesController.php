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

date_default_timezone_set('America/Recife');

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
        if(!\Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $patientTypes  = $this->repository->where('active', 'on')->get();
            return view('patienttype.index', [
                'namepage'      => 'Tipo de paciente',
                'threeview'     => 'Cadastros',
                'titlespage'    => ['Cadastro de tipo de paciente'],
                'titlecard'     => 'Lista dos tipos de pacientes',
                'titlemodal'    => 'Cadastrar tipo de paciente',
                'add'           => true,
                //List of entitie
                'table' => $this->repository->getTable(),
                'thead_for_datatable' => ['Nome', 'Responsável', 'Criado'],
                'patientTypes' => $patientTypes
            ]);
        }
    }

    public function store(PatientTypeCreateRequest $request)
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
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                $patientType = $this->repository->create($request->all());

                $response = [
                    'message' => 'Tipo de paciente cadastrado',
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
            return redirect()->route('patienttype.index');
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
                $response = [
                    'message' => 'Tipo de paciente deletado',
                    'type'   => 'info',
                ];
            } 
            catch (Exception $e) 
            {
                $response = [
                    'message' => $e->getMessage(),
                    'type'   => 'error',
                ];
            }
            
            session()->flash('return', $response);
            return redirect()->route('patienttype.index');
        }
    }

    //Method not used
    public function show($id){}
    public function edit($id){}
    public function update(PatientTypeUpdateRequest $request, $id){}
}
