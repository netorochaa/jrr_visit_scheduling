<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Auth;
use Exception;
use Hash;
use Session;
use Log;

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
        $user = $this->findUser($request);
        
        if($user)
        {
            if(env('PASSWORD_HASH') && Hash::check($request->get('password'), $user->password))
            {
                $credentials = $request->only('email', 'password');
                Auth::attempt($credentials);
                $request->session()->put(['logged_id' => $user->id, 'logged' => $user->name]);
                Log::channel('mysql')->info('Something happened!');
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
        
        return view('home', [
            'namepage' => 'Home',
            'logged' => $request->session()->get('logged'),
            'threeview' => null
        ]);
    }

    public function findUser($req)
    {        
        try {

            $credentials = $req->only('email', 'password');
            $user = $this->repository->findWhere(['email' => $req->get('email')])->first();

            return $user;
        } catch (Exception $th) {
            return false;
        }
    }
}
