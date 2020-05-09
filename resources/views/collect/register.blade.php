<div class="card">
    {!! Form::open(['route' => 'collect.reserve', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <h3 class="lead">{{ $neighborhood_model->name }}<small><p class="text-muted" id="describe-feedback"></p></small></h3>
        <div class="row">
            <div class="col-sm-2">
                @include('templates.components.input', ['label' => 'Data', 'col' => '12', 'input' => 'date', 'attributes' => ['id' => 'schedulingDate', 'require' => 'true', 'class' => 'form-control', 'autocomplete' => 'off']])
                @include('templates.components.hidden', ['hidden' => 'neighborhood_id', 'value' => $neighborhood_model->id, 'attributes' => ['id' => 'inputNeighborhood']])
            </div>
            <div class="col-sm-10">
                @include('templates.components.select', ['label' => 'Selecione um horário e o tipo de coletador', 'col' => '', 'select' => 'infoCollect', 'data' => [], 'attributes' => ['id' => 'infoCollectSel', 'class' => 'form-control select2bs4']])
            </div>
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Continuar', 'attributes' => ['class' => 'btn btn-outline-primary']])
        {{-- Ao cancelar deve-se deixar vago o horário --}}
    </div>
    {!! Form::close() !!}
</div>


