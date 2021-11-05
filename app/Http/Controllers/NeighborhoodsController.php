<?php

namespace App\Http\Controllers;

use App\Entities\Util;
use App\Http\Requests\{NeighborhoodCreateRequest, NeighborhoodUpdateRequest};
use App\Repositories\{CityRepository, NeighborhoodRepository};
use App\Validators\NeighborhoodValidator;
use Auth;
use Exception;
use Prettus\Validator\Contracts\ValidatorInterface;

date_default_timezone_set('America/Recife');

class NeighborhoodsController extends Controller
{
    protected $repository;

    protected $cityRepository;

    protected $validator;

    public function __construct(NeighborhoodRepository $repository, NeighborhoodValidator $validator, CityRepository $cityRepository)
    {
        $this->repository     = $repository;
        $this->validator      = $validator;
        $this->cityRepository = $cityRepository;
    }

    public function index()
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        if (Auth::user()->type > 3) {
            $neighborhoods     = $this->repository->where('active', 'on')->get();
            $cities_list       = $this->cityRepository->where('active', 'on')->get(); // for card city
            $regions_list      = $this->repository->regions_list();
            $cities_pluck_list = $this->cityRepository->where('active', 'on')->get()->pluck('name', 'id'); // for select in neighborhood

            return view('neighborhood.index', [
                'namepage'      => 'Bairro',
                'numberModal'   => '2',
                'threeview'     => 'Cadastros',
                'titlespage'    => ['Cadastro de bairros'],
                'titlecard'     => 'Lista de bairros',
                'titlemodal'    => 'Cadastrar bairro',
                'titlecard2'    => 'Lista de cidades',
                'titlemodal2'   => 'Cadastrar cidade',
                'add'           => true,
                //Lists of selects
                'regions_list'      => $regions_list,
                'cities_list'       => $cities_list,
                'cities_pluck_list' => $cities_pluck_list,
                //Info of entitie
                'table'                => $this->repository->getTable(),
                'table2'               => $this->cityRepository->getTable(),
                'thead_for_datatable'  => ['Nome', 'Taxa', 'Região', 'Cidade', 'Criado', 'Última atualização'],
                'thead_for_datatable2' => ['Nome', 'UF', 'Criado'],
                'neighborhoods'        => $neighborhoods,
            ]);
        }
            
        return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
    }

    public function store(NeighborhoodCreateRequest $request)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                $this->repository->create($request->all());
                $response = [
                    'message' => 'Bairro criado',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('neighborhood.index');
    }

    public function edit($id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        if (Auth::user()->type > 3) {
            $neighborhood      = $this->repository->find($id);
            $regions_list      = $this->repository->regions_list();
            $cities_pluck_list = $this->cityRepository->where('active', 'on')->get()->pluck('name', 'id');

            return view('neighborhood.edit', [
                'namepage'      => 'Bairro',
                'threeview'     => 'Cadastros',
                'titlespage'    => ['Cadastro de bairros'],
                'titlecard'     => 'Bairros cadastrados',
                'titlemodal'    => 'Cadastrar bairro',
                'add'           => false,
                'goback'        => true,
                //Lists of selects
                'regions_list'      => $regions_list,
                'cities_pluck_list' => $cities_pluck_list,
                //Info of entitie
                'table'        => $this->repository->getTable(),
                'neighborhood' => $neighborhood,
            ]);
        }
            
        return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
    }

    public function update(NeighborhoodUpdateRequest $request, $id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
                $this->repository->update($request->all(), $id);

                $response = [
                    'message' => 'Bairro atualizado',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('neighborhood.index');
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $this->repository->update(['active' => 'off'], $id);
                $response = [
                    'message' => 'Bairro deletado',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('neighborhood.index');
    }
}
