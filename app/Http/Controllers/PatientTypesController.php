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

    public function index(Request $request)
    {
        $patientTypes  = $this->repository->all();

        return view('patienttype.index', [
            'namepage'      => 'Tipo de paciente',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de tipo de paciente'],
            'titlecard'     => 'Lista dos tipos de pacientes',
            'titlemodal'    => 'Cadastrar tipo de paciente',
            'add'           => true,
            'logged'        => $request->session()->get('logged'),
            //List of entitie
            'table' => $this->repository->getTable(),
            'thead_for_datatable' => ['Nome', 'Responsável', 'Status', 'Criado', 'Última atualização'],
            'patientTypes' => $patientTypes
        ]);
    }

    public function store(PatientTypeCreateRequest $request)
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

    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        $response = [
            'message' => 'Tipo de paciente deletado',
            'type'   => 'info',
        ];
        session()->flash('return', $response);
        return redirect()->route('patienttype.index');
    }

    //Method not used
    public function show($id){}
    public function edit($id){}
    public function update(PatientTypeUpdateRequest $request, $id){}
}
