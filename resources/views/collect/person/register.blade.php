<div class="card">
    @if ($person ?? null)
        {!! Form::model($person, ['route' => ['collect.person.update', $collect->id, $person->id], 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @else
        {!! Form::open(['route' => ['collect.person.store', $collect->id] , 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @endif
    <div class="card-body">
        <div class="row">
            @include('templates.components.input',  ['label' => 'Nome',               'col' => '4', 'input'  => 'name',             'attributes' => ['required' => 'true', 'class' => 'form-control']])
            @include('templates.components.input',  ['label' => 'Nascimento',         'col' => '4', 'input'  => 'birth',            'attributes' => ['required' => 'true', 'class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'data-date', 'im-insert' => 'true']])
            @include('templates.components.select', ['label' => 'Tipo',               'col' => '4', 'select' => 'patientTypes_id',  'attributes' => ['class' => 'form-control', 'style' => 'width: 100%;'], 'data' => $patientType_list->pluck('name', 'id')])
            {{-- linha --}}
            @include('templates.components.input',  ['label' => 'RA',                 'col' => '4', 'input'  => 'ra',        'attributes' => ['required' => 'true', 'class' => 'form-control', 'data-inputmask' => "'mask': '9999999999'", 'data-ra', 'im-insert' => 'true']])
            @include('templates.components.input',  ['label' => 'CPF',                'col' => '4', 'input'  => 'CPF',       'attributes' => ['required' => 'true', 'class' => 'form-control', 'data-inputmask' => "'mask': '99999999999'", 'data-cpf', 'im-insert' => 'true']])
            @include('templates.components.input',  ['label' => 'Contato',            'col' => '4', 'input'  => 'fone',      'attributes' => ['required' => 'true', 'class' => 'form-control', 'data-inputmask' => "'mask': '(99) 99999-9999'", 'data-fone', 'im-insert' => 'true']])
            {{-- linha --}}
            @include('templates.components.input',  ['label' => 'Outro contato',      'col' => '4', 'input'  => 'otherFone', 'attributes' => ['class'    => 'form-control', 'data-inputmask' => "'mask': '(99) 99999-9999'", 'data-fone', 'im-insert' => 'true']])
            @include('templates.components.input',  ['label' => 'RG',                 'col' => '4', 'input'  => 'RG',        'attributes' => ['class'    => 'form-control']])
            @include('templates.components.input',  ['label' => 'E-mail',             'col' => '4', 'input'  => 'email',     'attributes' => ['class'    => 'form-control']])
        </div>
        <hr>
        <h4 class="lead">Responsável</h4>
        <div class="row">
            @include('templates.components.select', ['label' => 'Tipo',     'col' => '4', 'select' => 'typeResponsible',  'attributes' => ['class' => 'form-control', 'style' => 'width: 100%;', 'disabled'], 'data' => ['' => '', 'Selecione' => $typeResponsible_list]])
            @include('templates.components.input',  ['label' => 'Nome',     'col' => '4', 'input'  => 'nameReponsible',   'attributes' => ['required' => 'true', 'class' => 'form-control', 'disabled' => 'true']])
            @include('templates.components.input',  ['label' => 'Contato ', 'col' => '4', 'input'  => 'foneReponsible',   'attributes' => ['required' => 'true', 'class' => 'form-control', 'data-inputmask' => "'mask': '(99) 99999-9999'", 'data-fone', 'im-insert' => 'true', 'disabled' => 'true']])
        </div>
        <hr>
        <div class="row">
            @include('templates.components.select',    ['label' => 'Convênio',  'col' => '4',  'select' => 'covenant', 'selected' => $personHasCollect->covenant ?? null,   'attributes' => ['class' => 'form-control'], 'data' => $covenant_list])
            @include('templates.components.textarea',  ['label' => 'Exames',    'col' => '8',  'input'  => 'exams',    'value'    => $personHasCollect->exams ?? null,      'attributes' => ['required' => 'true', 'class' => 'form-control', 'rows' => 2]])
        </div>
        <hr>
        <div class="row">
            @include('templates.components.textarea',  ['label' => 'Medicação',     'col' => '12', 'input'  => 'medication',     'attributes' => ['class' => 'form-control', 'rows' => 2]])
            @include('templates.components.textarea',  ['label' => 'Observações',   'col' => '12', 'input'  => 'observationPat', 'attributes' => ['class' => 'form-control', 'rows' => 2]])
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
    </div>
    {!! Form::close() !!}
</div>