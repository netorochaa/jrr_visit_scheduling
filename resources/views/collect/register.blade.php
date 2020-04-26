
<div class="card">
    {!! Form::open(['route' => 'collect.schedule', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            <div class="col-sm-2">
                @include('templates.components.input', ['label' => 'Data', 'col' => '12', 'input' => 'date', 'attributes' => ['onchange' => 'verificateDate()', 'require' => 'true', 'class' => 'form-control', 'id' => 'schedulingDate']])
            </div>
            <div class="col-sm-10">
                <label>Digite o bairro</label>
                <div clas="col-12 form-group" id="infoCollect">
                    <select name="infoCollect" id="infoCollectSel" class="form-control select2bs4" style="width: 100%;" disabled>
                        <option value="" selected></option>
                        @foreach ($collectAvailables_list->where('neighborhood_id', null) as $collect)
                            @foreach ($collector_list->find($collect->collector->id)->neighborhoods as $neighborhood)
                                <option value="{{ $collect->id }},{{ $neighborhood->id }}">
                                    {{ $collect->formatted_date }} - {{ $collect->hour }}, {{ $neighborhood->getNeighborhoodZone() }}, {{ $neighborhood->city->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Continuar', 'attributes' => ['class' => 'btn btn-outline-primary']])
        {{-- Ao cancelar deve-se deixar vago o horário --}}
        <button type="button" onclick="#" class="btn btn-outline-danger" >Cancelar</button>
    </div>
    {!! Form::close() !!}
</div>
