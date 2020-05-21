<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Entities\Util;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\UserRepository;
use App\Repositories\CollectorRepository;
use App\Validators\UserValidator;
use App\Entities\User;
use Auth;
use Exception;

date_default_timezone_set('America/Recife');

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
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $users_list  = $this->repository->where([['active', 'on'], ['id', '>', 1]])->get();
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
                'table' => $this->repository->getTable(),
                'thead_for_datatable' => ['Nome', 'E-mail/Login', 'Tipo', 'Criado', 'Última atualização'],
                'users_list' => $users_list
            ]);
        }
    }

    public function store(UserCreateRequest $request)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                if(Auth::user()->type > 2)
                {
                    $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                    User::create($request->all());

                    $response = [
                        'message' => 'Usuário criado.',
                        'type'   => 'info',
                    ];
                }
                else return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
            catch (Exception $e)
            {
                $response = [
                    'message' =>  Util::getException($e),
                    'type'    => 'error'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('user.index');
        }
    }

    public function edit($id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            if(Auth::user()->type > 2 || Auth::user()->id == $id)
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
            else return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                $request->all()['password'] != null ? $user_request = $request->all() : $user_request = $request->except('password');
                $this->validator->with($user_request)->passesOrFail(ValidatorInterface::RULE_UPDATE);
                $user = User::find($id);
                $user->update($user_request);

                $response = [
                    'message' => 'Usuário atualizado',
                    'type'   => 'info',
                ];
            }
            catch (Exception $e)
            {
                $response = [
                    'message' =>  Util::getException($e),
                    'type'    => 'error'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('user.index');
        }
    }

    public function destroy($id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                if(Auth::user()->type > 2)
                {
                    $user = $this->repository->find($id);
                    $user->update(['active' => 'off']);
                    $response = [
                        'message' => 'Usuário deletado',
                        'type'   => 'info',
                    ];
                }
                else return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
            catch (Exception $e)
            {
                $response = [
                    'message' =>  Util::getException($e),
                    'type'    => 'error'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('user.index');
        }
    }

    //Methods not used
    public function show($id){}
}
