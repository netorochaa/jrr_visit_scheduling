<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Repositories\CollectRepository;
use App\Repositories\NeighborhoodRepository;
use App\Repositories\CancellationTypeRepository;
use App\Repositories\PatientTypeRepository;
use App\Repositories\PersonRepository;
use App\Repositories\CollectorRepository;
use App\Repositories\FreeDayRepository;
use App\Validators\CollectValidator;
use App\Entities\Util;
use App\Mail\SendMailSchedule;
use DateTime;
use Exception;
use DB;
use Log;

date_default_timezone_set('America/Recife');

class PublicCollectController extends Controller
{

    protected $repository;
    protected $validator;

    public function __construct(CollectRepository $repository, CollectValidator $validator, NeighborhoodRepository $neighborhoodRepository,
                                CancellationTypeRepository $cancellationTypeRepository ,PersonRepository $peopleRepository, PatientTypeRepository $patientTypeRepository,
                                CollectorRepository $collectorRepository, FreeDayRepository $freeDayRepository)
    {
        $this->repository                 = $repository;
        $this->validator                  = $validator;
        $this->neighborhoodRepository     = $neighborhoodRepository;
        $this->cancellationTypeRepository = $cancellationTypeRepository;
        $this->peopleRepository           = $peopleRepository;
        $this->patientTypeRepository      = $patientTypeRepository;
        $this->collectorRepository        = $collectorRepository;
        $this->freeDayRepository          = $freeDayRepository;
    }

    // API TO GET AVAILABLES
    public function available(Request $request)
    {
        try
        {
            $site = $request->has('site') ? true : false;
            $where = $site ? ['active' => 'on', 'showInSite' => 'on'] : ['active' => 'on'];

            $neighborhood_id = $request->get('neighborhood_id');
            $dateOfCollect   = trim(Util::setDateLocalBRToDb($request->get('datecollect'), false));
            $dateNow         = date("Y-m-d h:i");
            $collector_list  = $this->collectorRepository->where($where)->get();
            $freeDay_list    = $this->freeDayRepository->where('dateEnd', '>', $dateNow)->get();

            // SEPARATES COLLECTORS ACCORDING TO THE NEIGHBORHOOD
            $array_collectors = [];
            foreach($collector_list as $collector)
            {
                foreach($collector->neighborhoods as $neighborhood)
                    if($neighborhood->id == $neighborhood_id) array_push($array_collectors, $collector->id);
            }
            // GET AVAILABLES SCHEDULES
            $collect_list = DB::table('collects')
                                ->select('collects.id', 'collects.date', 'collects.hour', 'collects.status', 'collectors.name', 'collectors.id as collector_id', 'collectors.mondayToFriday', 'collectors.saturday', 'collectors.sunday')
                                ->join('collectors', 'collects.collector_id', '=', 'collectors.id')
                                ->whereDate('date', $dateOfCollect)
                                ->whereIn('collector_id', $array_collectors)
                                ->where([['status', '1'], ['cancellationType_id', null]])
                                ->orderBy('date')->orderBy('collector_id')->get()->toArray();

            // REMOVE SCHEDULES ACCORDING TO FREEDAYS
            $collects_remove = [];
            foreach ($freeDay_list as $freeday)
            {
                $end = new DateTime($freeday->dateEnd);
                $end->modify('+1 day');
                $end = $site ? $end->format('Y-m-d H:i:s') : $freeday->dateEnd;

                foreach ($freeday->collectors as $collector)
                {
                    for ($i=0; $i < count($collect_list); $i++)
                    {
                        if((strtotime($collect_list[$i]->date) >= strtotime($freeday->dateStart)) && (strtotime($collect_list[$i]->date) <= strtotime($end)) && $collect_list[$i]->collector_id == $collector->id)
                            array_push($collects_remove, $i);
                        else
                            continue;
                    }
                }
            }

            foreach ($collects_remove as $item)
                unset($collect_list[$item]);

            return $collect_list;
        }
        catch(Exception $e)
        {
            Log::channel('mysql')->info('Erro api available: ' . Util::getException($e));
        }
    }

    // API RELEASE IN 15 MIN. COLLECTS WITH STATUS = NEW
    public function release(Request $request)
    {
        $auth = $request->has('list_all_new_collects_more_10_min') ? true : false;
        if($auth)
        {
            $collects = $this->repository->where('status', 2)->get();
            if(count($collects) > 0)
            {
                try
                {
                    foreach($collects as $collect)
                    {
                        $reserved_at = new DateTime($collect->reserved_at);
                        $diff_date = $reserved_at->diff(new DateTime());
                        // dd($diff_date->i);
                        if($diff_date->i > 15)
                        {
                            $id_user = 2;
                            // UPDATE DATA WITH TYPE CANCELLATION COLLECT
                            $this->repository->update([
                                'status'                => 7,
                                'cancellationType_id'   => 2,
                                'user_id_cancelled'     => $id_user,
                                'closed_at'             => Util::dateNowForDB()
                            ], $collect->id);
                            // Reset values for new releasing collect
                            $collect = $this->repository->collectReset($collect);
                            Log::channel('mysql')->info('Coleta resetada: ' . $collect);
                            $arrayCollect = $collect->toArray();
                            $idCancelled = $arrayCollect['id'];
                            // remove id of array
                            unset($arrayCollect['id']);
                            // insert new releasing, available for schedule
                            $collectNew = $this->repository->create($arrayCollect);
                            Log::channel('mysql')->info('Get Api release: ' . $idCancelled . ' - ' . $collect->date . ' reset - time diff: ' . $diff_date->i . ' | Novo agendamento ID: ' . $collectNew);
                        }
                    }
                }
                catch(Exception $e)
                {
                    Log::channel('mysql')->info('Erro api release: ' . Util::getException($e));

                    return false;
                }
            }
            return count($collects) . " - " . date('d/m/Y H:i');
        }
    }

    // COLLECT PUBLIC
    public function index(Request $request)
    {
        // dd($request->session()->all());
        $sessionActive = null;
        if($request->session()->has('collect'))
        {
            $sessionActive = $request->session()->get('collect');
            $collect = $this->repository->find($sessionActive->id);
            if($collect->status == 7) $request->session()->flush();
        }

        if(!$request->has('neighborhood')) $neighborhood_list = $this->neighborhoodRepository->where('active', 'on')->get();
        else $neighborhood_model = $this->neighborhoodRepository->find($request->get('neighborhood'));

        return view('collect.public.index', [
            'titlespage' => null,
            'titlecard'  => 'Solicitar agendamento de coleta domiciliar',
            'ambulancy'  => true,
            'sessionActive'         => $sessionActive,
            'neighborhood_list'     => $neighborhood_list ?? null,
            'neighborhood_model'    => $neighborhood_model ?? null
        ]);
    }

    public function schedule(Request $request, $id)
    {
        try
        {
            $collect = $this->repository->find($id);

            // VERIFICA SE EXISTE UMA SOLICITAÇAO PENDENTE
            if($request->session()->has('collect'))
            {
                $sessionActive = $request->session()->get('collect');
                $collect = $this->repository->find($sessionActive->id);
                if($collect->status == 7)
                {
                    $request->session()->flush();
                    $response = [
                        'message' => 'Período de solicitação expirado. A sessão foi encerrada.',
                        'type'    => 'info'
                    ];
                    session()->flash('return', $response);
                    return redirect()->route('public.index');
                }
            }
            else return redirect()->route('public.index');

            $collector              = $this->collectorRepository->find($collect->collector->id);
            $cancellationType_list  = $this->cancellationTypeRepository->where('active', 'on')->pluck('name', 'id');
            $patientType_list       = $this->patientTypeRepository->patientTypeWithResponsible_list();
            $collectType_list       = $this->repository->collectType_list();
            $payment_list           = $this->repository->payment_list(true);
            $typeResponsible_list   = $this->peopleRepository->typeResponsible_list();
            $covenant_list          = $this->peopleRepository->covenant_list();
            $quant                  = (int)count($collect->people);
            $price                  = $quant == 0 ? 0 : $collect->neighborhood->displacementRate;
            if($quant > 2) $price   = ($quant-1) * $collect->neighborhood->displacementRate;
            $priceString            = "R$ " . (string) $price;

            return view('collect.public.edit', [
                'titlespage' => null,
                'titlecard'  => 'Dados da solicitação',
                'titlemodal' => 'Cadastrar paciente',
                'ambulancy'  => true,
                //Lists for select
                'cancellationType_list' => $cancellationType_list,
                'patientType_list'      => $patientType_list,
                'collectType_list'      => $collectType_list,
                'payment_list'          => $payment_list,
                'typeResponsible_list'  => $typeResponsible_list,
                'covenant_list'         => $covenant_list,
                'quant'                 => $quant,
                'price'                 => $priceString,
                //Info of entitie
                'table' => $this->repository->getTable(),
                'collect' => $collect
            ]);
        }
        catch(Exception $e)
        {
            $response = [
                'message' =>  'Ocorreu um erro. Nossos técnicos foram avisados. Pedimos desculpas pelo transtorno. Você pode tentar novamente em outra data ou horário.',
                'type'    => 'error'
            ];

            Log::channel('mysql')->info('Agendamento público: ' . Util::getException($e));

            session()->flash('return', $response);
            return redirect()->route('public.index');
        }
    }

    public function reserve(Request $request)
    {
        try
        {
            $id_collect      = $request->get('infoCollect');
            if($id_collect  == "Selecione") return redirect()->route('public.index')->withErrors(['Selecione um horário']);
            $id_neighborhood = $request->get('neighborhood_id');
            $id_origin       = 2;
            $id_status       = 2;

            if($id_collect != "Selecione")
            {
                //SESSION ACTIVE? PREVIOUSLY RESERVED COLLECTION?
                $sessionActive = null;
                if($request->session()->has('collect')){
                    $sessionActive = $request->session()->get('collect');
                    if($sessionActive->id != (int)$id_collect) return redirect()->route('public.index');
                }

                $collect = $this->repository->find($id_collect);

                //collect used?
                if($collect->status > 1 || isset($collect->cancellationType_id) || isset($collect->neighborhood) || isset($collect->reserved_at))
                {
                    $response = [
                        'message' => 'Este horário já estava ou acabou de ser reservado! Escolha outro horário disponível na lista abaixo.',
                        'type'    => 'error'
                    ];
                    session()->flash('return', $response);
                    return redirect()->route('public.index');
                }
                else if($this->repository->where([['collector_id', $collect->collector_id],
                                                ['date', $collect->date],
                                                ['id', '!=', $collect->id]
                                                ])
                                        ->whereBetween('status', [2, 6])->count() > 0)
                {
                    Log::channel('mysql')->info('Erro duplicação: ' . $collect);
                    $response = [
                        'message' => 'Este horário já estava ou acabou de ser reservado! Escolha outro horário disponível na lista abaixo.',
                        'type'    => 'error'
                    ];
                    session()->flash('return', $response);
                    return redirect()->route('public.index');
                }
                else
                {
                    $new_collect = $this->repository->update(['user_id' => $id_origin, 'neighborhood_id' => $id_neighborhood, 'status' => $id_status, 'reserved_at' => new DateTime()], $collect->id);

                    //START SESSION
                    $request->session()->put('collect', $new_collect);
                    $request->session()->put('timer', date('Y-m-d H:i'));

                    $response = [
                        'message' => 'Data e horário reservados por 10 minutos, preencha os demais dados para confirmar a solicitação',
                        'type'    => 'info'
                    ];
                    session()->flash('return', $response);
                    return redirect()->route('collect.public.schedule', $collect->id);
                }
            }
            else
            {
                $response = [
                    'message' => 'Horário não selecionado',
                    'type'    => 'error'
                ];
                session()->flash('return', $response);
                return redirect()->route('public.index');
            }
        }
        catch (Exception $e)
        {
            $response = [
                'message' =>  'Ocorreu um erro. Nossos técnicos foram avisados. Pedimos desculpas pelo transtorno. Você pode tentar novamente em outra data ou horário.',
                'type'    => 'error'
            ];
            Log::channel('mysql')->info('Reservar agendamento público: ' . Util::getException($e));
            session()->flash('return', $response);
            return redirect()->route('public.index');
        }
    }

    public function update(Request $request, $id)
    {
        try
        {
            //SESSION ACTIVE? PREVIOUSLY RESERVED COLLECTION?
            if($request->session()->has('collect'))
            {
                $collect = $this->repository->find($id);
                // COLLECT STILL AVAILABLE? OTHER CLIENT TAKE?
                if($collect->status > 2)
                {
                    $request->session()->flush();
                    return redirect()->route('public.index')->withErrors(['O horário acabou de ser confirmado por outra pessoa. Sua sessão foi encerrada. Por gentileza, realize uma nova solicitação para outro horário ou data.']);
                }
                // USER SITE = 2
                $id_user = 2;
                // UPDATE DATA
                $request->merge(['status' => 3]);
                $collect = $this->repository->update($request->except('cancellationType_id', 'attachment'), $id);

                if($request->has('attachment'))
                {
                    $i = 0;
                    $attachment =  $collect->attachment;
                    foreach ($request->allFiles()['attachment'] as $archives)
                    {
                        if( $archives->getMimeType() == "application/pdf" ||
                            $archives->getMimeType() == "image/png" ||
                            $archives->getMimeType() == "image/jpg" ||
                            $archives->getMimeType() == "image/jpeg")
                        {
                            if($archives->getSize() < 3000000)
                            {
                                $i++;
                                $name_archive = $collect->id . "_" . date('d-M-YHi') . "-" . $i . "." . $archives->extension();
                                $archives->storeAs('anexos/' . $collect->id, $name_archive);
                                $attachment = $attachment . "*" .$name_archive;
                            }
                        }
                    }
                    $this->repository->update(['attachment' => $attachment], $collect->id);
                }

                $request->session()->flush();
                // CANCELLATION COLLECT
                if($request->has('cancellationType_id'))
                {
                    $collect['closed_at']           = Util::dateNowForDB();;
                    $collect['cancellationType_id'] = (integer) $request->get('cancellationType_id');
                    $collect['user_id_cancelled']   = $id_user;
                    $collect['status']              = 7;

                    // UPDATE DATA WITH TYPE CANCELLATION COLLECT
                    $this->repository->update($collect->toArray(), $collect->id);
                    // Reset values for new releasing collect
                    $collect = $this->repository->collectReset($collect);
                    $arrayCollect = $collect->toArray();
                    // remove id of array
                    unset($arrayCollect['id']);
                    // insert new releasing, available for schedule
                    $this->repository->create($arrayCollect);

                    $response = [
                        'message' => 'Solicitação de agendamento cancelada',
                        'type'    => 'info'
                    ];
                    session()->flash('return', $response);
                    return redirect()->route('public.index');
                }
                $response = [
                    'message'   => 'Solicitação de agendamento enviada',
                    'text'      => 'Anote o número da sua solicitação: Nº ' . $collect->id,
                    'describe'  => count($collect->people) . ' paciente(s) na data: ' . $collect->formatted_date . ' no seguinte endereço: ' . $collect->address . ', ' . $collect->numberAddress . ', ' . $collect->neighborhood->name . ' ' . $collect->cep,
                    'type'      => 'info'
                ];
                // send email
                foreach ($collect->people as $person)
                {
                    if($person->email != null)
                    {
                        session()->flash('return', $response);
                        Mail::to($person->email)->queue(new SendMailSchedule());
                    }
                }
            }
            else
            {
                $response = [
                    'message' => 'Sessão encerrada',
                    'type'    => 'info'
                ];
                session()->flash('return', $response);
                return redirect()->route('public.index');
            }
        }
        catch (Exception $e)
        {
            Log::channel('mysql')->error('Salvar Agendamento público: ' . Util::getException($e));

            if(get_class($e) != "Swift_TransportException")
            {
                $response = [
                    'message' => 'Ocorreu um erro. Nossos técnicos foram avisados. Pedimos desculpas pelo transtorno.',
                    'type'    => 'error'
                ];

                session()->flash('return', $response);
                return redirect()->route('public.index');
            }
        }

        session()->flash('return', $response);
        return view('collect.public.sent');

    }

    public function cancellation(Request $request, $id)
    {
        $collect = $this->repository->find($id);
        // COLLECT STILL AVAILABLE? OTHER CLIENT TAKE?
        if($collect->status > 2)
        {
            $request->session()->flush();
            return redirect()->route('public.index')->withErrors(['O horário acabou de ser confirmado por outra pessoa. Sua sessão foi encerrada. Por gentileza, realize uma nova solicitação para outro horário ou data.']);
        }
        $request['cancellationType_id'] = 2;
        return $this->update($request, $id);
    }

    /**
     * Methods not used
     */
    public function destroy($id){}
    public function show($id){}
    public function edit($id){}
    public function store(Request $request){}
}
