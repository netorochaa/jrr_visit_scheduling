<div class="card">
    @if ($collect ?? null)
        {!! Form::model($collect, ['route' => ['collect.update', $collect->id], 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            @include('templates.components.input', ['label' => 'Nome do coletador',                                'col' => '8', 'input' => 'name',            'attributes' => ['required' => 'true', 'class' => 'form-control']])
            @include('templates.components.input', ['label' => 'Link no mapa do início da atividade do coletador', 'col' => '6', 'input' => 'startingAddress', 'attributes' => ['class' => 'form-control']])
            @include('templates.components.select', ['label' => 'Colaborador', 'col' => '6', 'select' => 'user_id', 'data' => $user_list, 'attributes' => ['class' => 'form-control select2', 'style' => 'width: 100%;']])
        </div>
        <h3>Horários</h3>
        <div class="row">
            @include('templates.components.select', ['label' => 'Segundas/sextas', 'listExists' => $collector->mondayToFriday ?? null, 'col' => '12', 'select' => 'mondayToFriday[]', 'data' => $schedules, 'attributes' => ['class' => 'form-control select2bs4', 'multiple' => 'multiple', 'style' => 'width: 100%;']])
            @include('templates.components.select', ['label' => 'Sábados', 'listExists' =>  $collector->saturday ?? null, 'col' => '12', 'select' => 'saturday[]', 'data' => $schedules, 'attributes' => ['class' => 'form-control select2bs4', 'multiple' => 'multiple', 'style' => 'width: 100%;']])
            @include('templates.components.select', ['label' => 'Domingos', 'listExists' => $collector->sunday ?? null, 'col' => '12', 'select' => 'sunday[]', 'data' => $schedules, 'attributes' => ['class' => 'form-control select2bs4', 'multiple' => 'multiple', 'style' => 'width: 100%;']])
        </div>
        <div class="row">
            <div class="col-sm-6">
                @include('templates.components.checkbox', ['label' => 'Ativo', 'col' => '4', 'input' => 'active', 'checked' => 'true'])
            </div>
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
    </div>
    {!! Form::close() !!}
</div>
