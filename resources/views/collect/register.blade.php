<div class="card">
    {!! Form::open(['route' => !$transfer ? 'collect.reserve' : ['collect.transfer', $collect->id], 'method' => !$transfer ? 'post' : 'get', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <h3 class="lead">{{ $neighborhood_model->name }}<small><p class="text-muted" id="describe-feedback"></p></small></h3>
        @if($collect->collect_old ?? null)
            <div class="row">
                <p class="lead text-danger" style="text-alig: center">
                    <small>
                        Esta coleta teve o horário alterado, com isso NÃO É POSSÍVEL transferi-la.<br>
                    </small>
                </p>
            </div>
        @else
            <div class="row">
                <div class="col-sm-2">
                    @include('templates.components.input', ['label' => 'Data', 'col' => '12', 'input' => 'date', 'attributes' => ['id' => 'schedulingDate', 'require' => 'true', 'class' => 'form-control', 'autocomplete' => 'off']])
                    @include('templates.components.hidden', ['hidden' => 'neighborhood_id', 'value' => $neighborhood_model->id, 'attributes' => ['id' => 'inputNeighborhood']])
                </div>
                <div class="col-sm-10">
                    @include('templates.components.select', ['label' => 'Selecione um horário e o tipo de coletador', 'col' => '', 'select' => 'infoCollect', 'data' => [], 'attributes' => ['id' => 'infoCollectSel', 'class' => 'form-control select2bs4']])
                </div>
            </div>
        @endif
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Continuar', 'attributes' => ['id' => 'submitSelectNeighborhood', 'class' => 'btn btn-outline-primary', 'disabled' => 'true']])
        {{-- Ao cancelar deve-se deixar vago o horário --}}
    </div>
    {!! Form::close() !!}
</div>
