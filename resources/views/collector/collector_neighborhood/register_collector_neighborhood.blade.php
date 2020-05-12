
<div class="card">
    @if ($user ?? null)
        {!! Form::model($collector, ['route' => ['collector.neighborhoods.attach', $collector->id], 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @else
        {!! Form::open(['route' => ['collector.neighborhoods.attach', $collector->id], 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @endiF    
    <div class="card-body">
        <div class="row">
            @include('templates.components.select', ['label' => 'Seleciona os bairros', 'col' => '12', 'select' => 'neighborhood_id[]', 'data' => $neighborhoods, 'attributes' => ['class' => 'form-control select2bs4', 'multiple' => 'multiple', 'style' => 'width: 100%;']])
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
    </div>
    {!! Form::close() !!}
</div>
