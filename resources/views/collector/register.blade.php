
<div class="card">
    {!! Form::open(['route' => 'collector.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            @include('templates.components.input', ['label' => 'Nome do coletador',                                'col' => '10', 'input' => 'name',             'attributes' => ['require' => 'true', 'class' => 'form-control']])
            @include('templates.components.input', ['label' => 'Link no mapa do início da atividade do coletador', 'col' => '12', 'input' => 'starting-address', 'attributes' => ['class' => 'form-control']])
        </div>
        <div class="row">
            <div class="col-sm-6">
                @include('templates.components.timepicker', ['label' => 'Início das atividades',                     'col' => '12', 'input' => 'initial-time-collect',  'attributes' => ['require' => 'true', 'onchange' => 'montaHorarios()', 'class' => 'form-control datetimepicker-input', 'data-target' => '#startTime', 'id' => 'inputStartTime'], 'datatargetdiv' => '#startTime', 'id' => 'startTime'])
                @include('templates.components.input',      ['label' => 'Intervalo entre as coletas (min.) [> 10] ', 'col' => '12', 'input' => 'collection-interval',   'attributes' => ['id' => 'interval', 'onchange' => 'montaHorarios()', 'require' => 'true', 'class' => 'form-control', 'data-inputmask' => "'mask': '99'", 'data-mask', 'im-insert' => 'true'], 'value' => '10'])
            </div>
            <div class="col-sm-6">
                @include('templates.components.timepicker', ['label' => 'Fim das atividades',   'col' => '12', 'input' => 'final-time-collect', 'attributes' => ['require' => 'true', 'onchange' => 'montaHorarios()', 'class' => 'form-control datetimepicker-input', 'data-target' => '#endTime', 'id' => 'inputEndTime'], 'datatargetdiv' => '#endTime', 'id' => 'endTime'])
                @include('templates.components.textarea',   ['label' => 'Horários disponíveis', 'col' => '12', 'input' => '',                   'attributes' => ['class' => 'form-control', 'id' => 'descriptionHour', 'readonly' => 'true', 'rows' => '3']])
            </div>
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