<?php

namespace App\Http\Controllers;

use App\Entities\Util;
use App\Http\Requests\{CityCreateRequest, CityUpdateRequest};
use App\Repositories\{CityRepository, NeighborhoodRepository};
use App\Validators\CityValidator;
use Auth;
use Exception;
use Prettus\Validator\Contracts\ValidatorInterface;

date_default_timezone_set('America/Recife');

class CitiesController extends Controller
{
    protected $repository;

    protected $validator;

    public function __construct(CityRepository $repository, CityValidator $validator, NeighborhoodRepository $neighborhoodRepository)
    {
        $this->repository             = $repository;
        $this->validator              = $validator;
        $this->neighborhoodRepository = $neighborhoodRepository;
    }

    public function store(CityCreateRequest $request)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 2) {
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
                $this->repository->create($request->all());

                $response = [
                    'message' => 'Cidade cadastrada',
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
        
        if (Auth::user()->type > 2) {
            $city = $this->repository->find($id);

            return view('city.edit', [
                'namepage'       => 'Bairro',
                'threeview'      => 'Cadastros',
                'titlespage'     => ['Cadastro de Cidade'],
                'titlecard'      => 'Editar cidade',
                'table'          => $this->repository->getTable(),
                'goback'         => true,
                'add'            => false,
                'city'           => $city,
            ]);
        }
            
        return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
    }

    public function update(CityUpdateRequest $request, $id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 2) {
                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
                $this->repository->update($request->all(), $id);
                $response = [
                    'message' => 'Cidade atualizada',
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
                $deleted       = $this->repository->update(['active' => 'off'], $id);
                $neighborhoods = $this->neighborhoodRepository->where('city_id', $id)->get();

                if ($deleted) {
                    foreach ($neighborhoods as $neighborhood) {
                        $neighborhood->update(['active' => 'off']);
                    }
                }

                $response = [
                    'message' => 'Cidade deletada',
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
