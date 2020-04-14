
<div class="card">
    {!! Form::open(['route' => 'collect.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            @include('templates.components.select', ['label' => 'Bairro', 'col' => '12', 'select' => 'neighborhood_id', 'data' => $neighborhoodCity_list, 'attributes' => ['id' => 'neighborhoodCity', 'require' => 'true', 'class' => 'form-control select2bs4', 'style' => 'width: 100%;']])
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
    </div>
    {!! Form::close() !!}
</div>
