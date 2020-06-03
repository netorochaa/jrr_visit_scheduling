<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PersonCreateRequest;
use App\Http\Requests\PersonUpdateRequest;
use App\Repositories\PersonRepository;
use App\Repositories\PatientTypeRepository;
use App\Repositories\CollectRepository;
use App\Validators\PersonValidator;
use App\Entities\Util;
use Auth;
use Exception;

date_default_timezone_set('America/Recife');

class PeopleController extends Controller
{
    protected $repository, $patientTypeRepository, $collectRepository;
    protected $validator;

    public function __construct(PersonRepository $repository, PersonValidator $validator, PatientTypeRepository $patientTypeRepository,
                                CollectRepository $collectRepository)
    {
        $this->repository            = $repository;
        $this->validator             = $validator;
        $this->patientTypeRepository = $patientTypeRepository;
        $this->collectRepository     = $collectRepository;
    }

    public function store(PersonCreateRequest $request, $collect_id)
    {
        //Ver a possibilidade de atrelar endereço da coleta com endereço do paciente, para assim os endereço entrar automaticamente na coleta
        // dd($request->all());
        $site = $request->session()->has('collect');
        if(!Auth::check() && !$site)
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                if($site)
                {
                    $collect = $this->collectRepository->find($collect_id);
                    if($collect->status > 2)
                    {
                        $request->session()->flush();
                        $response = [
                            'message' => 'O horário acabou de ser reservado por outra pessoa. A sessão foi encerrada. Por gentileza, realize uma nova solicitação em outro horário ou data.',
                            'type'    => 'info'
                        ];
                        session()->flash('return', $response);
                        return redirect()->route('public.index');
                    }
                }
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                $person = $this->repository->create($request->all());

                if($person != null) $person->collects()->attach($collect_id, $request->only('covenant', 'exams'));

                $response = [
                    'message' => 'Paciente cadastrado',
                    'type'   => 'info',
                ];
            }
            catch (Exception $e)
            {
                $response = [
                    'message' =>  Util::getException($e),
                    'type'    => 'error'
                ];
            }

            session()->flash('return', $response);
            return $site ? redirect()->route('collect.public.schedule', $collect_id) : redirect()->route('collect.schedule', $collect_id);
        }
    }

    public function edit($collect_id, $person_id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $person                 = $this->repository->find($person_id);
            $patientType_list       = $this->patientTypeRepository->patientTypeWithResponsible_list();
            $covenant_list          = $this->repository->covenant_list();
            $typeResponsible_list   = $this->repository->typeResponsible_list();
            $collect                = $this->collectRepository->find($collect_id);
            $peopleCollects         = $person->with('collects')->get();
            $personHasCollect       = $peopleCollects->find($person->id)->collects->find($collect->id)->pivot;

            return view('person.edit', [
                'namepage'              => 'Coletas',
                'threeview'             => null,
                'titlespage'            => ['Cadastro de pessoas'],
                'titlecard'             => 'Editar paciente',
                'goback'                => true,
                'add'                   => false,
                // list
                'patientType_list'      => $patientType_list,
                'covenant_list'         => $covenant_list,
                'typeResponsible_list'  => $typeResponsible_list,
                // model
                'table'                 => $this->repository->getTable(),
                'personHasCollect'      => $personHasCollect,
                'collect'               => $collect,
                'person'                => $person
            ]);
        }
    }

    public function update(PersonUpdateRequest $request, $collect_id, $people_id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try {
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
                $person = $this->repository->update($request->all(), $people_id);
                $collect = $this->collectRepository->find($collect_id);

                $person->collects()->updateExistingPivot($collect, $request->only('covenant', 'exams'));

                $response = [
                    'message' => 'Paciente atualizado',
                    'type'   => 'info',
                ];
            }
            catch (Exception $e)
            {
                $response = [
                    'message' =>  Util::getException($e),
                    'type'    => 'error'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('collect.schedule', $collect_id);
        }
    }

    public function attachPeopleCollect(Request $request)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $registeredPatient = $request->all()['registeredPatient'];
            $collect = $request->all()['idCollect'];
            try
            {
                $person = $this->repository->find($registeredPatient);
                $person->collects()->attach($collect);
                $response = [
                    'message' => 'Paciente adicionado',
                    'type'    => 'info'
                ];
            }
            catch(Exception $e)
            {
                $response = [
                    'message' =>  Util::getException($e),
                    'type'    => 'error'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('collect.schedule', $collect);
        }
    }

    public function detachPeopleCollect(Request $request, $people_id, $collect_id)
    {
        $site = $request->session()->has('collect');
        if(!Auth::check() && !$site)
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                $person = $this->repository->find($people_id);
                $person->collects()->detach($collect_id);
                $response = [
                    'message' => 'Paciente removido',
                    'type'    => 'info'
                ];
            }
            catch(Exception $e)
            {
                $response = [
                    'message' =>  Util::getException($e),
                    'type'    => 'error'
                ];
            }
            session()->flash('return', $response);
            return $site ? redirect()->route('collect.public.schedule', $collect_id) : redirect()->route('collect.schedule', $collect_id);
        }
    }


    //Methods not used
    //public function show($id){}
    //public function index(){}
    //public function destroy($id){}
}
