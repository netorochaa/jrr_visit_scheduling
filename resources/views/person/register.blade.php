<div class="card">
    @if ($person ?? null)
        {!! Form::model($person, ['route' => ['collect.person.update', $collect->id, $person->id], 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @else
        {!! Form::open(['route' => ['collect.person.store', $collect->id] , 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
        @if($collect->status == 2) @include('templates.components.hidden', ['hidden' => 'site', 'value' => true]) @endif
    @endif
   <div class="card-body">
        <p class="lead" id="age">ATENÇÃO: Não realizamos coletas domiciliares em crianças com idade inferior a 8 anos, exceto para o exame de teste do pezinho.</p>
        <div class="row">
            @include('templates.components.input',  ['label' => 'Nome',         'col' => '4', 'input'  => 'name',   'attributes' => ['required' => 'true', 'class' => 'form-control', 'maxlength' => 55]])
            @include('templates.components.input',  ['label' => 'Nascimento',   'col' => '4', 'input'  => 'birth',  'attributes' => ['onBlur' => 'checkAge(this)','required' => 'true', 'class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'data-date', 'im-insert' => 'true']])
            @include('templates.components.input',  ['label' => 'E-mail',       'col' => '4', 'input'  => 'email',  'attributes' => ['onBlur' => 'checkEmail(this)', 'class'    => 'form-control', 'maxlength' => 254]])
        @if($collect->status != 2)
            @include('templates.components.input',  ['label' => 'RA',       'col' => '4', 'input'  => 'ra',     'attributes' => ['class' => 'form-control', 'data-inputmask' => "'mask': '9999999999'", 'data-ra', 'im-insert' => 'true']])
        @endif
            @include('templates.components.input',  ['label' => 'CPF',      'col' => '4', 'input'  => 'CPF',    'attributes' => ['required' => 'true', 'class' => 'form-control', 'data-inputmask' => "'mask': '99999999999'", 'data-cpf', 'im-insert' => 'true']])
            @include('templates.components.input',  ['label' => 'Contato',  'col' => '4', 'input'  => 'fone',   'attributes' => ['required' => 'true', 'class' => 'form-control', 'data-inputmask' => "'mask': '(99) 99999-9999'", 'data-fone', 'im-insert' => 'true']])
            @include('templates.components.input',  ['label' => 'Outro contato', 'col' => '4', 'input'  => 'otherFone', 'attributes' => ['class'    => 'form-control', 'data-inputmask' => "'mask': '(99) 99999-9999'", 'data-fone', 'im-insert' => 'true']])
            @include('templates.components.input',  ['label' => 'RG',            'col' => '4', 'input'  => 'RG',        'attributes' => ['class'    => 'form-control', 'maxlength' => 10]])
            @include('templates.components.select', ['label' => 'Tipo',          'col' => '4', 'select' => 'patientTypes_id',  'attributes' => ['id' => 'selectPatientTypes', 'onchange' => 'changeResponsible(this)', 'class' => 'form-control', 'style' => 'width: 100%;'], 'data' => $patientType_list->pluck('nameFull', 'id')])
        </div>
        <hr>
        <h4 class="lead">Responsável</h4>
        <div class="row">
            @include('templates.components.select', ['label' => 'Tipo',     'col' => '4', 'select' => 'typeResponsible',  'attributes'  => ['id' => 'selectType', 'onchange' => 'changeTypeResponsible(this)', 'class' => 'form-control', 'style' => 'width: 100%;', 'disabled' => 'true'], 'data' => $typeResponsible_list])
            @include('templates.components.input',  ['label' => 'Nome',     'col' => '4', 'input'  => 'nameResponsible',   'attributes' => ['id' => 'inputName', 'required' => 'true', 'class' => 'form-control', 'disabled' => 'true', 'maxlength' => 55]])
            @include('templates.components.input',  ['label' => 'Contato ', 'col' => '4', 'input'  => 'foneResponsible',   'attributes' => ['id' => 'inputFone', 'class' => 'form-control', 'data-inputmask' => "'mask': '(99) 99999-9999'", 'data-fone', 'im-insert' => 'true', 'disabled' => 'true']])
        </div>
        <hr>
        <div class="row">
            @include('templates.components.select',    ['label' => 'Convênio',  'col' => '4',  'select' => 'covenant', 'selected' => $personHasCollect->covenant ?? null,   'attributes' => ['class' => 'form-control'], 'data' => $covenant_list])
            @include('templates.components.textarea',  ['label' => 'Exames',    'col' => '8',  'input'  => 'exams',    'value'    => $personHasCollect->exams ?? null,      'attributes' => ['id' => 'textExams', 'required' => 'true', 'class' => 'form-control', 'rows' => '2', 'maxlength' => 20000]])
        </div>
        <hr>
        <div class="row">
            @include('templates.components.textarea',  ['label' => 'Medicação',     'col' => '12', 'input'  => 'medication',     'attributes' => ['class' => 'form-control', 'rows' => 2, 'maxlength' => 500]])
            @include('templates.components.textarea',  ['label' => 'Observações',   'col' => '12', 'input'  => 'observationPat', 'attributes' => ['class' => 'form-control', 'rows' => 2, 'maxlength' => 20000]])
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
    </div>
    {!! Form::close() !!}
</div>
