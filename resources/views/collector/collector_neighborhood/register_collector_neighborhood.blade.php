
<div class="card">
    {!! Form::open(['route' => ['collector.neighborhoods.store', $collector->id], 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            @include('templates.components.select', ['label' => 'Seleciona os bairros', 'col' => '12', 'select' => 'neighborhood_id[]', 'data' => $neighborhoods, 'attributes' => ['class' => 'form-control select2bs4', 'multiple' => 'multiple', 'style' => 'width: 100%;']])
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Cadastrar', 'attributes' => ['class' => 'btn btn-outline-primary']])
    </div>
    {!! Form::close() !!}
</div>
