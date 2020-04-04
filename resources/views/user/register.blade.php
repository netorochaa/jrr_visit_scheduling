
<div class="card">
    {!! Form::open(['route' => 'user.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
        <div class="card-body">
            <div class="row">
                @include('templates.components.input',    ['label' => 'Nome completo',  'col' => '8', 'input' => 'name',  'attributes' => ['require' => 'true', 'class' => 'form-control']])
                @include('templates.components.password', ['label' => 'Senha',          'col' => '4', 'input' => 'password',  'attributes' => ['require' => 'true', 'class' => 'form-control']])
                @include('templates.components.email',    ['label' => 'E-mail',         'col' => '8', 'input' => 'email', 'attributes' => ['require' => 'true', 'class' => 'form-control']])
            </div>
            <div class="row">
                <div class="col-sm-6">
                    @include('templates.components.select', ['label' => 'Tipo',    'col' => '12', 'select' => 'type',           'data' => $typeUsers_list, 'attributes' => ['require' => 'true', 'class' => 'form-control']])
                </div>    
                <div class="col-sm-6">
                    @include('templates.components.select', ['label' => 'Coletor', 'col' => '12', 'select' => 'collector_id',   'data' => $collector_list, 'attributes' => ['class' => 'form-control select2', 'style' => 'width: 100%']])
                </div>
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