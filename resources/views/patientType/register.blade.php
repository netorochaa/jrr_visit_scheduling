
<div class="card">
    {!! Form::open(['route' => 'patienttype.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
        <div class="card-body">
            <div class="row">
                @include('templates.components.input', ['label' => 'Nome',  'col' => '8', 'input' => 'name', 'attributes' => ['require' => 'true', 'class' => 'form-control']])
            </div>
            <div class="row">
                <div class="col-sm-6">
                    @include('templates.components.checkbox', ['label' => 'Precisa de reponsável', 'col' => '4', 'input' => 'needResponsible'])
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
        </div>
    {!! Form::close() !!}
</div>
