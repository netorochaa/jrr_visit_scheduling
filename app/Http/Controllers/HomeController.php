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
        if(!Auth::check())
        {
            $user = $this->findUser($request);
            $pass = $request->get('password');

            if($user)
            {
                // if(env('PASSWORD_HASH') && Hash::check($request->get('password'), $user->password))
                if($pass == $user->password)
                {
                    $credentials = $request->only('email', 'password');
                    // dd($credentials);
                    // Auth::attempt($credentials);
                    Auth::login($user);
                    Auth::user() ? Log::channel('mysql')->info('Usuário: ' . $user->name . ' logou!') : Log::channel('mysql')->error('Usuário: ' . $user->name . ' erro, não logou!');
                }
                else
                { 
                    $response = [
                        'message' =>  "Senha inválida",
                        'type'    => 'error'
                    ];
                    session()->flash('return', $response);
                    return view('auth.login', $response);
                }
            }
            else
            {
                $response = [
                    'message' =>  "Usuário não cadastrado",
                    'type'    => 'error'
                ];               
                session()->flash('return', $response);
                return view('auth.login', $response);
            }       
        }
        
        return view('home', [
            'namepage' => 'Home',
            'threeview' => null
        ]);
    }

    public function findUser($req)
    {        
        try {
            $user = $this->repository->findWhere(['email' => $req->get('email')])->first();

            return $user;
        } catch (Exception $th) {
            return false;
        }
    }

    public function logout()
    {
        try {
            Auth::logout();    
        } 
        catch (Exception $e) 
        {
        }
        return view('auth.login');
    }
}
