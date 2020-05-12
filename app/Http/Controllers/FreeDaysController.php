<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\FreeDayCreateRequest;
use App\Http\Requests\FreeDayUpdateRequest;
use App\Repositories\FreeDayRepository;
use App\Repositories\CollectorRepository;
use App\Repositories\CityRepository;
use App\Validators\FreeDayValidator;

date_default_timezone_set('America/Recife');

class FreeDaysController extends Controller
{
  
    protected $repository, $collectorRepository, $cityRepository;
    protected $validator;

    public function __construct(FreeDayRepository $repository, FreeDayValidator $validator, CollectorRepository $collectorRepository, CityRepository $cityRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->collectorRepository = $collectorRepository;
        $this->cityRepository = $cityRepository;
    }

    public function index()
    {
        $freedays           = $this->repository->all();
        // $collectors         = $this->collectorRepository->all();
        // $collectors_list    = $this->collectorRepository->pluck('name', 'id');
        // $cities_list        = $this->cityRepository->pluck('name', 'id');
        $type_list          = $this->repository->type_list();

        return view('freedays.index', [
            'namepage'      => 'Dias sem coletas',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de dias sem coletas'],
            'titlecard'     => 'Dias sem coletas',
            'titlemodal'    => 'Cadastrar dias sem coletas',
            'add'           => true,
            //Lists for select
            // 'collectors_list' => $collectors_list,
            // 'cities_list' => $cities_list,
            'type_list' => $type_list,
            //List of entitie
            'table' => $this->repository->getTable(),
            'thead_for_datatable' => ['Nome', 'Período'],
            'freedays_list' => $freedays,
        ]);
    }

    public function convertDateTime($fulldate)
    {
        $date = explode(" ", $fulldate);
        $onlyDate = $date[0];
        $hour = $date[1];

        $dateSplit = explode("/", $onlyDate);
        $day = $dateSplit[0];
        $month = $dateSplit[1];
        $year = $dateSplit[2];
        $finalDate = $year . "-" . $month . "-" . $day . " " . $hour;

        return $finalDate;
    }

    public function store(FreeDayCreateRequest $request)
    {
        try 
        {
            if($request->has('dateRange'))
            {
                $fulldate = explode("-", $request->all()['dateRange']);
                $fullDateStart = trim($fulldate[0]);
                $fullDateEnd = trim($fulldate[1]);
                
                $request->merge(['dateStart' => $this->convertDateTime($fullDateStart)]);
                $request->merge(['dateEnd' => $this->convertDateTime($fullDateEnd)]);
            }

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            // $collectors = $request->has('collector_id') ? $request->all()['collector_id'] : null;
            // $cities     = $request->has('city_id') ? $request->all()['city_id'] : null;
            // $collectors == null && $cities == null ? $freeDay = null : $freeDay = $this->repository->create($request->except(['city_id', 'collector_id', 'dateRange']));
            $freeDay = $this->repository->create($request->except(['dateRange']));
            
            // if($freeDay == null){
            //     $response = [
            //         'message' => 'Coletadores/Cidades não iformadas.',
            //         'type'   => 'error',
            //     ];
            //     session()->flash('return', $response);
            //     return redirect()->route('freedays.index');
            // }
            // else{
            //     if($collectors != null)
            //     {
            //         for ($i=0; $i < count($collectors); $i++) 
            //             $freeDay->collectors()->attach($collectors[$i]);
            //     }
            //     else if($cities != null)
            //     {
            //         for ($i=0; $i < count($cities); $i++) 
            //             $freeDay->cities()->attach($cities[$i]);
            //     }
                $response = [
                    'message' => 'Dias sem coletas cadastrados',
                    'type'   => 'info',
                ];
            // }
        } 
        catch (ValidatorException $e) 
        {
            $response = [
                'message' =>  $e->getMessageBag(),
                'type'    => 'error'
            ];
        }
        session()->flash('return', $response);
        return redirect()->route('freedays.index');
    }

    public function destroy($id)
    {
        try 
        {
            $deleted = $this->repository->destroy($id);

            $response = [
                'message' => 'Dia sem coleta deletado',
                'type'   => 'info',
            ];
        } 
        catch (Exception $e) 
        {
            $response = [
                'message' => $e->getMessage(),
                'type'   => 'error',
            ];
        }
        
        session()->flash('return', $response);
        return redirect()->route('freedays.index');
    }

    // METHODS NOT IMPLEMENTED, BECAUSE JUST WILL BE UTILIZED DESTROY
    public function show($id){}

    public function edit($id){}

    public function update(FreeDayUpdateRequest $request, $id){}
}
