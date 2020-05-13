{!! Form::model($collect, ['route' => ['collect.update', $collect->id], 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card">
        <div class="card-body">
            <h4 class="lead">Nº {{ $collect->id }} | Status: <b>{{ $collect->formatted_status }}</b> | Coletador: {{ $collect->collector->name }}
            </h4>
            <div class="row">
                @include('templates.components.input',  ['label' => 'Data',   'col' => '4', 'input'  => 'date',         'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true'], 'value' => $collect->formatted_date])
                @include('templates.components.input',  ['label' => 'Hora',   'col' => '2', 'input'  => 'hour',         'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true']])
                @include('templates.components.input',  ['label' => 'Bairro', 'col' => '4', 'input'  => 'neighborhood', 'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true'], 'value' => $collect->neighborhood->getNeighborhoodZone()])
                @include('templates.components.label',  ['label' => 'Taxa',   'col' => '2', 'input'  => '',             'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true'], 'text' => "R$ " . $collect->neighborhood->displacementRate])
            </div>
            <p class="text-muted">Data e hora reservada por 10 minutos, preencha os dados abaixo e salve para confirmar sua reserva.</p>
            <hr>
            <div class="row">
                @include('templates.components.input', ['label' => 'CEP',       'col' => '2', 'input'  => 'cep',            'value' => $collect->cep ?? null,           'attributes' => ['id' => 'cep', 'size' => '10', 'maxlength' => '9', 'class' => 'form-control', 'data-inputmask' => "'mask': '99999-999'", 'data-cep', 'im-insert' => 'true']])
                @include('templates.components.input', ['label' => 'Endereço',  'col' => '8', 'input'  => 'address',        'value' => $collect->address ?? null,       'attributes' => ['required' => 'true', 'id' => 'rua', 'class' => 'form-control', 'placeholder' => 'rua, conjunto, avenida, favela...']])
                @include('templates.components.input', ['label' => 'Nº',        'col' => '2', 'input'  => 'numberAddress',  'value' => $collect->numberAddress ?? null, 'attributes' => ['required' => 'true', 'class' => 'form-control']])
            </div>
            <div class="row">
                @include('templates.components.input', ['label' => 'Complemento',         'col' => '6', 'input' => 'complementAddress', 'value' => $collect->complementAddress ?? null, 'attributes' => ['required' => 'true', 'class' => 'form-control', 'placeholder' => 'bloco, apartamento, casa...']])
                @include('templates.components.input', ['label' => 'Ponto de referência', 'col' => '6', 'input' => 'referenceAddress',  'value' => $collect->referenceAddress ?? null,  'attributes' => ['required' => 'true', 'class' => 'form-control']])
            </div>
            <hr>
            <h5 class="lead">Paciente(s)
                <button type="button" data-toggle="modal" data-target="#modal-xl" class="btn btn-outline-success"><i class="fas fa-plus"></i></button> 
            </h5>
            <div class="row">
                <div class="col-12">
                    @include('collect.person.list')
                </div>
            </div>
            <hr>
            <div class="row">
                @include('templates.components.label',  ['label' => 'Valor (' . $quant . ' pacientes)', 'col' => '2', 'input'  => '',                                                               'attributes' => ['class' => 'form-control'], 'text' => $price, 'id' => 'labelValue'])
                @include('templates.components.select', ['label' => 'Pagamento',                        'col' => '4', 'select' => 'payment',        'selected' => $collect->payment,                'attributes' => ['id' => 'selPayament', 'class' => 'form-control', 'onchange' => 'changeAuthUser()'], 'data' => $payment_list])
                @include('templates.components.input',  ['label' => 'Troco',                            'col' => '2', 'input'  => 'changePayment',  'value' => $collect->changePayment ?? null,     'attributes' => ['id' => 'changePay', 'class' => 'form-control', 'data-inputmask' => "'mask': '99.99'", 'data-mask', 'im-insert' => 'true']])
            </div>            
            <hr>
            <div class="row">
                @include('templates.components.textarea', ['label' => 'Observações', 'col' => '12', 'input' => 'observationCollect', 'value' => $collect->observationCollect ?? null, 'attributes' => ['class' => 'form-control', 'rows' => 2]])
            </div>
            <hr>
            <div class="row">
                @include('templates.components.file', ['label' => 'Anexos', 'col' => '6', 'input' => 'attachment', 'attributes' => ['class' => 'form-control', 'multiple' => 'true', 'disabled']])
            </div>
        </div>
        <div class="card-footer">
            @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['id' => 'submitSchedule', 'class' => 'btn btn-outline-primary']])
        </div>
    </div>
{!! Form::close() !!}
