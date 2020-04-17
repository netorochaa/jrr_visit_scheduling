
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
                    <select name="infoCollect" id="infoCollectSelMondayToFriday" class="form-control select2bs4" style="width: 100%;" disabled="disabled" required>
                        <option value="" selected></option>
                        @foreach ($collectScheduling_list as $itemCollectShedulling)
                            @if ($itemCollectShedulling->mondayToFriday ?? null)
                                <optgroup label="{{ $itemCollectShedulling->cityFull }} - {{ $itemCollectShedulling->name }}">
                                    @for ($i = 0; $i < count($itemCollectShedulling->mondayToFriday); $i++)
                                        <?php $reserved = false; ?>
                                        <option
                                            @foreach ($collects as $itemCollect)
                                                @if ($itemCollect->hour         == $itemCollectShedulling->mondayToFriday[$i] &&
                                                    $itemCollect->collector_id  == $itemCollectShedulling->collector_id)
                                                    class="{{ $itemCollect->date }}"
                                                    <?php $reserved = true; ?>
                                                @endif
                                            @endforeach
                                                value="{{ $itemCollectShedulling->collector_id }},{{ $itemCollectShedulling->neighborhood_id }},{{ $itemCollectShedulling->mondayToFriday[$i] }}" <?php if($reserved) echo " disabled"; ?>>
                                                {{ $itemCollectShedulling->mondayToFriday[$i] }} - {{ $itemCollectShedulling->neighborhoodCity}} <?php if($reserved) echo " (Reservado)"; ?>
                                        </option>
                                    @endfor
                                </optgroup>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div clas="col-12 form-group" id="infoCollectSaturday" style="display: none;">
                    <select name="infoCollect" id="infoCollectSelSaturday" class="form-control select2bs4" style="width: 100%;" disabled="disabled" required>
                        <option value="" selected></option>
                        @foreach ($collectScheduling_list as $itemCollectShedulling)
                            @if ($itemCollectShedulling->saturday ?? null)
                                <optgroup label="{{ $itemCollectShedulling->cityFull }} - {{ $itemCollectShedulling->name }}">
                                    @for ($i = 0; $i < count($itemCollectShedulling->saturday); $i++)
                                        <?php $reserved = false; ?>
                                        <option
                                            @foreach ($collects as $itemCollect)
                                                @if ($itemCollect->hour         == $itemCollectShedulling->saturday[$i] &&
                                                    $itemCollect->collector_id  == $itemCollectShedulling->collector_id)
                                                    class="{{ $itemCollect->date }}"
                                                    <?php $reserved = true; ?>
                                                @endif
                                            @endforeach
                                                value="{{ $itemCollectShedulling->collector_id }},{{ $itemCollectShedulling->neighborhood_id }},{{ $itemCollectShedulling->saturday[$i] }}" <?php if($reserved) echo " disabled"; ?>>
                                                {{ $itemCollectShedulling->saturday[$i] }} - {{ $itemCollectShedulling->neighborhoodCity}} <?php if($reserved) echo " (Reservado)"; ?>
                                        </option>
                                    @endfor
                                </optgroup>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div clas="col-12 form-group" id="infoCollectSunday" style="display: none;">
                    <select name="infoCollect" id="infoCollectSelSunday" class="form-control select2bs4" style="width: 100%;" disabled="disabled" required>
                        <option value="" selected></option>
                        @foreach ($collectScheduling_list as $itemCollectShedulling)
                            @if ($itemCollectShedulling->sunday ?? null)
                                <optgroup label="{{ $itemCollectShedulling->cityFull }} - {{ $itemCollectShedulling->name }}">
                                    @for ($i = 0; $i < count($itemCollectShedulling->sunday); $i++)
                                        <option
                                            @foreach ($collects as $itemCollect)
                                                @if ($itemCollect->hour         == $itemCollectShedulling->sunday[$i] &&
                                                    $itemCollect->collector_id  == $itemCollectShedulling->collector_id)
                                                    class="{{ $itemCollect->date }}"
                                                @endif
                                            @endforeach
                                                value="{{ $itemCollectShedulling->collector_id }},{{ $itemCollectShedulling->neighborhood_id }},{{ $itemCollectShedulling->sunday[$i] }}">
                                                {{ $itemCollectShedulling->sunday[$i] }} - {{ $itemCollectShedulling->neighborhoodCity}}
                                        </option>
                                    @endfor
                                </optgroup>
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
