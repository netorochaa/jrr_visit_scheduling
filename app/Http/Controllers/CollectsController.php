<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CollectCreateRequest;
use App\Http\Requests\CollectUpdateRequest;
use App\Repositories\CollectRepository;
use App\Repositories\NeighborhoodRepository;
use App\Validators\CollectValidator;

class CollectsController extends Controller
{
    protected $repository, $neighborhoodRepository;
    protected $validator;

    public function __construct(CollectRepository $repository, CollectValidator $validator, NeighborhoodRepository $neighborhoodRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->neighborhoodRepository = $neighborhoodRepository;
    }

    public function index()
    {
        $collects  = $this->repository->all();
        $collectScheduling_list  = $this->repository->collects_list()->get();
        for($i = 0; $i < count($collectScheduling_list); $i++){
            if($collectScheduling_list[$i]->mondayToFriday != null) $collectScheduling_list[$i]->mondayToFriday = explode(",", $collectScheduling_list[$i]->mondayToFriday);
            if($collectScheduling_list[$i]->saturday != null) $collectScheduling_list[$i]->saturday = explode(",", $collectScheduling_list[$i]->saturday);
            if($collectScheduling_list[$i]->sunday != null) $collectScheduling_list[$i]->sunday = explode(",", $collectScheduling_list[$i]->sunday);
        }
        $neighborhoodCity_list = $this->neighborhoodRepository->neighborhoodsCities_list()->pluck('name', 'id');

        // dd($collects);

        return view('collect.index', [
            'namepage'      => 'Coletas',
            'threeview'     => null,
            'titlespage'    => ['Coletas'],
            'titlecard'     => 'Lista de coletas',
            'titlemodal'    => 'Agendar coleta',
            'add'           => true,
            //List for select
            'neighborhoodCity_list' => $neighborhoodCity_list,
            'collectScheduling_list' => $collectScheduling_list,
            //Info of entitie
            'table'               => $this->repository->getTable(),
            'thead_for_datatable' => ['Data', 'Hora', 'Tipo', 'Status', 'Pagamento', 'Troco', 'Endereço', 'Link', 'Obs. Coleta', 'Anexo', 'Cancelamento', 'Tipo'],
            'collects'            => $collects
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

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $collect = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $collect,
            ]);
        }

        return view('collects.show', compact('collect'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $collect = $this->repository->find($id);

        return view('collects.edit', compact('collect'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CollectUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
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
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Collect deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Collect deleted.');
    }
}
