<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Auth;

class HomeController extends Controller
{
    private $repository;
    private $validator;

    public function __construct(UserRepository $repository, UserValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    public function index()
    {
        return view('home', [
            'namepage' => 'Home',
            'threeview' => null
        ]);
    }

    public function doinglogin(Request $request)
    {        
        try {
            Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')], false);
            return redirect()->route('home');
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }
}
