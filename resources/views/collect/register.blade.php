
<div class="card">
    {!! Form::open(['route' => 'collect.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            <div class="col-sm-2">
                @include('templates.components.input', ['label' => 'Data', 'col' => '12', 'input' => 'date', 'incon' => 'calendar-alt', 'attributes' => ['onchange' => 'verificateDate()', 'require' => 'true', 'class' => 'form-control', 'id' => 'schedulingDate']])
            </div>
            <div class="col-sm-10">
                <div clas="col-12 form-group">
                    <label>Horário/Bairro</label>
                    <select name="infoCollect" id="infoCollect" class="form-control select2bs4" style="width: 100%" required>
                        @foreach ($collectScheduling_list as $itemCollect)
                            @if ($itemCollect->mondayToFriday ?? null)
                                @for ($i = 0; $i < count($itemCollect->mondayToFriday); $i++)
                                    <option value="{{ $itemCollect->collector_id }},{{ $itemCollect->neighborhood_id }},{{ $itemCollect->mondayToFriday[$i] }}"> {{ $itemCollect->mondayToFriday[$i] }} - {{ $itemCollect->neighborhoodCity}}, {{ $itemCollect->name}}</option>
                                @endfor
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Continuar', 'attributes' => ['class' => 'btn btn-outline-primary']])
        <button type="button" onclick="location.href='{{ route('collect.index') }}'" class="btn btn-outline-danger" >Cancelar</button>
    </div>
    {!! Form::close() !!}
</div>
