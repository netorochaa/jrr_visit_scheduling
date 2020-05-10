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
use App\Entities\Util;

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

        return view('cancellationtype.index', [
            'namepage'      => 'Cancelamento de coleta',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de cancelamento de coleta'],
            'titlecard'     => 'Lista dos cancelamentos de coleta',
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
        try
        {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $cancellationType = $this->repository->create($request->all());

            $response = [
                'message' => 'Cancelamento cadastrado',
                'type'   => 'info',
            ];
        }
        catch (ValidatorException $e)
        {
            $response = [
                'message' =>  $e->getMessageBag(),
                'type'    => 'error'
            ];
        }
        session()->flash('return', $response);
        return redirect()->route('cancellationtype.index');
    }

    public function destroy($id)
    {
        try 
        {
            $deleted = $this->repository->update(['active' => 'off', 'deleted_at' => Util::dateNowForDB()], $id);

            $response = [
                'message' => 'Cancelamento deletado',
                'type'   => 'info',
            ];
        } 
        catch (Exception $e) 
        {
            $response = [
                'message' => $e->getMessage(),
                'type'   => 'error',
            ];
        }
        
        session()->flash('return', $response);
        return redirect()->route('cancellationtype.index');
    }

    //Method not used
    public function show($id){}
    public function edit($id){}
    public function update(CancellationTypeUpdateRequest $request, $id){}
}
