<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;
use App\Repositories\CollectRepository;
use App\Repositories\CollectorRepository;
use App\Validators\UserValidator;
use Auth;
use Exception;
use Log;

date_default_timezone_set('America/Recife');

class HomeController extends Controller
{
    private $repository, $collectRepository;
    private $validator;

    public function __construct(UserRepository $repository, UserValidator $validator, CollectRepository $collectRepository,
                                CollectorRepository $collectorRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->collectRepository = $collectRepository;
        $this->collectorRepository = $collectorRepository;
    }

    public function index(Request $request)
    {

    }


    public function logout()
    {
        Auth::logout();
        return view('auth.login');
    }
}
