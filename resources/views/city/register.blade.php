
<div class="card">
    {!! Form::open(['route' => 'city.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
        <div class="card-body">
            <div class="row">
                @include('templates.components.input', ['label' => 'Nome da cidade',  'col' => '8', 'input' => 'name',  'attributes' => ['require' => 'true', 'class' => 'form-control']])
                @include('templates.components.input', ['label' => 'UF', 'col' => '4', 'input' => 'UF',  'attributes' => ['require' => 'true', 'class' => 'form-control']])
            </div>
            <div class="row">
                <div class="col-sm-6">
                    @include('templates.components.checkbox', ['label' => 'Ativo', 'col' => '4', 'input' => 'active', 'checked' => 'true'])
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('templates.components.submit', ['input' => 'Cadastrar', 'attributes' => ['class' => 'btn btn-outline-primary']])
        </div>
    {!! Form::close() !!}
</div>