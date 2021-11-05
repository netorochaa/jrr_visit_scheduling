<?php

namespace App\Http\Controllers;

use App\Entities\Util;
use App\Http\Requests\{FreeDayCreateRequest, FreeDayUpdateRequest};
use App\Repositories\{CollectorRepository, FreeDayRepository};
use App\Validators\FreeDayValidator;
use Auth;
use Prettus\Validator\Contracts\ValidatorInterface;

date_default_timezone_set('America/Recife');

class FreeDaysController extends Controller
{
    protected $repository;

    protected $collectorRepository;

    protected $validator;

    public function __construct(FreeDayRepository $repository, FreeDayValidator $validator, CollectorRepository $collectorRepository)
    {
        $this->repository          = $repository;
        $this->validator           = $validator;
        $this->collectorRepository = $collectorRepository;
    }

    public function index()
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        if (Auth::user()->type > 3) {
            $freedays        = $this->repository->all();
            $collectors_list = $this->collectorRepository->pluck('name', 'id');
            $type_list       = $this->repository->type_list();
                
            return view('freedays.index', [
                'namepage'      => 'Dias sem coletas',
                'threeview'     => 'Cadastros',
                'titlespage'    => ['Cadastro de dias sem coletas'],
                'titlecard'     => 'Dias sem coletas',
                'titlemodal'    => 'Cadastrar dias sem coletas',
                'add'           => true,
                //Lists for select
                'collectors_list' => $collectors_list,
                'type_list'       => $type_list,
                //List of entitie
                'table'               => $this->repository->getTable(),
                'thead_for_datatable' => ['Nome', 'Coletador', 'Período'],
                'freedays_list'       => $freedays,
            ]);
        }
            
        return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
    }

    public function store(FreeDayCreateRequest $request)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                if ($request->has('dateRange')) {
                    $fulldate      = explode('-', $request->all()['dateRange']);
                    $fullDateStart = trim($fulldate[0]);
                    $fullDateEnd   = trim($fulldate[1]);
                    $request->merge(['dateStart' => Util::setDateLocalBRToDb($fullDateStart, true)]);
                    $request->merge(['dateEnd' =>  Util::setDateLocalBRToDb($fullDateEnd . ' 23:59:59', true)]);
                }

                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                $collectors = $request->has('collector_id') ? $request->all()['collector_id'] : null;
                $freeDay    = $this->repository->create($request->except(['dateRange']));

                if ($freeDay == null) {
                    return redirect()->route('freedays.index')->withErrors(['Coletadores não iformadas.']);
                }
                    
                if ($collectors != null) {
                    for ($i = 0; $i < count($collectors); $i++) {
                        $freeDay->collectors()->attach($collectors[$i]);
                    }
                }

                $response = [
                    'message' => 'Dias sem coletas cadastrados',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
        } catch (\Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('freedays.index');
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $this->repository->destroy($id);
                $response = [
                    'message' => 'Dia sem coleta deletado',
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

        return redirect()->route('freedays.index');
    }
}
