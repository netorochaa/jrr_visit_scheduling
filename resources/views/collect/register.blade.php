
<div class="card">
    {!! Form::open(['route' => 'collect.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            <div class="col-sm-2">
                @include('templates.components.input', ['label' => 'Data', 'col' => '12', 'input' => 'date', 'incon' => 'calendar-alt', 'attributes' => ['onchange' => 'verificateDate()', 'require' => 'true', 'class' => 'form-control', 'id' => 'schedulingDate']])
            </div>
            <div class="col-sm-10">
                <label>Horário/Bairro - <span id="DayOfWeek"></span></label>
                <div clas="col-12 form-group" id="infoCollectMondayToFriday" style="display: none;">
                    <select name="infoCollect" id="infoCollectSelMondayToFriday" class="form-control select2bs4" style="width: 100%;" disabled="disabled" required=>
                        <option value="" selected></option>
                        @foreach ($collectScheduling_list as $itemCollect)
                            @if ($itemCollect->mondayToFriday ?? null)
                                @for ($i = 0; $i < count($itemCollect->mondayToFriday); $i++)
                                    <option value="{{ $itemCollect->collector_id }},{{ $itemCollect->neighborhood_id }},{{ $itemCollect->mondayToFriday[$i] }}"> {{ $itemCollect->mondayToFriday[$i] }} - {{ $itemCollect->neighborhoodCity}}, {{ $itemCollect->name}}</option>
                                @endfor
                            @endif
                        @endforeach
                    </select>
                </div>
                <div clas="col-12 form-group" id="infoCollectSaturday" style="display: none;">
                    <select name="infoCollect" id="infoCollectSelSaturday" class="form-control select2bs4" style="width: 100%" disabled="disabled" required>
                        <option value="" selected></option>
                        @foreach ($collectScheduling_list as $itemCollect)
                            @if ($itemCollect->saturday ?? null)
                                @for ($i = 0; $i < count($itemCollect->saturday); $i++)
                                    <option value="{{ $itemCollect->collector_id }},{{ $itemCollect->neighborhood_id }},{{ $itemCollect->saturday[$i] }}"> {{ $itemCollect->saturday[$i] }} - {{ $itemCollect->neighborhoodCity}}, {{ $itemCollect->name}}</option>
                                @endfor
                            @endif
                        @endforeach
                    </select>
                </div>
                <div clas="col-12 form-group" id="infoCollectSunday" style="display: none;">
                    <select name="infoCollect" id="infoCollectSelSunday" class="form-control select2bs4" style="width: 100%" disabled="disabled" required>
                        <option value="" selected></option>
                        @foreach ($collectScheduling_list as $itemCollect)
                            @if ($itemCollect->sunday ?? null)
                                @for ($i = 0; $i < count($itemCollect->sunday); $i++)
                                    <option value="{{ $itemCollect->collector_id }},{{ $itemCollect->neighborhood_id }},{{ $itemCollect->sunday[$i] }}"> {{ $itemCollect->sunday[$i] }} - {{ $itemCollect->neighborhoodCity}}, {{ $itemCollect->name}}</option>
                                @endfor
                            @endif
                        @endforeach
                    </select>
                </div>
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
