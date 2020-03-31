<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\NeighborhoodCreateRequest;
use App\Http\Requests\NeighborhoodUpdateRequest;
use App\Repositories\NeighborhoodRepository;
use App\Validators\NeighborhoodValidator;

/**
 * Class NeighborhoodsController.
 *
 * @package namespace App\Http\Controllers;
 */
class NeighborhoodsController extends Controller
{
    /**
     * @var NeighborhoodRepository
     */
    protected $repository;

    /**
     * @var NeighborhoodValidator
     */
    protected $validator;

    /**
     * NeighborhoodsController constructor.
     *
     * @param NeighborhoodRepository $repository
     * @param NeighborhoodValidator $validator
     */
    public function __construct(NeighborhoodRepository $repository, NeighborhoodValidator $validator)
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
        $neighborhoods = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $neighborhoods,
            ]);
        }

        return view('neighborhoods.index', compact('neighborhoods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  NeighborhoodCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(NeighborhoodCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $neighborhood = $this->repository->create($request->all());

            $response = [
                'message' => 'Neighborhood created.',
                'data'    => $neighborhood->toArray(),
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
        $neighborhood = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $neighborhood,
            ]);
        }

        return view('neighborhoods.show', compact('neighborhood'));
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
        $neighborhood = $this->repository->find($id);

        return view('neighborhoods.edit', compact('neighborhood'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  NeighborhoodUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(NeighborhoodUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $neighborhood = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Neighborhood updated.',
                'data'    => $neighborhood->toArray(),
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
                'message' => 'Neighborhood deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Neighborhood deleted.');
    }
}
