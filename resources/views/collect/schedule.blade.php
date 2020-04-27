{!! Form::model($collect, ['route' => ['collect.update', $collect->id], 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card">
        <div class="card-body">
            <div class="row">
                @include('templates.components.input',  ['label' => 'Data',   'col' => '2', 'input'  => 'date',         'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true']])
                @include('templates.components.input',  ['label' => 'Hora',   'col' => '2', 'input'  => 'hour',         'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true']])
                @include('templates.components.input',  ['label' => 'Bairro', 'col' => '6', 'input'  => 'neighborhood', 'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true'], 'value' => $collect->neighborhood->getNeighborhoodZone()])
                @include('templates.components.select', ['label' => 'Tipo',   'col' => '2', 'select' => 'collectType',  'attributes' => ['class' => 'form-control'], 'data' => $collectType_list,])
            </div>
            <h3>Paciente(s)
                <button type="button" data-toggle="modal" data-target="#modal-xl" class="btn btn-outline-success"><i class="fas fa-plus"></i> Novo</button> 
                <button type="button" data-toggle="modal" data-target="#modal-lg" class="btn btn-outline-success"><i class="fas fa-plus"></i> Já possui cadastro</button> 
            </h3>
            <hr>
            <div class="row">
                <div class="col-12">
                    @include('collect.person.list')
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
        </div>
    </div>
{!! Form::close() !!}
