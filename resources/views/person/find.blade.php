<div class="card">
    <div class="card-body">
    {!! Form::open(['route' => 'person.collect.attach', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
        @include('templates.components.hidden', ['hidden' => 'idCollect', 'value' => $collect->id])
        <div class="row">
            @include('templates.components.select', ['label' => 'Pesquisar por',    'col' => '4',  'select' => '', 'attributes' => ['id' => 'selTypeSearch', 'class' => 'form-control'], 'data' => $search_list])
            @include('templates.components.input',  ['label' => 'Digite a procura', 'col' => '8', 'input'  => '', 'attributes' => ['id' => 'search', 'class' => 'form-control']])
        </div>
        <div class="row">
            <small><p class="text-muted" id="status-find-client"></p></small>
        </div>
        <div class="row">
            <div class="col-sm-12">
                @include('templates.components.select', ['label' => 'Selecione um paciente', 'col' => '', 'select' => 'registeredPatient', 'data' => [], 'attributes' => ['id' => 'registeredPatientSel', 'class' => 'form-control select2bs4']])
            </div>
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Adicionar', 'attributes' => ['class' => 'btn btn-outline-info']])
    </div>
    {!! Form::close() !!}
</div>
