<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Auth;
use Exception;
use Log;

date_default_timezone_set('America/Recife');

class HomeController extends Controller
{
    private $repository;
    private $validator;

    public function __construct(UserRepository $repository, UserValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    public function index(Request $request)
    {
        if(Auth::check())
        {
            return view('home', [
                'namepage' => 'Home',
                'threeview' => null,
                'titlespage' => ['Dashboard'],
                'titlecard' => 'Bem vindo (a)',
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

                        return view('home', [
                            'namepage' => 'Home',
                            'threeview' => null,
                            'titlespage' => ['Dashboard'],
                            'titlecard' => 'Bem vindo (a)',
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
