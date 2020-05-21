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

    public function index()
    {
        if(Auth::check())
        {
            if(Auth::user()->type == 2)
            {
                $dateNow    = date("Y-m-d");
                $cancellationType_list = $this->cancellationTypeRepository->pluck('name', 'id');
                $collector  = $this->collectorRepository->where('user_id', Auth::user()->id)->first();
                $activity   = $this->repository->whereDate('start', $dateNow)
                                                ->where([['user_id', Auth::user()->id],
                                                    ['collector_id', $collector->id]])
                                                ->first();
                $collect_list   = $this->collectRepository->whereDate('date', $dateNow)
                                                            ->where([['collector_id', $collector->id],
                                                                    ['status', '>', 3]])
                                                            ->orderBy('date')->get();
                                                            // dd($activity);
                // IF ALL COLLECTS DONE
                if($activity)
                {
                    if($activity->end == null){
                        if(count($collect_list->whereIn('status', [4, 5])) == 0)
                            $this->repository->update(['status' => 2, 'end' => Util::dateNowForDB()], $activity->id);
                    }
                }
                return view('activity.index', [
                    'namepage'      => 'Rota do dia',
                    'threeview'     => null,
                    'numberModal'   => 2,
                    'titlespage'    => [$activity != null ? "Rota " .  $activity->id . " | " . $collector->name . " (" . Auth::user()->name . ")" : null],
                    'titlemodal'    => 'Cancelar rota',
                    'titlemodal2'   => 'Cancelar coleta',
                    'titlecard'     => $activity != null ? "INÍCIO: " . Util::setDate($activity->start, true) : null,
                    'collector'     => $collector,
                    'date'          => Util::setDate($dateNow, false),
                    'end'           => $activity != null && $activity->end ?  Util::setDate($activity->end, true) : null,
                    //List for select
                    'collect_list'          => $collect_list,
                    'cancellationType_list' => $cancellationType_list,
                    //Info of entitie
                    'table'         => $this->repository->getTable(),
                    'activity'      => $activity
                ]);
            }else return redirect()->route('auth.home');
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
                $activity = $this->repository->create($data);
                $ok = Collect::whereDate('date', $dateNowShort)->where([['collector_id', $request->get('collector_id')],
                                                                        ['status', '>', 3]])
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
