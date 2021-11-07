<?php

namespace App\Http\Controllers;

use App\Entities\Util;

use App\Http\Requests\{PersonCreateRequest, PersonUpdateRequest};
use App\Repositories\{CollectRepository, PatientTypeRepository, PersonRepository};
use App\Validators\PersonValidator;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Prettus\Validator\Contracts\ValidatorInterface;

date_default_timezone_set('America/Recife');

class PeopleController extends Controller
{
    protected $repository;

    protected $patientTypeRepository;

    protected $validator;

    public function __construct(
        PersonRepository $repository,
        PersonValidator $validator,
        PatientTypeRepository $patientTypeRepository,
        CollectRepository $collectRepository
    ) {
        $this->repository            = $repository;
        $this->validator             = $validator;
        $this->patientTypeRepository = $patientTypeRepository;
        $this->collectRepository     = $collectRepository;
    }
    
    // API FIND PEOPLE
    public function find(Request $request)
    {
        try {
            $param = $request->get('typeSearch');
            $value = '%' . $request->get('value') . '%';

            $people_list = DB::table('people')
                ->select('id', 'name', 'cpf', 'rg', 'birth', 'fone', 'email')
                ->where($param, 'like', $value)
                ->orderBy('created_at', 'DESC');

            return $people_list->get()->toJson();
        } catch (Exception $e) {
            Log::channel('mysql')->info('Erro api findperson: ' . Util::getException($e));
        }
    }

    public function store(PersonCreateRequest $request, $collect_id)
    {
        $site = $request->session()->has('collect');
        
        if (!Auth::check() && !$site) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $person = $this->repository->create($request->all());

            if ($person != null) {
                $person->collects()->attach($collect_id, $request->only('covenant', 'enrollment', 'exams'));
            }

            $response = [
                'message' => 'Paciente cadastrado',
                'type'    => 'info',
            ];
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }

        session()->flash('return', $response);

        return $site ? redirect()->route('collect.public.schedule', $collect_id) : redirect()->route('collect.schedule', $collect_id);
    }

    public function edit($collect_id, $person_id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            $person               = $this->repository->find($person_id);
            $patientType_list     = $this->patientTypeRepository->patientTypeWithResponsible_list();
            $covenant_list        = $this->repository->covenant_list();
            $typeResponsible_list = $this->repository->typeResponsible_list();
            $collect              = $this->collectRepository->find($collect_id);
            //$peopleCollects         = $person->with('collects')->get();
            //$personHasCollect       = $peopleCollects->find($person->id)->collects->find($collect->id)->pivot;
            $personHasCollect = $this->repository->person_collect($collect_id, $person_id)->first();
                
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
                'person'                => $person,
            ]);
        } catch (Exception $e) {
            Log::channel('mysql')->info('Erro edit person: ' . Util::getException($e));
        }
    }

    public function update(PersonUpdateRequest $request, $collect_id, $people_id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $person  = $this->repository->update($request->all(), $people_id);
            $collect = $this->collectRepository->find($collect_id);

            $person->collects()->updateExistingPivot($collect, $request->only('covenant', 'enrollment', 'exams'));

            $response = [
                'message' => 'Paciente atualizado',
                'type'    => 'info',
            ];
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('collect.schedule', $collect_id);
    }

    public function attachPeopleCollect(Request $request)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        $collect = $this->collectRepository->find($request->all()['idCollect']);

        try {
            $person = $this->repository->find($request->all()['registeredPatient']);
            if (count($collect->people->where('CPF', $person->CPF)) == 0) {
                if (count($collect->people) == 0) {
                    $last_collect = $person->collects->where('status', '<>', 7)->sortByDesc('date')->first();
                    if ($last_collect) {
                        $collect = $this->collectRepository->update([
                            'cep'               => $last_collect->cep,
                            'address'           => $last_collect->address,
                            'numberAddress'     => $last_collect->numberAddress,
                            'complementAddress' => $last_collect->complementAddress,
                            'referenceAddress'  => $last_collect->referenceAddress,
                        ], $collect->id);
                    }
                }
                $person->collects()->attach($collect);
                $text = 'Paciente adicionado';
            } else {
                $text = 'Paciente já adicionado neste agendamento';
            }

            $response = [
                'message' => $text,
                'type'    => 'info',
            ];
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('collect.schedule', $collect->id);
    }

    public function detachPeopleCollect(Request $request, $people_id, $collect_id)
    {
        $site = $request->session()->has('collect');
        if (!Auth::check() && !$site) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            $person = $this->repository->find($people_id);
            $person->collects()->detach($collect_id);
            $response = [
                'message' => 'Paciente removido',
                'type'    => 'info',
            ];
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return $site ? redirect()->route('collect.public.schedule', $collect_id) : redirect()->route('collect.schedule', $collect_id);
    }
}
