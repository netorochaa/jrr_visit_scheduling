<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Repositories\CollectRepository;
use App\Repositories\NeighborhoodRepository;
use App\Repositories\CollectorRepository;
use App\Repositories\CancellationTypeRepository;
use App\Repositories\UserRepository;
use App\Repositories\PersonRepository;
use App\Repositories\PatientTypeRepository;
use App\Repositories\ActivityRepository;
use App\Validators\CollectValidator;
use App\Mail\SendMailSchedule;
use App\Entities\Util;
use DateTime;
use Exception;
use Auth;
use Log;

date_default_timezone_set('America/Recife');

class CollectsController extends Controller
{
    protected $repository, $neighborhoodRepository, $collectorRepository, $cancellationTypeRepository, $userRepository, $peopleRepository, $freeDayRepository, $patientTypeRepository;
    protected $validator;

    public function __construct(CollectRepository $repository, CollectValidator $validator, NeighborhoodRepository $neighborhoodRepository,
                                CollectorRepository $collectorRepository, CancellationTypeRepository $cancellationTypeRepository, UserRepository $userRepository,
                                PersonRepository $peopleRepository,PatientTypeRepository $patientTypeRepository, ActivityRepository $activityRepository)
    {
        $this->repository                 = $repository;
        $this->validator                  = $validator;
        $this->neighborhoodRepository     = $neighborhoodRepository;
        $this->collectorRepository        = $collectorRepository;
        $this->cancellationTypeRepository = $cancellationTypeRepository;
        $this->userRepository             = $userRepository;
        $this->peopleRepository           = $peopleRepository;
        $this->patientTypeRepository      = $patientTypeRepository;
        $this->activityRepository         = $activityRepository;
    }

    public function download($id, $archive)
    {
        return response()->download(storage_path('app/public/anexos/' . $id . '/' . $archive));
    }

    // CRUD AND MARK COLLECT
    public function index(Request $request)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $goback = false;
            if(!$request->has('neighborhood'))
                $neighborhood_list  = $this->neighborhoodRepository->where('active', 'on')->get();
            else
            {
                $goback = true;
                $neighborhood_model = $this->neighborhoodRepository->find($request->get('neighborhood'));
            }
            return view('collect.index', [
                'namepage'   => 'Agendar coleta',
                'threeview'  => 'Coletas',
                'titlespage' => ['Coletas'],
                'titlecard'  => 'Agendar coleta',
                'goback'     => $goback,
                'transfer'   => false,
                //List for select
                'neighborhood_list'  => $neighborhood_list  ?? null,
                'neighborhood_model' => $neighborhood_model ?? null,
            ]);
        }
    }

    // EXTRA COLLECT
    public function extra()
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                $cancellationType_list  = $this->cancellationTypeRepository->where('active', 'on')->pluck('name', 'id');
                $patientType_list       = $this->patientTypeRepository->patientTypeWithResponsible_list();
                $collectType_list       = $this->repository->collectType_list();
                $payment_list           = $this->repository->payment_list(false);
                $userAuth_list          = $this->userRepository->where([['id', '>', 1], ['active', 'on']])->whereBetween('type', [3, 98])->pluck('name', 'name');
                $collector_list         = $this->collectorRepository->where('active', 'on')->pluck('name', 'id');
                $hour_list              = $this->collectorRepository->schedules();
                $neighborhood_list      = $this->neighborhoodRepository->where('active', 'on')->pluck('name', 'id');

                return view('collect.extra', [
                    'namepage'      => 'Coleta extra',
                    'numberModal'   => '2',
                    'threeview'     => 'Coletas',
                    'titlespage'    => ['Coleta extra'],
                    'titlecard'     => 'Cadastrar coleta extra',
                    'titlecard2'    => 'Adicionar paciente',
                    'titlemodal'    => 'Cadastrar paciente',
                    'goback'        => false,
                    'add'           => false,
                    //Lists for select
                    'cancellationType_list' => $cancellationType_list,
                    'patientType_list'      => $patientType_list,
                    'collectType_list'      => $collectType_list,
                    'payment_list'          => $payment_list,
                    'userAuth_list'         => $userAuth_list,
                    'collector_list'        => $collector_list,
                    'hour_list'             => $hour_list,
                    'neighborhood_list'     => $neighborhood_list,
                    //Info of entitie
                    'table' => $this->repository->getTable()
                ]);
            }
            catch(Exception $e)
            {
                $response = [
                    'message' =>  $e->getMessage(),
                    'type'    => 'error'
                ];
                session()->flash('return', $response);
                return redirect()->route('auth.home');
            }
        }
    }
    public function store(Request $request)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            // RESERVED
            $status = 3;
            $extra = 1;
            try
            {
                $request->merge(['date'   => Util::setDateLocalBRToDb($request->get('date') . " " . $request->get('hour'), true)]);
                $request->merge(['status' => $status]);
                $request->merge(['extra'  => $extra]);

                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                $collect = $this->repository->create($request->all());

                $response = [
                    'message' => 'Coleta criada',
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
            return redirect()->route('collect.schedule', $collect->id);
        }
    }

    // LIST PAGES - DEIXAR APENAS UM MÉTODO
        public function listAll(Request $request)
        {
            if(!Auth::check())
            {
                session()->flash('return');
                return view('auth.login');
            }
            else
            {
                if(count($request->all()) > 0 && $request['value'])
                {
                    if($request->get('typeSearch') == 'collects.date') $request['value'] = Util::setDateLocalBRToDb($request->get('value'), false);
                    $table = explode('.', $request->get('typeSearch'));
                    $table[0] == 'people' ? $collect_list = $this->repository->collect_filter($request->get('typeSearch'), '%' . $request->get('value') . '%')->get() : //pesquisa por paciente
                                            $collect_list = $this->repository->where($request->get('typeSearch'), 'like',  '%' . $request->get('value') . '%')->where('collects.status', '>', '1')->get(); //pesquisa por coleta
                }
                else $collect_list = [];

                return view('collect.template_table', [
                    'namepage'   => 'Todas coletas',
                    'threeview'  => 'Coletas',
                    'titlespage' => ['Todas coletas'],
                    'titlecard'  => 'Lista de todas coletas',
                    'filter'     => true,
                    'search_list' => $this->repository->search_type_collect(),
                    //Info of entitie
                    'table'               => $this->repository->getTable(),
                    'thead_for_datatable' => ['Data/Hora', 'Código', 'Paciente', 'Pagamento Taxa', 'Bairro', 'Endereço', 'Coletador', 'Status'],
                    'collect_list'        => $collect_list
                ]);
            }
        }
        public function listReserved()
        {
            if(!Auth::check())
            {
                session()->flash('return');
                return view('auth.login');
            }
            else
            {
                $collect_list = $this->repository->where('neighborhood_id', '!=', null)
                                                    ->where('status', 3)->get();

                return view('collect.template_table', [
                    'namepage'   => 'Coletas reservadas',
                    'threeview'  => 'Coletas',
                    'titlespage' => ['Coletas reservadas'],
                    'titlecard'  => 'Lista de coletas reservadas',
                    //Info of entitie
                    'table'               => $this->repository->getTable(),
                    'thead_for_datatable' => ['Data/Hora', 'Código', 'Paciente', 'Pagamento Taxa', 'Bairro', 'Endereço', 'Coletador', 'Status'],
                    'collect_list'        => $collect_list
                ]);
            }
        }
        public function listConfirmed()
        {
            if(!Auth::check())
            {
                session()->flash('return');
                return view('auth.login');
            }
            else
            {
                $collect_list = $this->repository->where('neighborhood_id', '!=', null)
                                                    ->where('status', 4)->get();

                return view('collect.template_table', [
                    'namepage'   => 'Coletas confirmadas',
                    'threeview'  => 'Coletas',
                    'titlespage' => ['Coletas confirmadas'],
                    'titlecard'  => 'Lista de coletas confirmadas',
                    //Info of entitie
                    'table'               => $this->repository->getTable(),
                    'thead_for_datatable' => ['Data/Hora', 'Código', 'Paciente', 'Pagamento Taxa', 'Bairro', 'Endereço', 'Coletador', 'Status'],
                    'collect_list'        => $collect_list
                ]);
            }
        }
        public function listProgress()
        {
            if(!Auth::check())
            {
                session()->flash('return');
                return view('auth.login');
            }
            else
            {
                $collect_list = $this->repository->where('neighborhood_id', '!=', null)
                                                    ->where('status', 5)->get();

                return view('collect.template_table', [
                    'namepage'   => 'Coletas em andamento',
                    'threeview'  => 'Coletas',
                    'titlespage' => ['Coletas em andamento'],
                    'titlecard'  => 'Lista de coletas em andamento',
                    //Info of entitie
                    'table'               => $this->repository->getTable(),
                    'thead_for_datatable' => ['Data/Hora', 'Código', 'Paciente', 'Pagamento Taxa', 'Bairro', 'Endereço', 'Coletador', 'Status'],
                    'collect_list'        => $collect_list
                ]);
            }
        }
    // END LIST PAGES

    public function schedule($id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                $collect = $this->repository->find($id);

                $collector              = $this->collectorRepository->find($collect->collector->id);
                $cancellationType_list  = $this->cancellationTypeRepository->where('active', 'on')->pluck('name', 'id');
                $patientType_list       = $this->patientTypeRepository->patientTypeWithResponsible_list();
                $collectType_list       = $this->repository->collectType_list();
                $payment_list           = $this->repository->payment_list(false);
                $userAuth_list          = $this->userRepository->where([['id', '>', 1], ['active', 'on']])->whereBetween('type', [3, 98])->pluck('name', 'name');
                $search_list            = $this->peopleRepository->search_list();
                $typeResponsible_list   = $this->peopleRepository->typeResponsible_list();
                $covenant_list          = $this->peopleRepository->covenant_list();
                $quant                  = count($collect->people);
                $price                  = $quant == 0 ? 0 : $collect->neighborhood->displacementRate;
                $price                  = $quant  > 2 ? ($quant-1) * $collect->neighborhood->displacementRate : $collect->neighborhood->displacementRate;
                $priceString            = "R$ " . (string) $price;
                $attachments            = $collect->attachment != null ? explode('*', $collect->attachment) : null;
                
                if(Auth::user()->type > 2) $neighborhood_model = $this->neighborhoodRepository->find($collect->neighborhood_id);

                if(Auth::user()->type < 3) 
                {
                    $rangeArray = Util::getDayOfWeek($collect->date) == "Saturday" ? explode(",", $collector->saturday) : (Util::getDayOfWeek($collect->date) == "Sunday" ? explode(",", $collector->sunday) : explode(",", $collector->mondayToFriday));
                    $range = "Entre " . $rangeArray[0] . " e " . end($rangeArray);
                }


                return view('collect.edit', [
                    'namepage'      => 'Agendar coleta',
                    'numberModal'   => '2',
                    'threeview'     => 'Coletas',
                    'titlespage'    => ['Coletas'],
                    'titlecard'     => 'Agendamento de coleta',
                    'titlecard2'    => 'Adicionar paciente',
                    'titlemodal'    => 'Cadastrar paciente',
                    'idmodal'       => Auth::user()->type > 2 ? 'tranfer' : null,
                    'goback'        => false,
                    'add'           => false,
                    'modifyhour'    => Auth::user()->type > 2 ? true : false,
                    'transfer'      => Auth::user()->type > 2 ? true : false,
                    //Lists for select
                    'cancellationType_list' => $cancellationType_list,
                    'patientType_list'      => $patientType_list,
                    'collectType_list'      => $collectType_list,
                    'payment_list'          => $payment_list,
                    'userAuth_list'         => $userAuth_list,
                    'search_list'           => $search_list,
                    'typeResponsible_list'  => $typeResponsible_list,
                    'covenant_list'         => $covenant_list,
                    'quant'                 => $quant,
                    'price'                 => $priceString,
                    'range'                 => $range ?? null,
                    'neighborhood_model'    => $neighborhood_model ?? null,
                    'attachments'           => $attachments,
                    //Info of entitie
                    'table' => $this->repository->getTable(),
                    'collect' => $collect
                ]);
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
    }

    public function update(Request $request, $id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                $collect = $this->repository->find($id);
                $id_user = Auth::user()->id;

                Log::channel('mysql')->info(Auth::user()->name . ' ATUALIZOU a coleta: ' . $collect);

                // UPDATE DATA
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
                $collect = $this->repository->update($request->except('cancellationType_id', 'site', 'attachment'), $id);

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
                                $name_archive = $collect->id . "_" . date('d-M-YHis') . "-" . $i . "." . $archives->extension();
                                $archives->storeAs('anexos/' . $collect->id, $name_archive);
                                $attachment = $attachment . "*" .$name_archive;
                            }
                        }
                    }
                    $this->repository->update(['attachment' => $attachment], $collect->id);
                }

                Log::channel('mysql')->info('Para: ' . $collect);

                $response = [
                    'message' => 'Coleta reservada e atualizada',
                    'type'    => 'info'
                ];

                // CANCELLATION COLLECT
                if($request->has('cancellationType_id'))
                {
                    $collect['closed_at']           = Util::dateNowForDB();;
                    $collect['cancellationType_id'] = $request->get('cancellationType_id');
                    $collect['user_id_cancelled']   = $id_user;
                    $collect['status']              = 7;

                    // UPDATE DATA WITH TYPE CANCELLATION COLLECT
                    $collect = $this->repository->update($collect->toArray(), $collect->id);
                    Log::channel('mysql')->info(Auth::user()->name . ' CANCELOU a coleta: ' . $collect->id);
                    //IF NOT EXTRA COLLECT
                    if($collect->extra != '1')
                    {
                        if($collect->collect_old)
                        {
                            $collect_initial = $this->repository->find($collect->collect_old);
                            $collect->date = $collect_initial->date;
                            $collect->hour = $collect_initial->hour;
                            $reset = $collect_initial->status == "1" ? false : true;
                        }
                        else $reset = true;

                        if($reset)
                        {
                            // Reset values for new releasing collect
                            $collect = $this->repository->collectReset($collect);
                            Log::channel('mysql')->info('Coleta resetada: ' . $collect);
                            $arrayCollect = $collect->toArray();
                            // remove id of array
                            unset($arrayCollect['id']);
                            // insert new releasing, available for schedule
                            $new_collect_reset = $this->repository->create($arrayCollect);
                            Log::channel('mysql')->info('Nova coleta resetada: ' . $new_collect_reset);
                        }
                        else Log::channel('mysql')->info('Reset: false');
                    }

                    $response = [
                        'message' => 'Coleta cancelada',
                        'type'    => 'info'
                    ];

                    session()->flash('return', $response);
                    return redirect()->route('collect.index') ;
                }
            }
            catch (Exception $e)
            {
                $response = [
                    'message' => Util::getException($e),
                    'type'    => 'error'
                ];
                Log::channel('mysql')->info('Erro em alguma parte do update collect: ' . Util::getException($e));
            }
            session()->flash('return', $response);
            return redirect()->route('collect.schedule', $collect->id);
        }
    }

    public function reserve(Request $request)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $id_collect      = $request->get('infoCollect');
            $id_neighborhood = $request->get('neighborhood_id');
            $id_origin       = Auth::user()->id;
            // Reservada
            $status          = 3;
            $collect = $this->repository->find($id_collect);

            //collect used?
            if($collect->status > 1 || isset($collect->cancellationType_id) || isset($collect->neighborhood) || isset($collect->reserved_at))
            {
                $response = [
                    'message' => 'Este horário já estava ou acabou de ser reservado! Escolha outro horário disponível na lista abaixo.',
                    'type'    => 'error'
                ];
                session()->flash('return', $response);
                return redirect()->route('collect.index');
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
                return redirect()->route('collect.index');
            }
            else
            {
                try
                {
                    $this->repository->update(['user_id' => $id_origin, 'neighborhood_id' => $id_neighborhood, 'status' => $status, 'reserved_at' => new DateTime()], $collect->id);

                    $response = [
                        'message' => 'Data e horário reservados',
                        'type'    => 'info'
                    ];
                }
                catch (Exception $e)
                {
                    $response = [
                        'message' => $e->getMessage(),
                        'type'    => 'erro'
                    ];
                }
                session()->flash('return', $response);
                return redirect()->route('collect.schedule', $collect->id);
            }
        }
    }

    public function confirmed(Request $request, $id)
    {
        if(!Auth::check() && !$request->has('confirmed_in_email'))
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $collect = $this->repository->find($id);
            $id_user = !Auth::check() && $request->has('confirmed_in_email') ? 2 : Auth::user()->id;

            if($collect->status > 3)
            {
                $response = [
                    'message' => 'Não é possível confirmar uma coleta que não esta reservada',
                    'type'    => 'error'
                ];
                session()->flash('return', $response);
                return  $request->has('confirmed_in_email') ? "<script>window.close();</script>" : redirect()->route('collect.list.reserved');
            }
            else
            {
                try
                {
                    // 4 = CONFIRMADA
                    $this->repository->update(['status' => 4, 'user_id_confirmed' => $id_user, 'confirmed_at' =>  Util::dateNowForDB()], $collect->id);
                }
                catch (Exception $e)
                {
                    $response = [
                        'message' => $e->getMessage(),
                        'type'    => 'error'
                    ];
                    Log::channel('mysql')->error($e->getMessage());
                }
                $response = [
                    'message'   => 'Agendamento para coleta domiciliar CONFIRMADO',
                    'text'      => 'Anote o número da sua solicitação: Nº ' . $collect->id,
                    'describe'  => count($collect->people) . ' paciente(s) na data: ' . $collect->formatted_date . ' às ' . $collect->hour . ' no seguinte endereço: ' . $collect->address . ', ' . $collect->numberAddress . ', ' . $collect->neighborhood->name . ' ' . $collect->cep,
                    'type'      => 'confirmed'
                ];
                // send email
                foreach ($collect->people as $person) {
                    if($person->email != null){
                        session()->flash('return', $response);
                        Mail::to($person->email)->queue(new SendMailSchedule());
                    }
                }

                session()->flash('return', $response);
                return $request->has('confirmed_in_email') ? "<script>window.close();</script>" : redirect()->route('collect.list.reserved');
            }
        }
    }

    public function close(Request $request, $id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            $collect = $this->repository->find($id);
            $id_user = Auth::user()->id;
            try
            {
                // CANCELLATION COLLECT
                if($request->get('cancellationType_id'))
                {
                    $collect['closed_at'] = Util::dateNowForDB();
                    // 8 = CANCELADO EM ROTA
                    // UPDATE DATA WITH TYPE CANCELLATION COLLECT
                    $this->repository->update(['status' => 8, 'cancellationType_id' => (integer)$request->get('cancellationType_id'), 'user_id_cancelled' => $id_user, 'closed_at' => Util::dateNowForDB()], $collect->id);
                }
                else
                    $this->repository->update(['status' => 6, 'closed_at' => Util::dateNowForDB()], $collect->id); //CONCLUÍDA

                $response = [
                        'message' => 'Coleta ' . $collect->id . ' finalizada',
                        'type'    => 'info'
                    ];
                }
            catch (Exception $e)
            {
                $response = [
                    'message' => $e->getMessage(),
                    'type'    => 'error'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('activity.index');
        }
    }

    public function transfer(Request $request, $id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            try
            {
                $id_new             = $request->get('infoCollect');
                $collect_old        = $this->repository->find($id);
                $collect_new        = $this->repository->find($id_new);
                //PREPARED COLLECTS TO TRANSFER
                $array_collect_old  = $collect_old->toArray();
                unset($array_collect_old['id'], $array_collect_old['date'], $array_collect_old['hour'], $array_collect_old['collector_id'], $array_collect_old['created_at']);
 
                //collects used?
                if($collect_new->status > 1 || isset($collect_new->cancellationType_id) || isset($collect_new->neighborhood) || isset($collect_new->reserved_at))
                {
                    $response = [
                        'message' => 'Este horário já estava ou acabou de ser reservado! Escolha outro horário disponível na lista abaixo.',
                        'type'    => 'error'
                    ];
                    session()->flash('return', $response);
                    return redirect()->route('collect.schedule', $collect_old->id);
                }
                else if($this->repository->where([
                                            ['collector_id', $collect_new->collector_id],
                                            ['date', $collect_new->date],
                                            ['id', '!=', $collect_new->id]
                                        ])
                                        ->whereBetween('status', [2, 6])->count() > 0)
                {
                    Log::channel('mysql')->info('Erro duplicação: ' . $collect_new);
                    $response = [
                        'message' => 'Este horário já estava ou acabou de ser reservado! Escolha outro horário disponível na lista abaixo.',
                        'type'    => 'error'
                    ];
                    session()->flash('return', $response);
                    return redirect()->route('collect.schedule', $collect_old->id);
                }
                else
                {
                    $collect_new = $this->repository->update($array_collect_old, $collect_new->id);
                    Log::channel('mysql')->info(Auth::user()->name . ' TRANSFERIU a coleta: ' . $collect_old->id . ' - PARA: ' . $collect_new->id);

                    // ATTACH PATIENTS
                    foreach ($collect_old->people as $person) 
                    {
                        $collect_new->people()->attach($person->id);
                        $collect_new->people()->updateExistingPivot($person->id, ['covenant' => $person->pivot->covenant, 'exams' => $person->pivot->exams]);
                        $collect_old->people()->detach($person->id);
                    }

                    // TRANSFER ATTACHMENTS
                    if($collect_old['attachment'])
                    {
                        $attachment = "";
                        $files      = Storage::allFiles('anexos/' . $collect_old->id);
                        
                        foreach ($files as $file) 
                        {
                            $array_archive_old = explode("_", basename($file));

                            $archive_old    = $file; //
                            $archive_name   = $collect_new->id . "_" . $array_archive_old[1];
                            $archive_new    = 'anexos/' . $collect_new->id . "/" . $archive_name;
                            Storage::move($archive_old, $archive_new);
                            
                            $attachment = $attachment . "*" . $archive_name;
                        }
                        $this->repository->update(['attachment' => $attachment],  $collect_new->id);
                    }

                    //IF NOT EXTRA COLLECT
                    if($collect_old->extra != '1')
                    {
                        // Reset values for new releasing collect
                        $collect = $this->repository->collectReset($collect_old);
                        Log::channel('mysql')->info('Coleta resetada: ' . $collect);
                        $arrayCollect = $collect->toArray();
                        $new_collect_reset = $this->repository->update($arrayCollect, $collect->id);
                        Log::channel('mysql')->info('Nova coleta resetada: ' . $new_collect_reset);
                    }
                    $response = [
                        'message' => 'Coleta transferida para ' . $collect_new->id,
                        'type'    => 'info'
                    ];
                }
                session()->flash('return', $response);
                return redirect()->route('collect.schedule', $collect_new->id);
            }
            catch (Exception $e)
            {
                $response = [
                    'message' => $e->getMessage(),
                    'type'    => 'error'
                ];
                session()->flash('return', $response);
                return redirect()->route('collect.schedule', $id);
            }
        }
    }

    public function sendconfirmation($id)
    {
        try
        {
            $collect = $this->repository->find($id);
            $status = false;
            $response = [
                'message'   => 'Confirme seu agendamento de coleta domiciliar',
                'text'      => 'Número da sua solicitação: Nº ' . $collect->id,
                'describe'  => count($collect->people) . ' paciente(s) na data: ' . $collect->formatted_date . ' às ' . $collect->hour . ' no seguinte endereço: ' . $collect->address . ', ' . $collect->numberAddress . ', ' . $collect->neighborhood->name . ' ' . $collect->cep,
                'link'      => route('collect.confirmed', $collect->id),
                'type'      => 'sendconfirmation'
            ];

            // send email
            foreach ($collect->people as $person)
            {
                if($person->email != null){
                    session()->flash('return', $response);
                    Mail::to($person->email)->queue(new SendMailSchedule());
                }
                $status = true;
            }
            if($status) $this->repository->update(['sendconfirmation' => $collect->sendconfirmation+1], $collect->id);
        }
        catch(Exception $e)
        {
            // dd(Util::getException($e));
            Log::channel('mysql')->info('Erro ao enviar e-mail para confirmação: ' . Util::getException($e));
        }
        return redirect()->route('collect.schedule', $id);
    }

    public function modifyHour(Request $request, $id)
    {
        $model      = $this->repository->find($id);
        $collect    = $model->toArray();
        $datetime   = explode(' ', $collect['date']);
        $date       = $datetime[0];
        $hour       = $request->get('hour');
        unset($collect['id']);
        
        try
        {
            // VERIFICA SE EXISTE HORÁRIO IGUAL NESTA DATA PARA ESTE COLETADOR
            if(count($this->repository->whereDate('date', $date)->whereTime('hour', $hour)->where('collector_id', $model->collector_id)->whereBetween('status', [1, 6])->get()) == 0)
            {
                $collect_old = $collect;
                $collect_old['status'] = 9; //STATUS HORÁRIO MODIFICADO
                $collect_old['user_id_modified'] = Auth::user()->id;
                $collect_old['hour_new'] = $hour;
                $collect_modified = $this->repository->create($collect_old); //GARANTE QUE HORÁRIO NÃO FICARÁ MAIS DISPONÍVEL
                $collect_modified->people()->attach($model->people);

                $this->repository->update([
                    'date' => $date . " " . $hour . ":00", 
                    'hour' => $hour, 
                    'collect_old' => $collect_old['collect_old'] ? $collect_old['collect_old'] : $collect_modified->id
                ], $id);

                Log::channel('mysql')->info(Auth::user()->name . ' MODIFICOU O HORÁRIO da coleta: ' . $id. ' - DE ' . $model->date . ' PARA: ' . $date . " " . $hour . ":00");
                
                $msg = 'Horário alterado';
            }
            else
                $msg = 'Não é possível alterar para  ' . $hour . 'h, já existe uma coleta disponível ou marcada para este horário.';

            $response = [
                'message' => $msg,
                'type'    => 'info'
            ];
        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }
        session()->flash('return', $response);
        return redirect()->route('collect.schedule', $id);
    }

    /**
     * Methods not used
     */
    //public function destroy($id){}
    //public function edit($id){}
    //public function show($id){}
}
