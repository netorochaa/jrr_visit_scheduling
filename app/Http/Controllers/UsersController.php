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

        // dd($users_list);
        $typeUsers_list = $this->repository->typeUser_list();

        return view('user.index', [
            'namepage'      => 'Usuário',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de usuários'],
            'titlecard'     => 'Lista de usuários cadastrados',
            'titlemodal'    => 'Cadastrar usuário',
            'add'           => true,

            //Lists for select
            'typeUsers_list' => $typeUsers_list,

            //List of entitie
            'table' => $this->repository->getTable(),
            'thead_for_datatable' => ['E-mail', 'Nome', 'Tipo', 'Status', 'Criado', 'Última atualização'],
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
                'type'   => 'info',
            ];

            session()->flash('return', $response);

            return redirect()->route('user.index');
        } catch (ValidatorException $e) {

            $response = [
                'message' =>  $e->getMessageBag(),
                'type'    => 'error'
            ];

            session()->flash('return', $response);

            return redirect()->route('user.index');
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
        $typeUsers_list = $this->repository->typeUser_list();

        return view('user.edit', [
            'namepage'       => 'Usuário',
            'threeview'      => 'Cadastros',
            'titlespage'     => ['Cadastro de usuários'],
            'titlecard'      => 'Editar usuário',
            'typeUsers_list' => $typeUsers_list,
            'table'          => $this->repository->getTable(),
            'goback'         => true,
            'add'            => false,
            'user'           => $user
        ]);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {

            $request->all()['password'] != null ? $userRequest = $request->all() : $userRequest = $request->except('password');

            $this->validator->with($userRequest)->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $user = $this->repository->update($userRequest, $id);

            $response = [
                'message' => 'Usuário atualizado',
                'type'   => 'info',
            ];

            session()->flash('return', $response);

            return redirect()->route('user.index');
        } catch (ValidatorException $e) {
            
            $response = [
                'message' =>  $e->getMessageBag(),
                'type'    => 'error'
            ];

            session()->flash('return', $response);

            return redirect()->route('user.index');
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
