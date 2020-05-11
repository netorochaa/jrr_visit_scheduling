
<div class="card">
    @if ($city ?? null)
        {!! Form::model($city, ['route' => ['city.update', $city->id], 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @else
        {!! Form::open(['route' => 'city.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @endif
        <div class="card-body">
            <div class="row">
                @include('templates.components.input', ['label' => 'Nome da cidade',  'col' => '8', 'input' => 'name',  'attributes' => ['require' => 'true', 'class' => 'form-control']])
                @include('templates.components.input', ['label' => 'UF', 'col' => '4', 'input' => 'UF',  'attributes' => ['require' => 'true', 'class' => 'form-control']])
            </div>
        </div>
        <div class="card-footer">
            @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
        </div>
    {!! Form::close() !!}
</div>
