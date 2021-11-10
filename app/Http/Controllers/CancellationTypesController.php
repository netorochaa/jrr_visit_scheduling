<?php

namespace App\Http\Controllers;

use App\Entities\Util;
use App\Http\Requests\{CancellationTypeCreateRequest, CancellationTypeUpdateRequest};
use App\Repositories\CancellationTypeRepository;
use App\Validators\CancellationTypeValidator;
use Auth;
use Exception;
use Prettus\Validator\Contracts\ValidatorInterface;

date_default_timezone_set('America/Recife');

class CancellationTypesController extends Controller
{
    protected $repository;

    protected $validator;

    public function __construct(CancellationTypeRepository $repository, CancellationTypeValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    public function index()
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        if (Auth::user()->type > 3) {
            $cancellationTypes = $this->repository->where('active', 'on')->get();

            return view('cancellationtype.index', [
                'namepage'      => 'Cancelamento de coleta',
                'threeview'     => 'Cadastros',
                'titlespage'    => ['Cadastro de cancelamento de coleta'],
                'titlecard'     => 'Lista dos cancelamentos de coleta',
                'titlemodal'    => 'Cadastrar cancelamento de coleta',
                'add'           => true,
                //List of entitie
                'table'               => $this->repository->getTable(),
                'thead_for_datatable' => ['Nome', 'Criado'],
                'cancellationTypes'   => $cancellationTypes,
            ]);
        }

        return redirect()->route('auth.home')->withErrors(['Você não tem permissão para esta ação, entre em contato com seu superior.']);
    }

    public function store(CancellationTypeCreateRequest $request)
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
                    'message' => 'Cancelamento cadastrado',
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

        return redirect()->route('cancellationtype.index');
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            session()->flash('return');

            return view('auth.login');
        }
        
        try {
            if (Auth::user()->type > 3) {
                $deleted  = $this->repository->update(['active' => 'off'], $id);
                $response = [
                    'message' => 'Cancelamento deletado',
                    'type'    => 'info',
                ];
            } else {
                return redirect()->route('auth.home')->withErrors(['Você não tem permissão para executar esta ação, entre em contato com seu superior.']);
            }
        } catch (Exception $e) {
            $response = [
                'message' => Util::getException($e),
                'type'    => 'error',
            ];
        }
        session()->flash('return', $response);

        return redirect()->route('cancellationtype.index');
    }
}
