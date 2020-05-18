<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CollectorRepository;
use App\Repositories\CollectRepository;
use App\Entities\Util;
use Auth;

class ReportController extends Controller
{
    protected $collectRepository, $collectorRepository;

    public function __construct(CollectRepository $collectRepository, CollectorRepository $collectorRepository)
    {
        $this->collectRepository = $collectRepository;
        $this->collectorRepository = $collectorRepository;
    }

    public function cash(Request $request)
    {
        $request->has('dateRange_filter') ? $period_get = $request->get('dateRange_filter') : $period_get = null;
        $request->has('collector_filter') ? $collector_selected = $request->get('collector_filter') : $collector_selected = null;

        $splitDates = $period_get ? explode(' - ', $period_get) : null;
        // filter dates
        $dates = $splitDates ? [Util::setDateLocalBRToDb(trim($splitDates[0]), true), Util::setDateLocalBRToDb(trim($splitDates[1]), true)] : [date('Y-m-d') . " 00:00:01", date('Y-m-d') . " 23:59:59"];
        $collects = $this->collectRepository->whereBetween('status', [3, 6])->whereBetween('date', $dates);

        // filter collector
        if($collector_selected && $collector_selected != 'all')
        {
            $collects = $collects->where('collector_id', $collector_selected);
            $collector_selected = $this->collectorRepository->find($collector_selected);
        }
        // list collector for select
        $collectors = $this->collectorRepository->where('active', 'on')->pluck('name', 'id');

        return view('report.index', [
            'report'        => 'cash',
            'namepage'      => 'Caixa',
            'threeview'     => 'Relatórios',
            'titlespage'    => ['Caixa'],
            'titlecard'     => 'Relatório de caixa das coletas domiciliares',
            'titlemodal'    => 'Opções de filtro',
            'add'           => true,
            'filtersReport' => true,
            'thead_for_datatable' => ['Nº', 'Data', 'Pacientes', 'Bairro', 'Coletador', 'Status', 'Forma', 'Taxa'],
            'collects'       => $collects->get(),
            'period_get'     => $period_get,
            'collector_selected' => $collector_selected->name ?? null,
            'collector_list' => $collectors
        ]);
    }

    public function index()
    {

    }
}
