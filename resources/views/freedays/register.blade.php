
<div class="card">
    {!! Form::open(['route' => 'freedays.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            @include('templates.components.input',  ['label' => 'Nome', 'col' => '8', 'input' => 'name', 'attributes' => ['require' => 'true', 'class' => 'form-control']])
            @include('templates.components.select', ['label' => 'Tipo', 'col' => '4', 'select' => 'type', 'data' => $type_list, 'attributes' => ['require' => 'true', 'class' => 'form-control']])
        </div>
        <div class="row">
            @include('templates.components.select', ['label' => 'Coletadores', 'col' => '12', 'select' => 'collector_id[]', 'data' => $collectors_list, 'attributes' => ['class' => 'form-control select2bs4', 'id' => 'selectCollector', 'multiple' => 'multiple', 'style' => 'width: 100%;']])
        </div>
        <div class="row">
            @include('templates.components.input', ['label' => 'Período', 'col' => '12', 'input' => 'dateRange', 'incon' => 'calendar-alt', 'attributes' => ['require' => 'true', 'class' => 'form-control']])
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
    </div>
    {!! Form::close() !!}
</div>
