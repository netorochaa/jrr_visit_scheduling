<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entities\Util;
use App\Entities\Collect;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ActivityCreateRequest;
use App\Http\Requests\ActivityUpdateRequest;
use App\Repositories\ActivityRepository;
use App\Repositories\CollectRepository;
use App\Repositories\CollectorRepository;
use App\Repositories\CancellationTypeRepository;
use App\Validators\ActivityValidator;
use Auth;
use Exception;

date_default_timezone_set('America/Recife');

class ActivitiesController extends Controller
{
    protected $repository, $collectRepository;
    protected $validator;

    public function __construct(ActivityRepository $repository, ActivityValidator $validator, CollectRepository $collectRepository, CollectorRepository $collectorRepository,
                                CancellationTypeRepository $cancellationTypeRepository)
    {
        $this->repository           = $repository;
        $this->validator            = $validator;
        $this->collectRepository    = $collectRepository;
        $this->collectorRepository  = $collectorRepository;
        $this->cancellationTypeRepository = $cancellationTypeRepository;
    }

    public function index(Request $request)
    {
        if(Auth::check())
        {
            if(Auth::user()->type > 1)
            {
                $dateNow    = date("Y-m-d");
                $cancellationType_list = $this->cancellationTypeRepository->pluck('name', 'id');
                if(Auth::user()->type > 2)
                {
                    $collector_list = $this->collectorRepository->where('active', 'on');
                    $all_collectors = $collector_list->get();
                    $collector = $request->has('collector') ? $collector_list->where('id', $request->get('collector'))->first() : $collector_list->first();
                    if($request->has('dateConsult')) $dateNow = trim(Util::setDateLocalBRToDb($request->get('dateConsult'), false));
                }
                else
                    $collector  = $this->collectorRepository->where('user_id', Auth::user()->id)->first();

                $activity   = $this->repository->whereDate('start', $dateNow == false ? date("Y-m-d") : $dateNow)
                                                ->where('collector_id', $collector->id)
                                                ->first();
                
                $collect_list   = $this->collectRepository->whereDate('date', $dateNow == false ? date("Y-m-d") : $dateNow)
                                                            ->where([['collector_id', $collector->id],
                                                                    ['status', '>', 3],
                                                                    ['status', '!=', 7],
                                                                    ['status', '!=', 9]])
                                                            ->orderBy('date')->get();
                // IF ALL COLLECTS DONE
                if($activity && Auth::user()->type == 2)
                {
                    if($activity->end == null){
                        if(count($collect_list->whereIn('status', [4, 5])) == 0)
                            $this->repository->update(['status' => 2, 'end' => Util::dateNowForDB()], $activity->id);
                    }
                }

                return view('activity.index', [
                    'namepage'      => 'Rota do dia',
                    'threeview'     => null,
                    'titlespage'    => [$activity != null ? "Rota " .  $activity->id . " | " . $collector->name . " (" . $collector->user->name . ")" : null],
                    'titlemodal'    => 'Cancelar rota',
                    'titlecard'     => $activity != null ? "INÍCIO: " . Util::setDate($activity->start, true) : null,
                    'collector'     => $collector,
                    'date'          => Util::setDate($dateNow, false),
                    'end'           => $activity != null && $activity->end ?  Util::setDate($activity->end, true) : null,
                    //List for select
                    'collect_list'          => $collect_list,
                    'cancellationType_list' => $cancellationType_list,
                    'collector_list'        => $all_collectors ?? null,
                    //Info of entitie
                    'table'         => $this->repository->getTable(),
                    'activity'      => $activity
                ]);
            }
            else return redirect()->route('auth.home');
        }
        else return view('auth.login');
    }

    public function store(ActivityCreateRequest $request)
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
                $dateNow = date("Y-m-d H:i");
                $dateNowShort = date("Y-m-d");
                $data = [
                    'status'    => '1', // progress
                    'start' => $dateNow,
                    'collector_id' => $request->get('collector_id'),
                    'user_id' => $request->get('user_id')
                ];
                $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);
                $this->repository->create($data);
                Collect::whereDate('date', $dateNowShort)->where([['collector_id', $request->get('collector_id')],
                                                                 ['status', 4]])
                                                         ->update(['status' => '5']);
                $response = [
                    'message' => 'Rota iniciada!',
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
            return redirect()->route('activity.index');
        }
    }

    public function close(Request $request, $id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $dateNow = date("Y-m-d H:i");
            $activity = $this->repository->find($id);
            try
            {
                $this->repository->update(['status' => '3', 'end' => $dateNow, 'reasonCancellation' => $request->get('reasonCancellation')], $activity->id);
                $response = [
                    'message' => 'Rota ' . $activity->id . ' encerrada',
                    'type'    => 'info'
                ];
            }
            catch (Exception $e)
            {
                $response = [
                    'message' => Util::getException($e),
                    'type'    => 'erro'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('auth.home');
        }
    }

    //Methods not used
    public function show($id){}
    public function destroy($id){}
    public function update(ActivityUpdateRequest $request, $id){}
    public function edit($id){}
}
