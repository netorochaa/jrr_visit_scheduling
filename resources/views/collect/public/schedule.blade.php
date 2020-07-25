{!! Form::model($collect, ['route' => ['public.update', $collect->id], 'method' => 'put', 'enctype' => 'multipart/form-data',  'role' => 'form', 'class' => 'form-horizontal', 'id' => 'form_schedule']) !!}
    <div class="card">
        <div class="card-body">
            <h4 class="lead">Nº {{ $collect->id }} |
            Status: <b>{{ $collect->formatted_status }}</b> |
                            Coletador: {{ $collect->collector->name }}
            </h4>
            <div class="row">
                @include('templates.components.input',  ['label' => 'Data',   'col' => '2', 'input'  => 'date',         'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true'], 'value' => $collect->formatted_date])
                @include('templates.components.input',  ['label' => 'Hora',   'col' => '2', 'input'  => 'hour',         'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true']])
                @include('templates.components.input',  ['label' => 'Bairro', 'col' => '4', 'input'  => 'neighborhood', 'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true'], 'value' => $collect->neighborhood->getNeighborhoodZone()])
                @include('templates.components.label',  ['label' => 'Taxa para este bairro',   'col' => '4', 'input'  => '', 'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true'], 'text' => "R$ " . $collect->neighborhood->displacementRate])
            </div>
            <p class="text-muted">Data e horário reservados por 10 minutos, preencha os dados abaixo e salve para confirmar sua solicitação.</p>
            <hr>
            <h5 class="lead">Paciente(s)
                @if(count($collect->people) < 2)
                    <button type="button" data-toggle="modal" data-target="#modal-xl" class="btn btn-outline-success"><i class="fas fa-plus"></i></button>
                @else
                    <label> - Limite de 2 (dois) pacientes por agendamento</label>
                @endif
            </h5>
            <div class="row">
                <div class="col-12">
                    @include('person.list')
                </div>
            </div>
            <hr>
            <div class="row">
                @include('templates.components.input', ['label' => 'CEP',       'col' => '2', 'input'  => 'cep',            'value' => $collect->cep ?? null,           'attributes' => ['id' => 'cep', 'size' => '10', 'maxlength' => '9', 'class' => 'form-control', 'data-inputmask' => "'mask': '99999-999'", 'data-cep', 'im-insert' => 'true']])
                @include('templates.components.input', ['label' => 'Endereço',  'col' => '6', 'input'  => 'address',        'value' => $collect->address ?? null,       'attributes' => ['required' => 'true', 'id' => 'rua', 'class' => 'form-control', 'placeholder' => 'rua, conjunto, avenida, favela...', 'maxlength' => 140]])
                @include('templates.components.input', ['label' => 'Bairro',    'col' => '2', 'input'  => '',                                                           'attributes' => ['id' => 'bairro', 'class' => 'form-control', 'disabled' => 'true']])
                @include('templates.components.input', ['label' => 'Nº',        'col' => '2', 'input'  => 'numberAddress',  'value' => $collect->numberAddress ?? null, 'attributes' => ['required' => 'true', 'class' => 'form-control', 'maxlength' => 14]])
            </div>
            <div class="row">
                @include('templates.components.input', ['label' => 'Complemento',         'col' => '6', 'input' => 'complementAddress', 'value' => $collect->complementAddress ?? null, 'attributes' => ['required' => 'true', 'class' => 'form-control', 'placeholder' => 'bloco, apartamento, casa...', 'maxlength' => 45]])
                @include('templates.components.input', ['label' => 'Ponto de referência', 'col' => '6', 'input' => 'referenceAddress',  'value' => $collect->referenceAddress ?? null,  'attributes' => ['required' => 'true', 'class' => 'form-control', 'maxlength' => 140]])
            </div>
            <hr>
            <div class="row">
                @include('templates.components.label',  ['label' => 'Valor taxa',   'col' => '2', 'input'  => '',                                                               'attributes' => ['class' => 'form-control'], 'text' => $price, 'id' => 'labelValue'])
                @include('templates.components.select', ['label' => 'Pagamento',    'col' => '4', 'select' => 'payment',        'selected' => $collect->payment,                'attributes' => ['id' => 'selPayament', 'class' => 'form-control', 'onchange' => 'changeAuthUser()'], 'data' => $payment_list])
                @include('templates.components.input',  ['label' => 'Troco',        'col' => '2', 'input'  => 'changePayment',  'value' => $collect->changePayment ?? null,     'attributes' => ['id' => 'changePay', 'class' => 'form-control', 'data-inputmask' => "'mask': '99.99'", 'data-mask', 'im-insert' => 'true']])
            </div>
            <hr>
            <div class="row">
                @include('templates.components.textarea', ['label' => 'Observações', 'col' => '12', 'input' => 'observationCollect', 'value' => $collect->observationCollect ?? null, 'attributes' => ['class' => 'form-control', 'rows' => 2, 'maxlength' => 500]])
            </div>
            <hr>
            <div class="row">
                @include('templates.components.file', ['label' => 'Anexos', 'col' => '12', 'input' => 'attachment[]', 'attributes' => ['class' => 'form-control', 'multiple' => 'true', 'accept' => 'image/png, image/jpeg, application/pdf']])
                <p class="text-muted">Arquivos aceitáveis: Imagens (jpg, jpeg e png) e PDF</p>
            </div>
        </div>
        <div class="card-footer">
            @include('templates.components.submit', ['input' => 'Enviar', 'attributes' => ['id' => 'submitSchedule', 'class' => 'btn btn-outline-primary']])
            <button type="button" onclick="location.href='{{ route('collect.public.cancellation', $collect->id) }}'" id="cancelSchedule" class="btn btn-danger float-right">Cancelar agendamento</button>
        </div>
    </div>
{!! Form::close() !!}
