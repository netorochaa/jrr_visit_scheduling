<div class="card">
    {!! Form::open(['route' => $report == 'cash' ? 'report.cash' : 'report.graphic', 'method' => 'get', 'role' => 'form', 'class' => 'form-horizontal']) !!}
        <div class="card-body">
            <div class="row">
                @include('templates.components.input', ['label' => 'Período', 'col' => '4', 'input' => 'dateRange_filter', 'value' => $period_get ?? null, 'attributes' => ['require' => 'true', 'class' => 'form-control'], 'form' => 'form-group'])
                @if($report == 'cash')
                    @include('templates.components.select', ['label' => 'Coletador', 'col' => '4', 'select' => 'collector_filter', 'selected' => $collector_selected ?? null, 'data' => ['all' => 'Todos', 'Selecione' => $collector_list], 'attributes' => ['require' => 'true', 'class' => 'form-control']])
                @endif
            </div>
        </div>
        <div class="card-footer">
            @include('templates.components.submit', ['input' => 'Filtrar', 'attributes' => ['class' => 'btn btn-outline-secondary']])
        </div>
    {!! Form::close() !!}
</div>
