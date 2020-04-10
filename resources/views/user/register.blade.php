
<div class="card">
    {{-- {{ dd($user) }}  --}}
    @if ($user ?? null)
        {!! Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @else
        {!! Form::open(['route' => 'user.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @endif
        <div class="card-body">
            <div class="row">
                @include('templates.components.input',    ['label' => 'Nome',   'col' => '8', 'input' => 'name',     'attributes' => ['require' => 'true', 'class' => 'form-control']])
                @include('templates.components.password', ['label' => 'Senha',  'col' => '4', 'input' => 'password', 'attributes' => ['require' => 'true', 'class' => 'form-control']])
            </div>
            <div class="row">
                @include('templates.components.email',  ['label' => 'E-mail', 'col' => '6', 'input' => 'email',    'attributes' => ['require' => 'true', 'class' => 'form-control']])
                @include('templates.components.select', ['label' => 'Tipo',   'col' => '6', 'select' => 'type', 'data' => $typeUsers_list, 'attributes' => ['onchange' => 'activeCollector()', 'id' => 'typeUsers', 'require' => 'true', 'class' => 'form-control']])
            </div>
            <div class="row">
                <div class="col-sm-6">
                    @include('templates.components.checkbox', ['label' => 'Ativo', 'col' => '4', 'input' => 'active', 'checked' => 'true'])
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
        </div>
    {!! Form::close() !!}
</div>
