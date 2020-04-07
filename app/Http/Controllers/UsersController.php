<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\UserRepository;
use App\Repositories\CollectorRepository;
use App\Validators\UserValidator;


class UsersController extends Controller
{    
    protected $repository, $collectorRepository;
    protected $validator;
    
    public function __construct(UserRepository $repository, UserValidator $validator, CollectorRepository $collectorRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->collectorRepository = $collectorRepository;
    }

    public function index()
    {
        $users_list  = $this->repository->all();
    
        $typeUsers_list = $this->repository->typeUser_list();
        // $collector_list = $this->collectorRepository->findWhereNotIn('id', $users_list->collectors_id->toArray())->pluck('name', 'id');

        return view('user.index', [
            'namepage'      => 'Usuário',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de usuários'],
            'titlecard'     => 'Lista de usuários cadastrados',
            'titlemodal'    => 'Cadastrar usuário',
            
            //Lists for select
            'typeUsers_list' => $typeUsers_list,
            'collector_list' => $collector_list,
            
            //List of entitie
            'table' => $this->repository->getTable(),
            'thead_for_datatable' => ['E-mail', 'Nome', 'Tipo', 'Status', 'Coletador', 'Criado', 'Última atualização'],
            'users_list' => $users_list
        ]);
    }

    public function store(UserCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $user = $this->repository->create($request->all());

            $response = [
                'message' => 'Usuário criado.',
                'data'    => $user->toArray(),
            ];

            if ($request->wantsJson()) 
            {
                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            $response = [
                'message' =>  $e->getMessageBag(),
                'error'    => true
            ];

            if ($request->wantsJson()) 
            {
                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
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
        $user = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $user,
            ]);
        }

        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = $this->repository->find($id);

        return view('users.edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $user = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'User updated.',
                'data'    => $user->toArray(),
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

        $response = [
            'message' => 'Usuário deletado',
            'deleted' => $deleted,
        ];

        if (request()->wantsJson()) {

            return response()->json($response);
        }

        return redirect()->back()->with('message', $response['message']);
    }
}
