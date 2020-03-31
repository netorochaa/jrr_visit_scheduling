<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CancellationTypeCreateRequest;
use App\Http\Requests\CancellationTypeUpdateRequest;
use App\Repositories\CancellationTypeRepository;
use App\Validators\CancellationTypeValidator;

/**
 * Class CancellationTypesController.
 *
 * @package namespace App\Http\Controllers;
 */
class CancellationTypesController extends Controller
{
    /**
     * @var CancellationTypeRepository
     */
    protected $repository;

    /**
     * @var CancellationTypeValidator
     */
    protected $validator;

    /**
     * CancellationTypesController constructor.
     *
     * @param CancellationTypeRepository $repository
     * @param CancellationTypeValidator $validator
     */
    public function __construct(CancellationTypeRepository $repository, CancellationTypeValidator $validator)
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
        $cancellationTypes = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $cancellationTypes,
            ]);
        }

        return view('cancellationTypes.index', compact('cancellationTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CancellationTypeCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CancellationTypeCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $cancellationType = $this->repository->create($request->all());

            $response = [
                'message' => 'CancellationType created.',
                'data'    => $cancellationType->toArray(),
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
        $cancellationType = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $cancellationType,
            ]);
        }

        return view('cancellationTypes.show', compact('cancellationType'));
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
        $cancellationType = $this->repository->find($id);

        return view('cancellationTypes.edit', compact('cancellationType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CancellationTypeUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(CancellationTypeUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $cancellationType = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'CancellationType updated.',
                'data'    => $cancellationType->toArray(),
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
                'message' => 'CancellationType deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'CancellationType deleted.');
    }
}
