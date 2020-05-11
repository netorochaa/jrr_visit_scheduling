
<div class="card">
    {!! Form::open(['route' => 'cancellationtype.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
        <div class="card-body">
            <div class="row">
                @include('templates.components.input', ['label' => 'Nome',  'col' => '8', 'input' => 'name',  'attributes' => ['require' => 'true', 'class' => 'form-control']])
            </div>
        </div>
        <div class="card-footer">
            @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
        </div>
    {!! Form::close() !!}
</div>
