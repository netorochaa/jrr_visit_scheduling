<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;
use App\Repositories\CollectRepository;
use App\Validators\UserValidator;
use App\Entities\Util;
use Auth;
use Exception;
use Log;

date_default_timezone_set('America/Recife');

class HomeController extends Controller
{
    private $repository, $collectRepository;
    private $validator;

    public function __construct(UserRepository $repository, UserValidator $validator, CollectRepository $collectRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->collectRepository = $collectRepository;
    }

    public function index(Request $request)
    {
        if(Auth::check())
        {
            $collects = $this->collectRepository->whereMonth('date', '=', date('m'))->get();
            return view('home', [
                'namepage'      => 'Home',
                'threeview'     => null,
                'titlespage'    => ['Dashboard'],
                'titlecard'     => 'Bem vindo(a) ' . Auth::user()->name,
                'collects'      => $collects
            ]);
        }
        else
        {
            if(count($request->all()) > 0)
            {
                $user = $this->findUser($request);
                $pass = $request->get('password');

                if($user)
                {
                    // if(env('PASSWORD_HASH') && Hash::check($request->get('password'), $user->password))
                    if($pass == $user->password)
                    {
                        session()->flush();
                        $credentials = $request->only('email', 'password');
                        // dd($credentials);
                        // Auth::attempt($credentials);
                        Auth::login($user);
                        Auth::user() ? Log::channel('mysql')->info('Usuário: ' . $user->name . ' logou!') : Log::channel('mysql')->error('Usuário: ' . $user->name . ' erro, não logou!');

                        $collects = $this->collectRepository->whereMonth('date', '=', date('m'))->get();
                        return view('home', [
                            'namepage'      => 'Home',
                            'threeview'     => null,
                            'titlespage'    => ['Dashboard'],
                            'titlecard'     => 'Bem vindo(a) ' . Auth::user()->name,
                            'collects'      => $collects
                        ]);
                    }
                    else
                    {
                        $response = [
                            'message' =>  "Senha inválida",
                            'type'    => 'error'
                        ];
                    }
                }
                else
                {
                    $response = [
                        'message' =>  "Usuário não cadastrado",
                        'type'    => 'error'
                    ];
                }
            }
            else
                $response = [];
        }
        session()->flash('return', $response);
        return view('auth.login', $response);
    }

    public function findUser($req)
    {
        try {
            $user = $this->repository->findWhere(['email' => $req->get('email'), 'active' => 'on'])->first();
            return $user;
        } catch (Exception $th) {
            return false;
        }
    }

    public function logout()
    {
        Auth::logout();
        return view('auth.login');
    }
}
