<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Exception;
use App\Http\Requests\CollectCreateRequest;
use App\Http\Requests\CollectUpdateRequest;
use App\Repositories\CollectRepository;
use App\Repositories\NeighborhoodRepository;
use App\Repositories\CollectorRepository;
use App\Repositories\CancellationTypeRepository;
use App\Repositories\UserRepository;
use App\Repositories\PersonRepository;
use App\Validators\CollectValidator;

date_default_timezone_set('America/Recife');

class CollectsController extends Controller
{
    protected $repository, $neighborhoodRepository, $collectorRepository, $cancellationTypeRepository, $userRepository, $peopleRepository;
    protected $validator;

    public function __construct(CollectRepository $repository, CollectValidator $validator, NeighborhoodRepository $neighborhoodRepository, 
                                CollectorRepository $collectorRepository, CancellationTypeRepository $cancellationTypeRepository, UserRepository $userRepository,
                                PersonRepository $peopleRepository)
    {
        $this->repository                 = $repository;
        $this->validator                  = $validator;
        $this->neighborhoodRepository     = $neighborhoodRepository;
        $this->collectorRepository        = $collectorRepository;
        $this->cancellationTypeRepository = $cancellationTypeRepository;
        $this->userRepository             = $userRepository;
        $this->peopleRepository           = $peopleRepository;
    }

    public function index()
    {
        $neighborhoodCity_list = $this->neighborhoodRepository->neighborhoodsCities_list()->get();
        $collector_list        = $this->collectorRepository->with('neighborhoods')->get();
        $collect_list          = $this->repository->all();
        
        return view('collect.index', [
            'namepage'      => 'Coletas',
            'threeview'     => null,
            'titlespage'    => ['Coletas'],
            'titlecard'     => 'Lista de coletas',
            'titlemodal'    => 'Agendar coleta',
            'add'           => true,
            //List for select
            'neighborhoodCity_list' => $neighborhoodCity_list,
            'collect_list'          => $collect_list,
            //Info of entitie
            'table'               => $this->repository->getTable(),
            'thead_for_datatable' => ['Data', 'Hora', 'Tipo', 'Status', 'Pagamento', 'Troco', 'Endereço', 'Link', 'Obs. Coleta', 'Anexo', 'Cancelamento', 'Tipo'],
            'collector_list'      => $collector_list
        ]);
    }

    public function store(CollectCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $collect = $this->repository->create($request->all());

            $response = [
                'message' => 'Collect created.',
                'data'    => $collect->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    public function schedule(Request $request)
    {

        try
        {
            $id = explode(",", $request->all()['infoCollect']);
            $id = $id[0];
            $idNeighborhood = $id[1];
            $collect = $this->repository->find($id);

            if(!$collect->neighborhood)
            {
                $response = [
                    'message' =>  'Este horário já estava ou acabou de ser reservado! Escolha outro horário disponível na lista abaixo.',
                    'type'    => 'error'
                ];
                session()->flash('return', $response);
                return redirect()->route('collect.index'); 
            }
            else
            {
                $cancellationType = $this->cancellationTypeRepository->pluck('name', 'id');
                $collectType_list = $this->repository->collectType_list();
                $statusCollects_list = $this->repository->statusCollects_list();
                $payment_list = $this->repository->payment_list();
                $userAuth_list = $this->userRepository->where('type', '>', 2)->pluck('name', 'name');
                $people_list = $this->$peopleRepository->all();

                return view('collect.schedule', [
                    'namepage'      => 'Coletas',
                    'threeview'     => null,
                    'titlespage'    => ['Coletas'],
                    'titlecard'     => 'Agendamento de coleta',
                    'goback'        => true,
                    'add'           => false,
                    //Lists for select
                    'schedules' => $schedules,
                    //Info of entitie
                    'table' => $this->repository->getTable(),
                    'collect' => $collect
                ]);
            }
        }
        catch(Exception $e)
        {
            $response = [
                'message' =>  $e->getMessage(),
                'type'    => 'error'
            ];
            session()->flash('return', $response);
            return redirect()->route('collect.index'); 
        }
    }

    public function update(CollectUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $collect = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Collect updated.',
                'data'    => $collect->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Methods not used
     */
    public function destroy($id){}
    public function edit($id){}
    public function show($id){}
}
