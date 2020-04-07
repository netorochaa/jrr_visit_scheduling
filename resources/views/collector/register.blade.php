
<div class="card">
    {!! Form::open(['route' => 'collector.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            @include('templates.components.input', ['label' => 'Nome do coletador',                                'col' => '8', 'input' => 'name',             'attributes' => ['require' => 'true', 'class' => 'form-control']])
            @include('templates.components.input', ['label' => 'Link no mapa do início da atividade do coletador', 'col' => '12', 'input' => 'startingAddress', 'attributes' => ['class' => 'form-control']])
        </div>
        <div class="row">
            <div class="col-sm-6">
                @include('templates.components.timepicker', ['label' => 'Início das atividades',                     'col' => '12', 'input' => 'initialTimeCollect',  'attributes' => ['require' => 'true', 'onchange' => 'montaHorarios()', 'class' => 'form-control datetimepicker-input', 'data-target' => '#startTime', 'id' => 'inputStartTime'], 'datatargetdiv' => '#startTime', 'id' => 'startTime'])
                @include('templates.components.input',      ['label' => 'Intervalo entre as coletas (min.) [> 10] ', 'col' => '12', 'input' => 'collectionInterval',   'attributes' => ['id' => 'interval', 'onchange' => 'montaHorarios()', 'require' => 'true', 'class' => 'form-control', 'data-inputmask' => "'mask': '999'", 'data-mask', 'im-insert' => 'true']])
            </div>
            <div class="col-sm-6">
                @include('templates.components.timepicker', ['label' => 'Fim das atividades',   'col' => '12', 'input' => 'finalTimeCollect',               'attributes' => ['require' => 'true', 'onchange' => 'montaHorarios()', 'class' => 'form-control datetimepicker-input', 'data-target' => '#endTime', 'id' => 'inputEndTime'], 'datatargetdiv' => '#endTime', 'id' => 'endTime'])
                @include('templates.components.select',     ['label' => 'Colaborador',          'col' => '12', 'select' => 'user_id', 'data' => $user_list, 'attributes' => ['class' => 'form-control select2', 'style' => 'width: 100%;']])
            </div>
        </div>
        <div class="row">
            @include('templates.components.textarea',   ['label'=>'Horáriosdisponíveis', 'col'=>'12','input'=>'','attributes'=>['class'=>'form-control','id'=>'descriptionHour','readonly'=>'true','rows'=>'2']])
        </div>
        <div class="row">
            <div class="col-sm-6">
                @include('templates.components.checkbox', ['label' => 'Ativo', 'col' => '4', 'input' => 'active', 'checked' => 'true'])
            </div>
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Cadastrar', 'attributes' => ['class' => 'btn btn-outline-primary']])
    </div>
    {!! Form::close() !!}
</div>
