<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CityCreateRequest;
use App\Http\Requests\CityUpdateRequest;
use App\Repositories\CityRepository;
use App\Repositories\NeighborhoodRepository;
use App\Validators\CityValidator;
use Auth;

date_default_timezone_set('America/Recife');

class CitiesController extends Controller
{
    protected $repository;
    protected $validator;

    public function __construct(CityRepository $repository, CityValidator $validator, NeighborhoodRepository $neighborhoodRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->neighborhoodRepository = $neighborhoodRepository;
    }

    public function store(CityCreateRequest $request)
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
                if(Auth::user()->type > 2)
                {
                    $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                    $this->repository->create($request->all());

                    $response = [
                        'message' => 'Cidade cadastrada',
                        'type'   => 'info',
                    ];
                }
                else
                    return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
            catch (ValidatorException $e)
            {
                $response = [
                    'message' =>  $e->getMessageBag(),
                    'type'    => 'error'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('neighborhood.index');
        }
    }

    public function edit($id)
    {
        if(!Auth::check())
        {
            session()->flash('return');
            return view('auth.login');
        }
        else
        {
            if(Auth::user()->type > 2)
            {
                $city = $this->repository->find($id);
                return view('city.edit', [
                    'namepage'       => 'Bairro',
                    'threeview'      => 'Cadastros',
                    'titlespage'     => ['Cadastro de Cidade'],
                    'titlecard'      => 'Editar cidade',
                    'table'          => $this->repository->getTable(),
                    'goback'         => true,
                    'add'            => false,
                    'city'           => $city
                ]);
            }
            else
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
        }
    }

    public function update(CityUpdateRequest $request, $id)
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
                if(Auth::user()->type > 2)
                {
                    $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
                    $this->repository->update($request->all(), $id);
                    $response = [
                        'message' => 'Cidade atualizada',
                        'type'   => 'info',
                    ];
                }
                else
                    return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
            catch (ValidatorException $e)
            {
                $response = [
                    'message' =>  $e->getMessageBag(),
                    'type'    => 'error'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('neighborhood.index');
        }
    }

    public function destroy($id)
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
                if(Auth::user()->type > 2)
                {
                    $deleted = $this->repository->update(['active' => 'off'], $id);
                    $neighborhoods = $this->neighborhoodRepository->where('city_id', $id)->get();

                    if($deleted)
                        foreach($neighborhoods as $neighborhood) $neighborhood->update(['active' => 'off']);

                    $response = [
                        'message' => 'Cidade deletada',
                        'type'   => 'info',
                    ];
                }
                else
                    return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
            catch (ValidatorException $e)
            {
                $response = [
                    'message' =>  $e->getMessageBag(),
                    'type'    => 'error'
                ];
            }
            session()->flash('return', $response);
            return redirect()->route('neighborhood.index');
        }
    }

    //Method not used
    public function index(){}
    public function show($id){}


}
