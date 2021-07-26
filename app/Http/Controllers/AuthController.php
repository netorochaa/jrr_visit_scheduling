<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\CollectRepository;
use App\Repositories\CollectorRepository;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Entities\Util;
use Carbon\CarbonImmutable;

date_default_timezone_set('America/Recife');

class AuthController extends Controller
{
    private $repository, $collectRepository;

    public function __construct(UserRepository $repository, CollectRepository $collectRepository, CollectorRepository $collectorRepository)
    {
        $this->repository = $repository;
        $this->collectRepository = $collectRepository;
        $this->collectorRepository = $collectorRepository;
    }

    public function dashboard(Request $request)
    {
        if(Auth::check())
        {
            $date_now   = CarbonImmutable::now();
            $subDay     = !$request->has('rangedate') ? 7 : (is_numeric($request->get('rangedate')) ? $request->get('rangedate') : 7);
            $startDate  = $date_now->subDays($subDay);
            $collects   = $this->collectRepository->whereBetween('date', [$startDate->toDateString() . " 00:00:00", $date_now->toDateString() . " 23:59:59"])->where('status', '>', 2)->orderBy('date')->get();
            $users      = $this->repository->all(); 
            $barChatQtd = null;
            $labels     = [];
            $array_done = [];

            foreach ($collects as $collect) 
            {
                $date = Util::setDate($collect->date, false);
                if(in_array($date, $labels)) continue; 
                else{ 
                    array_push($labels, $date);
                    array_push($array_done, $collects->where('status', '<', 7)->whereBetween("date", [Util::setDateLocalBRToDb($date, false) . "00:00:00", Util::setDateLocalBRToDb($date, false) . "23:59:59"])->count());
                }
            }

            // dd($array_reserverd);
            if(Auth::user()->type > 3)
            {
                $barChatQtd = app()->chartjs
                                ->name('barChartTest')
                                ->type('bar')
                                // ->size(['width' => 400, 'height' => 200])
                                // ->labels(['Label x', 'Label y'])
                                ->labels($labels)
                                ->datasets([
                                    [
                                        'min' => '0',
                                        'label' => 'Reservadas/Concluídas',
                                        'backgroundColor' => '#007bff',
                                        'data' => $array_done
                                    ],
                                ])
                                ->optionsRaw("{
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }]
                                    }
                                }");
            }

            return view('home', [
                'namepage'      => 'Home',
                'threeview'     => null,
                'titlespage'    => ['Dashboard'],
                'titlecard'     => 'Bem vindo(a) ' . Auth::user()->name,
                'collects'      => $collects,
                'users'         => $users,
                'barChatQtd'    => $barChatQtd
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

            return $this->dashboard($request);
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
                $date = $collector->date_start_last_modify > Util::dateNowForDB() ? $collector->date_start_last_modify : date('Y-m-d');
            else
                $date = $collector->date_start > Util::dateNowForDB() ? $collector->date_start : date('Y-m-d');

            $this->collectorRepository->setAvailableCollects($arrayMondayToFriday, $arraySaturday, $arraySunday, $date, $collector->id);
        }
    }
}
