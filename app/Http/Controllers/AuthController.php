<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;
use App\Repositories\CollectRepository;
use App\Repositories\CollectorRepository;
use Illuminate\Support\Facades\Auth;
use Log;
use Exception;
use App\Entities\Util;

date_default_timezone_set('America/Recife');

class AuthController extends Controller
{
    private $repository, $collectRepository;

    public function __construct(UserRepository $repository,CollectRepository $collectRepository, CollectorRepository $collectorRepository)
    {
        $this->repository = $repository;
        $this->collectRepository = $collectRepository;
        $this->collectorRepository = $collectorRepository;
    }

    public function dashboard()
    {
        if(Auth::check())
        {
            $collects   = $this->collectRepository->whereMonth('date', '=', date('m'))->get();
            $users      = $this->repository->all();

            return view('home', [
                'namepage'      => 'Home',
                'threeview'     => null,
                'titlespage'    => ['Dashboard'],
                'titlecard'     => 'Bem vindo(a) ' . Auth::user()->name,
                'collects'      => $collects,
                'users'         => $users
            ]);
        }
        else return view('auth.login');
    }

    public function do(Request $request)
    {
        $credentials =[
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'active' => 'on'
        ];
        if(Auth::attempt($credentials))
        {
            // IF COLLECTOR
            if(Auth::user()->type == 2)
               $this->updateCollects(Auth::user()->id);

            return $this->dashboard();
        }
        else
            return redirect()->back()->withInput()->withErrors(['E-mail ou senha inválido!']);
    }

    public function login()
    {
        return view('auth.login');
    }

    public function logout()
    {
        Auth::logout();
        return view('auth.login');
    }

    public function findUser($req)
    {
        try
        {
            $user = $this->repository->findWhere(['email' => $req->get('email'), 'active' => 'on'])->first();
            return $user;
        }
        catch (Exception $th)
        {
            return false;
        }
    }

    public function updateCollects($id_collector)
    {
        $collector = $this->collectorRepository->findWhere(['user_id' => $id_collector])->first();
        if($collector)
        {
            $arrayMondayToFriday = null;
            $arraySaturday = null;
            $arraySunday = null;
            //Send data to array
            if($collector->mondayToFriday)
                $arrayMondayToFriday = explode(',', $collector->mondayToFriday);
            if($collector->saturday)
                $arraySaturday = explode(',', $collector->saturday);
            if($collector->sunday)
                $arraySunday = explode(',', $collector->sunday);

            // Verify date start for set
            if($collector->date_start_last_modify)
                $date = $collector->date_start_last_modify > Util::dateNowForDB() ? Util::setDate($collector->date_start_last_modify, false) : date('d/m/Y');
            else
                $date = $collector->date_start > Util::dateNowForDB() ? Util::setDate($collector->date_start, false) : date('d/m/Y');

            $this->collectorRepository->setAvailableCollects($arrayMondayToFriday, $arraySaturday, $arraySunday, $date, $collector->id);
        }
    }
}
