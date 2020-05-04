<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CityCreateRequest;
use App\Http\Requests\CityUpdateRequest;
use App\Repositories\CityRepository;
use App\Validators\CityValidator;

class CitiesController extends Controller
{
    protected $repository;
    protected $validator;

    public function __construct(CityRepository $repository, CityValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    public function store(CityCreateRequest $request)
    {
        try 
        {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $city = $this->repository->create($request->all());

            $response = [
                'message' => 'Cidade cadastrada',
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
        return redirect()->route('neighborhood.index');
    }

    public function edit(Request $request, $id)
    {
        $city = $this->repository->find($id);

        return view('city.edit', [
            'namepage'       => 'Bairro',
            'threeview'      => 'Cadastros',
            'titlespage'     => ['Cadastro de Cidade'],
            'titlecard'      => 'Editar cidade',
            'logged'         => $request->session()->get('logged'),
            'table'          => $this->repository->getTable(),
            'goback'         => true,
            'add'            => false,
            'city'           => $city
        ]);
    }

    public function update(CityUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $city = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Cidade atualizada',
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
        return redirect()->route('neighborhood.index');
    }

    //Method not used
    public function destroy($id){}
    public function index(){}
    public function show($id){}


}
