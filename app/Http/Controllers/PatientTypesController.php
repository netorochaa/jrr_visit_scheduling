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
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            if(Auth::user()->type > 2)
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
            else
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
        }
    }

    public function store(PatientTypeCreateRequest $request)
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
                if(Auth::user()->type > 2)
                {
                    $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                    $this->repository->create($request->all());

                    $response = [
                        'message' => 'Tipo de paciente cadastrado',
                        'type'   => 'info',
                    ];
                }
                else
                    return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
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
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                if(Auth::user()->type > 2)
                {
                    $this->repository->update(['active' => 'off'], $id);
                    $response = [
                        'message' => 'Tipo de paciente deletado',
                        'type'   => 'info',
                    ];
                }
                else
                    return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
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
