<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ActivityCreateRequest;
use App\Http\Requests\ActivityUpdateRequest;
use App\Repositories\ActivityRepository;
use App\Repositories\CollectRepository;
use App\Repositories\CollectorRepository;
use App\Validators\ActivityValidator;
use Auth;

date_default_timezone_set('America/Recife');

class ActivitiesController extends Controller
{
    protected $repository, $collectRepository;
    protected $validator;

    public function __construct(ActivityRepository $repository, ActivityValidator $validator, CollectRepository $collectRepository, CollectorRepository $collectorRepository)
    {
        $this->repository           = $repository;
        $this->validator            = $validator;
        $this->collectRepository    = $collectRepository;
        $this->collectorRepository  = $collectorRepository; 
    }

    public function index()
    {
        if(auth()->check())
        {
            $dateNow = date("Y-m-d");
            $acitivity   = $this->repository->whereDate('dateStart', $dateNow)->where('user_id', Auth::user()->id)->first();
            $collector   = $this->collectorRepository->where('user_id', Auth::user()->id)->first();
            $collect_list   = $this->collectRepository->whereDate('date', $dateNow)->where([
                                                                                            ['collector_id', auth()->user()->id], 
                                                                                            ['status', 4]
                                                                                           ])->get();
            // dd($collector);

            return view('activity.index', [
                'namepage'   => 'Atividade do dia',
                'threeview'  => null,
                'titlespage' => ['Atividade'],
                'titlecard'  => $acitivity ? $activity->dateStart . " - " .  $activity->id : null,
                'collector'  => $collector,
                //List for select
                'collect_list'  => $collect_list,
                //Info of entitie
                'table'               => $this->repository->getTable(),
                // 'thead_for_datatable' => ['Data/Hora', 'Código', 'Status', 'Pagamento Taxa', 'Bairro', 'Endereço', 'Coletador'],
                'acitivity'     => $acitivity
            ]);
        }
        else return view('auth.login');
    }

    public function store(ActivityCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $activity = $this->repository->create($request->all());

            $response = [
                'message' => 'Activity created.',
                'data'    => $activity->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    public function show($id)
    {
        $activity = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $activity,
            ]);
        }

        return view('activities.show', compact('activity'));
    }

    public function edit($id)
    {
        $activity = $this->repository->find($id);

        return view('activities.edit', compact('activity'));
    }

    public function update(ActivityUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $activity = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Activity updated.',
                'data'    => $activity->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Activity deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Activity deleted.');
    }
}
