<div class="card">
    {!! Form::open(['route' => 'collect.reserve', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <h3 class="lead">{{ $neighborhood_model->name }}</h3>
        <div class="row">
            <div class="col-sm-2">
                @include('templates.components.input', ['label' => 'Data', 'col' => '12', 'input' => 'date', 'attributes' => ['onchange' => 'verificateDate()', 'require' => 'true', 'class' => 'form-control', 'id' => 'schedulingDate']])
            </div>
            <div class="col-sm-10">
                <label>Digite o bairro</label>
                <div clas="col-12 form-group" id="infoCollect">
                    <select name="infoCollect" id="infoCollectSel" class="form-control select2bs4" style="width: 100%;">
                        <option value="" selected></option>
                        @foreach ($collector_list as $collector)
                            @foreach ($collector->neighborhoods as $neighborhood)
                                @if($neighborhood->id == $neighborhood_model->id)  
                                    @foreach (explode(',', $collector->mondayToFriday) as $hour)
                                        <option value="{{ $neighborhood->id }}">
                                            {{ $hour }}, {{ $neighborhood->getNeighborhoodZone() }}, {{ $neighborhood->city->name }} - {{ $collector->name }}
                                        </option>
                                    @endforeach
                                @endif
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


