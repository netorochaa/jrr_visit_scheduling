<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CollectorCreateRequest;
use App\Http\Requests\CollectorUpdateRequest;
use App\Repositories\CollectorRepository;
use App\Validators\CollectorValidator;

/**
 * Class CollectorsController.
 *
 * @package namespace App\Http\Controllers;
 */
class CollectorsController extends Controller
{

    protected $repository;
    protected $validator;

    public function __construct(CollectorRepository $repository, CollectorValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    public function index()
    {
        $collectors_list  = $this->repository->all();

        return view('collector.index', [
            'namepage'      => 'Coletador',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de coletadores'],
            'titlecard'     => 'Lista de coletadores cadastrados',
            'titlemodal'    => 'Cadastrar coletador',
            
            //List of entitie
            'table' => $this->repository->getTable(),
            'thead_for_datatable' => ['Nome', 'Hora inicial', 'Hora final', 'Intervalo entre coletas', 'Endereço inicial', 'Criado', 'Última atualização'],
            'collectors_list' => $collectors_list
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CollectorCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CollectorCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $collector = $this->repository->create($request->all());

            $response = [
                'message' => 'Coletador Criado.',
                'data'    => $collector->toArray(),
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
        $collector = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $collector,
            ]);
        }

        return view('collectors.show', compact('collector'));
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
        $collector = $this->repository->find($id);

        return view('collectors.edit', compact('collector'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CollectorUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(CollectorUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $collector = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Collector updated.',
                'data'    => $collector->toArray(),
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
                'message' => 'Collector deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Collector deleted.');
    }
}
