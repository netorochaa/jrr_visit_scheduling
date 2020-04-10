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

class CancellationTypesController extends Controller
{
    protected $repository;
    protected $validator;

    public function __construct(CancellationTypeRepository $repository, CancellationTypeValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    public function index()
    {
        $cancellationTypes  = $this->repository->all();

        return view('cancellationType.index', [
            'namepage'      => 'Cancelamento de coleta',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de cancelamento de coleta'],
            'titlecard'     => 'Lista dos cancelamentos cadastrados',
            'titlemodal'    => 'Cadastrar cancelamento de coleta',
            'add'           => true,

            //List of entitie
            'table' => $this->repository->getTable(),
            'thead_for_datatable' => ['Nome', 'Status', 'Criado', 'Última atualização'],
            'cancellationTypes' => $cancellationTypes
        ]);
    }

    public function store(CancellationTypeCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $cancellationType = $this->repository->create($request->all());

            $response = [
                'message' => 'Cancelamento cadastrado',
                'type'   => 'info',
            ];

            session()->flash('return', $response);

            return redirect()->route('cancellationType.index');
        } catch (ValidatorException $e) {
            $response = [
                'message' =>  $e->getMessageBag(),
                'type'    => 'error'
            ];

            session()->flash('return', $response);

            return redirect()->route('cancellationType.index');
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

    public function edit($id)
    {
       
    }

    public function update(CancellationTypeUpdateRequest $request, $id)
    {
        
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
