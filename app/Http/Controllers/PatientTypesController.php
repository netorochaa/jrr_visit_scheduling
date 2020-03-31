<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PatientTypeCreateRequest;
use App\Http\Requests\PatientTypeUpdateRequest;
use App\Repositories\PatientTypeRepository;
use App\Validators\PatientTypeValidator;

/**
 * Class PatientTypesController.
 *
 * @package namespace App\Http\Controllers;
 */
class PatientTypesController extends Controller
{
    /**
     * @var PatientTypeRepository
     */
    protected $repository;

    /**
     * @var PatientTypeValidator
     */
    protected $validator;

    /**
     * PatientTypesController constructor.
     *
     * @param PatientTypeRepository $repository
     * @param PatientTypeValidator $validator
     */
    public function __construct(PatientTypeRepository $repository, PatientTypeValidator $validator)
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
        $patientTypes = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $patientTypes,
            ]);
        }

        return view('patientTypes.index', compact('patientTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PatientTypeCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(PatientTypeCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $patientType = $this->repository->create($request->all());

            $response = [
                'message' => 'PatientType created.',
                'data'    => $patientType->toArray(),
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
        $patientType = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $patientType,
            ]);
        }

        return view('patientTypes.show', compact('patientType'));
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
        $patientType = $this->repository->find($id);

        return view('patientTypes.edit', compact('patientType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PatientTypeUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(PatientTypeUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $patientType = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'PatientType updated.',
                'data'    => $patientType->toArray(),
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
                'message' => 'PatientType deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'PatientType deleted.');
    }
}
