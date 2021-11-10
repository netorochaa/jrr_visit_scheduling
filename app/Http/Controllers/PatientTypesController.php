<?php

namespace App\Http\Controllers;

use App\Entities\Util;
use App\Http\Requests\{PatientTypeCreateRequest, PatientTypeUpdateRequest};
use App\Repositories\PatientTypeRepository;
use App\Validators\PatientTypeValidator;
use Auth;
use Exception;
use Prettus\Validator\Contracts\ValidatorInterface;

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
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        if (Auth::user()->type > 3) {
            $patientTypes = $this->repository->where('active', 'on')->get();

            return view('patienttype.index', [
                'namepage'      => 'Tipo de paciente',
                'threeview'     => 'Cadastros',
                'titlespage'    => ['Cadastro de tipo de paciente'],
                'titlecard'     => 'Lista dos tipos de pacientes',
                'titlemodal'    => 'Cadastrar tipo de paciente',
                'add'           => true,
                //List of entitie
                'table'               => $this->repository->getTable(),
                'thead_for_datatable' => ['Nome', 'Responsável', 'Criado'],
                'patientTypes'        => $patientTypes,
            ]);
        }
            
        return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
    }

    public function store(PatientTypeCreateRequest $request)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                $this->repository->create($request->all());

                $response = [
                    'message' => 'Tipo de paciente cadastrado',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('patienttype.index');
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $this->repository->update(['active' => 'off'], $id);
                $response = [
                    'message' => 'Tipo de paciente deletado',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }

        session()->flash('return', $response);

        return redirect()->route('patienttype.index');
    }
}
