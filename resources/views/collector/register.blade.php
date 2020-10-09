
<div class="card">
    @if ($collector ?? null)
        {!! Form::model($collector, ['route' => ['collector.update', $collector->id], 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @else
        {!! Form::open(['route' => 'collector.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @endif
    <div class="card-body">
        <div class="row">
            @include('templates.components.input', ['label' => 'Nome do coletador',                                'col' => '8', 'input' => 'name',             'attributes' => ['required' => 'true', 'class' => 'form-control']])
            @include('templates.components.input', ['label' => 'Link no mapa do início da atividade do coletador', 'col' => '6', 'input' => 'startingAddress', 'attributes' => ['class' => 'form-control']])
            @include('templates.components.select', ['label' => 'Colaborador', 'col' => '6', 'select' => 'user_id', 'data' => $user_list, 'attributes' => ['class' => 'form-control select2', 'style' => 'width: 100%;']])
        </div>
        <div class="row">
            <div class="col-sm-6">
                @include('templates.components.checkbox', ['label' => 'Disponível no site', 'col' => '4', 'input' => 'showInSite'])
            </div>
        </div>
        <h3>Horários</h3>
        <div class="row">
            @include('templates.components.input',  ['label' => 'Data inicial das atividades', 'col' => '3', 'input' => 'date_start', 'attributes' => ['id' => ($collector ?? null ? '' : 'dateStart'),'require' => 'true', 'class' => 'form-control', 'autocomplete' => 'off', 'disabled' => ($collector ?? null ? true : false)]])
            @include('templates.components.input',  ['label' => 'Data de início da alteração', 'col' => '3', 'input' => 'date_start_last_modify', 'value' => '','attributes' => ['id' => 'dateStart','require' => 'true', 'class' => 'form-control', 'autocomplete' => 'off', 'disabled' => ($collector ?? null ? false : true)]])
            @if($collector ?? null)
                <div class="col-sm-6">
                    <p class="lead text-muted">É importante haver planejamento no momento de editar horários dos coletadores. Procure realizar alterações para datas que não hajam coletas confirmadas, pois poderá afetar a rota do coletador. COLETAS AGENDADAS NÃO TERÃO HORÁRIOS MODIFICADOS.</p>
                </div>
                @include('templates.components.checkbox', ['label' => 'Não atualizar horários', 'col' => '12', 'input' => 'not_update_hours', 'attributes' => ['id' => 'check_date_last', 'class' => 'form-check-input', 'onchange' => 'changeDateLast(this)']])
            @endif
            @include('templates.components.select', ['label' => 'Segundas/sextas', 'listExists' => $collector->mondayToFriday ?? null, 'col' => '12', 'selected' => $collector->mondayToFriday ?? null, 'select' => 'mondayToFriday[]', 'data' => $schedules, 'attributes' => ['id' => 'select_mondayToFriday', 'class' => 'form-control select2bs4', 'multiple' => 'multiple', 'style' => 'width: 100%;']])
            @include('templates.components.select', ['label' => 'Sábados', 'listExists' =>  $collector->saturday ?? null, 'col' => '12', 'select' => 'saturday[]', 'data' => $schedules, 'attributes' => ['id' => 'select_saturday','class' => 'form-control select2bs4', 'multiple' => 'multiple', 'style' => 'width: 100%;']])
            @include('templates.components.select', ['label' => 'Domingos', 'listExists' => $collector->sunday ?? null, 'col' => '12', 'select' => 'sunday[]', 'data' => $schedules, 'attributes' => ['id' => 'select_sunday','class' => 'form-control select2bs4', 'multiple' => 'multiple', 'style' => 'width: 100%;']])
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
    </div>
    {!! Form::close() !!}
</div>
