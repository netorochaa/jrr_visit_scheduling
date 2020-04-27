<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use DateInterval;
use DatePeriod;
use DB;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CollectorCreateRequest;
use App\Http\Requests\CollectorUpdateRequest;
use App\Repositories\CollectorRepository;
use App\Repositories\UserRepository;
use App\Repositories\NeighborhoodRepository;
use App\Validators\CollectorValidator;

date_default_timezone_set('America/Recife');

class CollectorsController extends Controller
{

    protected $repository, $userRepository, $neighborhoodRepository;
    protected $validator;

    public function __construct(CollectorRepository $repository, CollectorValidator $validator, UserRepository $userRepository, NeighborhoodRepository $neighborhoodRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->userRepository = $userRepository;
        $this->neighborhoodRepository = $neighborhoodRepository;
    }

    public function index()
    {
        $collectors_list  = $this->repository->all();
        $user_list        = $this->userRepository->where('type', 2)->pluck('name', 'id');

        $schedules  = $this->repository->schedules();

        return view('collector.index', [
            'namepage'      => 'Coletador',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de coletadores'],
            'titlecard'     => 'Lista de coletadores',
            'titlemodal'    => 'Cadastrar coletador',
            'add'           => true,
            //List for select
            'user_list'     => $user_list,
            'schedules'     => $schedules,
            //Info of entitie
            'table'               => $this->repository->getTable(),
            'thead_for_datatable' => ['Nome', 'Segunda/Sexta', 'Sábados', 'Domingos', 'Endereço inicial', 'Colaborador', 'Bairros', 'Status'],
            'collectors_list'     => $collectors_list
        ]);
    }

    public function store(CollectorCreateRequest $request)
    {
        try
        {
            $arrayMondayToFriday = null;
            $arraySaturday = null;
            $arraySunday = null;

            if($request->has('mondayToFriday'))
            { 
                $arrayMondayToFriday = $request->all()['mondayToFriday'];
                $request->merge(['mondayToFriday' => implode(',', $request->all()['mondayToFriday'])]); 
            }
            if($request->has('saturday'))
            { 
                $arraySaturday = $request->all()['saturday'];
                $request->merge(['saturday' => implode(',', $request->all()['saturday'])]); 
            }
            if($request->has('sunday'))
            { 
                $arraySunday = $request->all()['sunday'];
                $request->merge(['sunday' => implode(',', $request->all()['sunday'])]); 
            }

            $inicio = new DateTime();
            $inicio->modify('+1 day');
            $fim = new DateTime();
            $fim->modify('+2 month');

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $collector = $this->repository->create($request->all());

            $interval = new DateInterval('P1D');
            $periodo = new DatePeriod($inicio, $interval ,$fim);

            //CRIAR MÉTODO E MOVER PARA ENTIDADE OU REPOSITORIO
            foreach($periodo as $data)
            {
                $day = $data->format("l");
                $date = $data->format("Y-m-d");

                if( $day == "Monday" ||
                    $day == "Tuesday" ||
                    $day == "Wednesday" ||
                    $day == "Thursday" ||
                    $day == "Friday")
                {
                    if($arrayMondayToFriday)
                    {
                        for($i = 0; $i < count($arrayMondayToFriday); $i++)
                        {
                            DB::table('collects')->insert(
                                ['date' => $date . " " . $arrayMondayToFriday[$i], 'hour' => $arrayMondayToFriday[$i], 'collector_id' => $collector->id]
                            );
                        }
                    }
                }
                else if ($day == "Saturday")
                {
                    if($arraySaturday)
                    {
                        for($i = 0; $i < count($arraySaturday); $i++)
                        {
                            DB::table('collects')->insert(
                                ['date' => $date . " " . $arraySaturday[$i], 'hour' => $arraySaturday[$i], 'collector_id' => $collector->id]
                            );
                        }
                    }
                }
                else if ($day == "Sunday")
                {
                    if($arraySunday)
                    {
                        for($i = 0; $i < count($arraySunday); $i++)
                        {
                            DB::table('collects')->insert(
                                ['date' => $date . " " . $arraySunday[$i], 'hour' => $arraySunday[$i], 'collector_id' => $collector->id]
                            );
                        }
                    }
                }
            }
            $response = [
                'message' => 'Coletador criado',
                'type'   => 'info',
            ];
        }
        catch (ValidatorException $e)
        {
            $response = [
                'message' =>  $e->getMessageBag(),
                'type'    => 'error'
            ];
        }
        session()->flash('return', $response);
        return redirect()->route('collector.index');
    }

    public function storeCollectorNeighborhoods(Request $request, $collect_id)
    {
        try
        {
            $collector = $this->repository->find($collect_id);
            $neighborhoods = $request->has('neighborhood_id') ? $request->all()['neighborhood_id'] : null;

            if($neighborhoods == null){
                $response = [
                    'message' =>  'Bairros não informados.',
                    'type'    => 'error'
                ];
                session()->flash('return', $response);
                return redirect()->route('collector.index', $collector->id);
            }
            else
            {
                for ($i=0; $i < count($neighborhoods); $i++)
                    $collector->neighborhoods()->attach($neighborhoods[$i]);
                
                $response = [
                    'message' => 'Bairros relacionados',
                    'type'   => 'info',
                ];
            }
        }
        catch (ValidatorException $e)
        {
            $response = [
                'message' =>  $e->getMessageBag(),
                'type'    => 'error'
            ];
        }
        session()->flash('return', $response);
        return redirect()->route('collector.index', $collector->id);
    }

    public function show($id)
    {
        $collector = $this->repository->find($id);
        $neighborhoods = $this->neighborhoodRepository->neighborhoodsCities_list()->pluck('name', 'id');

        return view('collector.collector_neighborhood.index', [
            'namepage'      => 'Coletador',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Vincular bairro'],
            'titlecard'     => 'Bairros vinculados ao ' . $collector->getAttribute('name'),
            'titlemodal'    => 'Relacionar bairros ao coletador ' . $collector->getAttribute('name'),
            'goback'        => true,
            'add'           => true,
            'collector'     => $collector,
            'neighborhoods' => $neighborhoods
        ]);
    }

    public function edit($id)
    {
        $collector = $this->repository->find($id);
        $user_list = $this->userRepository->where('type', 2)->pluck('name', 'id');
        $schedules  = $this->repository->schedules();

        return view('collector.edit', [
            'namepage'      => 'Coletador',
            'threeview'     => 'Cadastros',
            'titlespage'    => ['Cadastro de coletadores'],
            'titlecard'     => 'Lista de coletadores cadastrados',
            'goback'        => true,
            'add'           => false,
            //Lists for select
            'user_list' => $user_list,
            'schedules' => $schedules,
            //Info of entitie
            'table' => $this->repository->getTable(),
            'collector' => $collector
        ]);
    }

    public function update(CollectorUpdateRequest $request, $id)
    {
        try
        {
            if($request->has('mondayToFriday')) $request->merge(['mondayToFriday' => implode(',', $request->all()['mondayToFriday'])]);
            if($request->has('saturday')) $request->merge(['saturday' => implode(',', $request->all()['saturday'])]);
            if($request->has('sunday')) $request->merge(['sunday' => implode(',', $request->all()['sunday'])]);

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $collector = $this->repository->update($request->all(), $id);

            //Adicionar data de virgência e criar script para atualizar horário das coletas

            $response = [
                'message' => 'Coletador atualizado',
                'type'   => 'info',
            ];
        }
        catch (ValidatorException $e)
        {
            $response = [
                'message' =>  $e->getMessageBag(),
                'type'    => 'error'
            ];
        }
        session()->flash('return', $response);
        return redirect()->route('collector.index');
    }

    public function detachCollectorNeighborhoods($collector_id, $neighborhood_id)
    {
        $collector = $this->repository->find($collector_id);
        $neighborhood = $collector->neighborhoods()->where('neighborhood_id', $neighborhood_id)->get();

        $detach = $collector->neighborhoods()->detach($neighborhood);

        $response = [
            'message' => 'Coletador atualizado',
            'type'   => 'info',
        ];
        session()->flash('return', $response);
        return redirect()->route('collector.collector_neighborhood.index');
    }

    //Methods not used
    public function destroy($id){}
}
