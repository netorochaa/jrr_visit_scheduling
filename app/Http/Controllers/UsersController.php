<?php

namespace App\Http\Controllers;

use App\Entities\{User, Util};
use App\Http\Requests\{UserCreateRequest, UserUpdateRequest};
use App\Repositories\{CollectorRepository, UserRepository};
use App\Validators\UserValidator;
use Auth;
use Exception;
use Prettus\Validator\Contracts\ValidatorInterface;

date_default_timezone_set('America/Recife');

class UsersController extends Controller
{
    protected $repository;

    protected $collectorRepository;

    protected $validator;

    public function __construct(UserRepository $repository, UserValidator $validator, CollectorRepository $collectorRepository)
    {
        $this->repository          = $repository;
        $this->validator           = $validator;
        $this->collectorRepository = $collectorRepository;
    }

    public function index()
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        $users_list     = $this->repository->where([['active', 'on'], ['id', '>', 1]])->get();
        $typeUsers_list = $this->repository->typeUser_list();

        return view('user.index', [
            'namepage'      => 'Usuário',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de usuários'],
            'titlecard'     => 'Lista de usuários',
            'titlemodal'    => 'Cadastrar usuário',
            'add'           => true,
            //Lists for select
            'typeUsers_list' => $typeUsers_list,
            //Info of entitie
            'table'               => $this->repository->getTable(),
            'thead_for_datatable' => ['Nome', 'E-mail/Login', 'Tipo', 'Criado', 'Última atualização'],
            'users_list'          => $users_list,
        ]);
    }

    public function store(UserCreateRequest $request)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                User::create($request->all());

                $response = [
                    'message' => 'Usuário criado.',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('user.index');
    }

    public function edit($id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        if (Auth::user()->type > 3 || Auth::user()->id == $id) {
            $user           = $this->repository->find($id);
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
                'user'           => $user,
            ]);
        }

        return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            $request->all()['password'] != null ? $user_request = $request->all() : $user_request = $request->except('password');
            $this->validator->with($user_request)->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $user = User::find($id);
            $user->update($user_request);

            $response = [
                'message' => 'Usuário atualizado',
                'type'    => 'info',
            ];
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('user.index');
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $user = $this->repository->find($id);
                $user->update(['active' => 'off']);
                $response = [
                    'message' => 'Usuário deletado',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('user.index');
    }
}
