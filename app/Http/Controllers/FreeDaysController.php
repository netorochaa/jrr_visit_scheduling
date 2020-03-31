<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\FreeDayCreateRequest;
use App\Http\Requests\FreeDayUpdateRequest;
use App\Repositories\FreeDayRepository;
use App\Validators\FreeDayValidator;

/**
 * Class FreeDaysController.
 *
 * @package namespace App\Http\Controllers;
 */
class FreeDaysController extends Controller
{
    /**
     * @var FreeDayRepository
     */
    protected $repository;

    /**
     * @var FreeDayValidator
     */
    protected $validator;

    /**
     * FreeDaysController constructor.
     *
     * @param FreeDayRepository $repository
     * @param FreeDayValidator $validator
     */
    public function __construct(FreeDayRepository $repository, FreeDayValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $freeDays = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $freeDays,
            ]);
        }

        return view('freeDays.index', compact('freeDays'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FreeDayCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(FreeDayCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $freeDay = $this->repository->create($request->all());

            $response = [
                'message' => 'FreeDay created.',
                'data'    => $freeDay->toArray(),
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

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $freeDay = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $freeDay,
            ]);
        }

        return view('freeDays.show', compact('freeDay'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $freeDay = $this->repository->find($id);

        return view('freeDays.edit', compact('freeDay'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FreeDayUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(FreeDayUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $freeDay = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'FreeDay updated.',
                'data'    => $freeDay->toArray(),
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'FreeDay deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'FreeDay deleted.');
    }
}
