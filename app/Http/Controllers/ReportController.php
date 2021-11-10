<?php

namespace App\Http\Controllers;

use App\Entities\Util;
use App\Repositories\{CollectRepository, CollectorRepository};
use Auth;
use Illuminate\Http\Request;

date_default_timezone_set('America/Recife');

class ReportController extends Controller
{
    protected $collectRepository;

    protected $collectorRepository;

    public function __construct(CollectRepository $collectRepository, CollectorRepository $collectorRepository)
    {
        $this->collectRepository   = $collectRepository;
        $this->collectorRepository = $collectorRepository;
    }

    public function cash(Request $request)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        $request->has('dateRange_filter') ? $period_get         = $request->get('dateRange_filter') : $period_get         = null;
        $request->has('collector_filter') ? $collector_selected = $request->get('collector_filter') : $collector_selected = null;
        $splitDates                                             = $period_get ? explode(' - ', $period_get) : null;
        // filter dates
        $dates    = $splitDates ? [Util::setDateLocalBRToDb(trim($splitDates[0]), true), Util::setDateLocalBRToDb(trim($splitDates[1]), true)] : [date('Y-m-d') . ' 00:00:01', date('Y-m-d') . ' 23:59:59'];
        $collects = $this->collectRepository->whereBetween('status', [3, 6])->whereBetween('date', $dates);
        // filter collector
        if ($collector_selected && $collector_selected != 'all') {
            $collects           = $collects->where('collector_id', $collector_selected);
            $collector_selected = $this->collectorRepository->find($collector_selected);
        }
        // list collector for select
        $collectors = $this->collectorRepository->where('active', 'on')->pluck('name', 'id');
            
        return view('report.index', [
            'report'              => 'cash',
            'namepage'            => 'Caixa',
            'threeview'           => 'Relatórios',
            'titlespage'          => ['Caixa'],
            'titlecard'           => 'Relatório de caixa das coletas domiciliares',
            'titlemodal'          => 'Opções de filtro',
            'add'                 => true,
            'filtersReport'       => true,
            'thead_for_datatable' => ['Nº', 'Data', 'Pacientes', 'Bairro', 'Coletador', 'Status', 'Forma', 'Taxa'],
            'collects'            => $collects->get(),
            'period_get'          => $period_get,
            'collector_selected'  => $collector_selected->name ?? null,
            'collector_list'      => $collectors,
        ]);
    }

    public function graphic(Request $request)
    {
        if (!Auth::check() || Auth::user()->type <= 3) {
            session()->flash('return');

            return view('auth.login');
        }
        
        $request->has('dateRange_filter') ? $period_get = $request->get('dateRange_filter') : $period_get = null;
        $splitDates                                     = $period_get ? explode(' - ', $period_get) : null;
        $barChatQtd                                     = null;

        if ($period_get) {
            // filter dates
            $dates = $splitDates
                            ? [Util::setDateLocalBRToDb(trim($splitDates[0]), true), Util::setDateLocalBRToDb(trim($splitDates[1]), true)]
                            : [date('Y-m-d') . ' 00:00:01', date('Y-m-d') . ' 23:59:59'];
            $collects = $this->collectRepository
                                    ->where('status', '>=', 5)
                                    ->whereBetween('date', $dates)
                                    ->get();
            $labels          = [];
            $array_finished  = [];
            $array_cancelled = [];

            foreach ($collects as $collect) {
                $date = Util::setDate($collect->date, false);
                if (in_array($date, $labels)) {
                    continue;
                }
                     
                array_push($labels, $date);
                array_push($array_finished, $collects
                                                    ->where('status', 6)
                                                    ->whereBetween('date', [Util::setDateLocalBRToDb($date, false) . '00:00:00', Util::setDateLocalBRToDb($date, false) . '23:59:59'])->count());
                array_push($array_cancelled, $collects
                                                    ->where('status', '>', 6)
                                                    ->where('user_id_cancelled', '!=', 2)
                                                    ->whereBetween('date', [Util::setDateLocalBRToDb($date, false) . '00:00:00', Util::setDateLocalBRToDb($date, false) . '23:59:59'])->count());
            }

            $barChatQtd = app()->chartjs
                                    ->name('barChartTest')
                                    ->type('bar')
                                    ->labels($labels)
                                    ->datasets([
                                        [
                                            'min'             => '0',
                                            'label'           => 'Concluídas',
                                            'backgroundColor' => '#007bff',
                                            'data'            => $array_finished,
                                        ],
                                        [
                                            'min'             => '0',
                                            'label'           => 'Canceladas ou com horários modificados (Sem desistências do site)',
                                            'backgroundColor' => '#dc3545',
                                            'data'            => $array_cancelled,
                                        ],
                                    ])
                                    ->optionsRaw('{
                                        scales: {
                                            yAxes: [{
                                                ticks: {
                                                    beginAtZero: true
                                                }
                                            }]
                                        }
                                    }');
        }

        return view('report.index', [
            'report'          => 'graphic',
            'namepage'        => 'Gráficos',
            'threeview'       => 'Relatórios',
            'titlespage'      => ['Gráficos'],
            'titlecard'       => 'Gráficos das coletas domiciliares',
            'titlemodal'      => 'Opções de filtro',
            'add'             => true,
            'filtersReport'   => true,
            'collects'        => $collects ?? null,
            'period_get'      => $period_get,
            'barChatQtd'      => $barChatQtd,
        ]);
    }
}
