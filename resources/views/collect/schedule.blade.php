@if ($collect ?? null)
    {!! Form::model($collect, ['route' => ['collect.update', $collect->id], 'method' => 'put', 'enctype' => 'multipart/form-data', 'role' => 'form', 'class' => 'form-horizontal']) !!}
@else
    {!! Form::open(['route' => 'collect.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'role' => 'form', 'class' => 'form-horizontal']) !!}
@endif
    @csrf
    <div class="card">
        <div class="card-body">
            @if ($collect ?? null)
                <h4 class="lead">Nº {{ $collect->id }} |
                Status: <b>{{ $collect->formatted_status }}</b>  @if($collect->status == '3')<a href="{{ route('collect.sendconfirmation', $collect->id) }}" class="btn btn-outline-success"> <i class="fas fa-envelope"></i> {{ $collect->sendconfirmation }}</a>@endif |
                                Coletador: {{ $collect->collector->name }}
                    @if($collect->status < 4 && $collect->address != null)
                        <button type="button" onclick="location.href='{{ route('collect.confirmed', $collect->id) }}'" id="buttonConfirmed" class="btn btn-success float-right" disabled>Confirmar</button>
                    @endif
                </h4>
                @if(Auth::user()->type > 3)
                    <p class="text-muted">
                        Reservada: <b>{{ $collect->user->name ?? null }}</b> {{ $collect->formatted_reservedAt ?? null }}<br>
                        Confirmada: <b>{{ $collect->confirmed->name ?? null}}</b> {{ $collect->formatted_confirmedAt ?? null }}
                    </p>
                @endif
            @endif
            <div class="row">
                @include('templates.components.select', ['label' => 'Tipo',   'col' => '2', 'select' => 'collectType',  'attributes' => ['class' => 'form-control'], 'data' => $collectType_list])
                {{-- SCHEDULE --}}
                @if ($collect ?? null)
                    @include('templates.components.input',  ['label' => 'Data',   'col' => '2', 'input'  => 'date', 'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true'], 'value' => $collect->formatted_date])
                    @include('templates.components.input',  ['label' => 'Hora',   'col' => '2', 'input'  => 'hour', 'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true'], 'value' => $range ?? $collect->hour])
                    @include('templates.components.input',  ['label' => 'Bairro', 'col' => '4', 'input'  => 'neighborhood', 'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true'], 'value' => $collect->neighborhood->getNeighborhoodZone()])
                    @include('templates.components.label',  ['label' => 'Taxa',   'col' => '2', 'input'  => '', 'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true'], 'text' => ($collect->payment == 4 ? $collect->getFormattedPaymentAttribute() : "R$ " . $collect->neighborhood->displacementRate)])
                {{-- EXTRA --}}
                @else
                    @include('templates.components.input',  ['label' => 'Data',      'col' => '2', 'input'  => 'date', 'attributes' => ['required' => 'true', 'class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'data-date', 'im-insert' => 'true']])
                    @include('templates.components.select', ['label' => 'Hora',      'col' => '2', 'select' => 'hour', 'attributes' => ['required' => 'true', 'class' => 'form-control'], 'data' => $hour_list])
                    @include('templates.components.select', ['label' => 'Bairro',    'col' => '4', 'select' => 'neighborhood_id', 'attributes' => ['required' => 'true', 'class' => 'form-control select2bs4'], 'data' => $neighborhood_list])
                    @include('templates.components.select', ['label' => 'Coletador', 'col' => '4', 'select' => 'collector_id', 'attributes' => ['required' => 'true', 'class' => 'form-control select2bs4'], 'data' => $collector_list])
                @endif
            </div>
            @if ($collect ?? null)
                <h5 class="lead">Paciente(s)
                    @if ($collect->status < 7)
                        <button type="button" data-toggle="modal" data-target="#modal-xl" class="btn btn-outline-success"><i class="fas fa-plus"></i> Novo</button>
                        <button type="button" data-toggle="modal" data-target="#modal-lg" class="btn btn-outline-success"><i class="fas fa-plus"></i> Já possui cadastro</button>
                    @endif
                </h5>
                <div class="row">
                    <div class="col-12">
                        @include('person.list')
                    </div>
                </div>
                <hr>
            @endif
            <div class="row">
                @include('templates.components.input', ['label' => 'CEP',       'col' => '2', 'input'  => 'cep',           'value' => $collect->cep ?? null,           'attributes' => ['id' => 'cep', 'size' => '10', 'maxlength' => '9', 'class' => 'form-control', 'data-inputmask' => "'mask': '99999-999'", 'data-cep', 'im-insert' => 'true']])
                @include('templates.components.input', ['label' => 'Endereço',  'col' => '6', 'input'  => 'address',       'value' => $collect->address ?? null,       'attributes' => ['id' => 'rua', 'class' => 'form-control', 'placeholder' => 'rua, conjunto, avenida, favela...', 'maxlength' => 140]])
                @include('templates.components.input', ['label' => 'Bairro',    'col' => '2', 'input'  => '',                                                          'attributes' => ['id' => 'bairro', 'class' => 'form-control', 'disabled' => 'true']])
                @include('templates.components.input', ['label' => 'Nº',        'col' => '2', 'input'  => 'numberAddress', 'value' => $collect->numberAddress ?? null, 'attributes' => ['class' => 'form-control', 'maxlength' => 14]])
            </div>
            <div class="row">
                @include('templates.components.input', ['label' => 'Complemento',         'col' => '4', 'input' => 'complementAddress', 'value' => $collect->complementAddress ?? null, 'attributes' => ['class' => 'form-control', 'placeholder' => 'bloco, apartamento, casa...', 'maxlength' => 45]])
                @include('templates.components.input', ['label' => 'Ponto de referência', 'col' => '8', 'input' => 'referenceAddress',  'value' => $collect->referenceAddress ?? null,  'attributes' => ['class' => 'form-control', 'maxlength' => 254]])
            </div>
            <hr>
            @if ($collect ?? null)
                <div class="row">
                    @include('templates.components.label',  ['label' => 'Valor (' . $quant . ' pacientes)', 'col' => '2', 'input'  => '',                                                               'attributes' => ['class' => 'form-control'], 'text' => ($collect->payment == 4 ? $collect->getFormattedPaymentAttribute() : $price), 'id' => 'labelValue'])
                    @include('templates.components.select', ['label' => 'Pagamento',                        'col' => '4', 'select' => 'payment',        'selected' => $collect->payment,                'attributes' => ['id' => 'selPayament', 'class' => 'form-control', 'onchange' => 'changeAuthUser()'], 'data' => $payment_list])
                    @include('templates.components.input',  ['label' => 'Troco',                            'col' => '2', 'input'  => 'changePayment',  'value' => $collect->changePayment ?? null,     'attributes' => ['id' => 'changePay', 'class' => 'form-control', 'data-inputmask' => "'mask': '99.99'", 'data-mask', 'im-insert' => 'true']])
                    @include('templates.components.select', ['label' => 'Autorização da cortesia',          'col' => '4', 'select' => 'AuthCourtesy',   'selected' => $collect->AuthCourtesy ?? null,   'attributes' => ['required' => 'true','id' => 'selAuthUser', 'class' => 'form-control', 'disabled' => 'true'], 'data' =>  ['' => '', 'Selecione' => $userAuth_list]])
                </div>
                <hr>
                <div class="row">
                    @if ($collect->status < 7)
                        <div clas="col-sm-6">
                            @include('templates.components.checkbox', ['label' => 'Cancelar', 'col' => '12', 'input' => '', 'attributes' => ['id' => 'cancellationCheck', 'class' => 'form-check-input', 'onchange' => 'changeCancellation()']])
                        </div>
                    @endif
                    @include('templates.components.select', ['label' => '', 'col' => '8', 'select' => 'cancellationType_id', 'selected' => $collect->cancellationType_id ?? null, 'attributes' => ['required' => 'true', 'id' => 'cancellationSelect', 'class' => 'form-control', 'disabled' => 'true'], 'data' => ['' => '', 'Selecione' => $cancellationType_list]])
                </div>
                <hr>
            @endif
            <div class="row">
                @include('templates.components.textarea', ['label' => 'Observações da coleta', 'col' => '12', 'input' => 'observationCollect', 'value' => $collect->observationCollect ?? null, 'attributes' => ['class' => 'form-control', 'rows' => 5, 'maxlength' => 30000]])
            </div>
            <hr>
            <div class="row">
                @include('templates.components.file', ['label' => 'Anexos', 'col' => '12', 'input' => 'attachment[]', 'attributes' => ['class' => 'form-control', 'multiple' => 'true', 'accept' => 'image/png, image/jpeg, application/pdf']])
                <p class="text-muted">Arquivos aceitáveis: Imagens (jpg, jpeg e png) e PDF</p>
            </div>
            @if ($attachments ?? null)
                <div class="row">
                    <table>
                        @foreach ($attachments as $archive)
                            @if($archive == "") <?php continue; ?> @endif
                            <tr>
                                <td><a href="{{ route('collect.archive.download', [$collect->id, $archive]) }}" download> {{ $archive }}<a><br></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        </div>

        @if ($collect ?? null)
            @if ($collect->status > 6)
                <div class="card-footer">
                    @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary', 'disabled' => 'true']])
                </div>
            @else
                <div class="card-footer">
                    @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['id' => 'submitSchedule', 'class' => 'btn btn-outline-primary']])
                </div>
            @endif
        @else
            <div class="card-footer">
                @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['id' => 'submitSchedule', 'class' => 'btn btn-outline-primary']])
            </div>
        @endif
    </div>
{!! Form::close() !!}
