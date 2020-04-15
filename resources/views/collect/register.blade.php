
<div class="card">
    {!! Form::open(['route' => 'collect.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            @include('templates.components.select', ['label' => 'Horário/Bairro', 'col' => '12', 'select' => 'neighborhood_id', 'data' => $neighborhoodCity_list, 'attributes' => ['id' => 'neighborhoodCity', 'required' => 'true', 'class' => 'form-control select2bs4', 'style' => 'width: 100%;']])
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Continuar', 'attributes' => ['class' => 'btn btn-outline-primary']])
        <button type="button" onclick="location.href='{{ route('collect.index') }}'" class="btn btn-outline-danger" >Cancelar</button>
    </div>
    {!! Form::close() !!}
</div>
