<div class="card">
    @if ($person ?? null)
        {!! Form::model($person, ['route' => ['person.update', $person->id], 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @else
        {!! Form::open(['route' => 'person.store' , 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @endif
    <div class="card-body">
        <div class="row">
            @if ($collect ?? null)
                @include('templates.components.hidden', ['hidden' => 'idCollect', 'value' => $collect->id])
            @endif
            @include('templates.components.input',  ['label' => 'Nome',               'col' => '8', 'input'  => 'name', 'attributes' => ['required' => 'true', 'class' => 'form-control']])
            @include('templates.components.select', ['label' => 'Tipo',               'col' => '4', 'select'  => 'patientTypes_id',  'attributes' => ['class' => 'form-control', 'style' => 'width: 100%;'], 
                                                        'data' => $patientType_list->pluck('name', 'id')])
            {{-- linha --}}
            @include('templates.components.input',  ['label' => 'RA',                 'col' => '4', 'input'  => 'ra',   'attributes' => ['required' => 'true', 'class' => 'form-control']])
            @include('templates.components.input',  ['label' => 'Nascimento',         'col' => '4', 'input'  => 'birth',     'attributes' => ['required' => 'true', 'class' => 'form-control']])
            @include('templates.components.input',  ['label' => 'CPF',                'col' => '4', 'input'  => 'CPF',       'attributes' => ['required' => 'true', 'class' => 'form-control']])
            {{-- linha --}}
            @include('templates.components.input',  ['label' => 'Contato',            'col' => '4', 'input'  => 'fone',      'attributes' => ['required' => 'true', 'class' => 'form-control']])
            @include('templates.components.input',  ['label' => 'Contato secundário', 'col' => '4', 'input'  => 'otherFone', 'attributes' => ['class'    => 'form-control']])
            @include('templates.components.input',  ['label' => 'RG',                 'col' => '4', 'input'  => 'RG',        'attributes' => ['class'    => 'form-control']])
            {{-- linha --}}
            @include('templates.components.input',  ['label' => 'E-mail',          'col' => '4', 'input'   => 'email',          'attributes' => ['class'    => 'form-control']])
        </div>
        <div class="row">
            {{-- linha --}}
            @include('templates.components.input',  ['label' => 'Nome reponsável', 'col' => '8', 'input'  => 'nameReponsible', 'attributes' => ['required' => 'true', 'class'    => 'form-control', 'disabled' => 'true']])
            @include('templates.components.input',  ['label' => 'Contato reponsável', 'col' => '4', 'input'  => 'foneReponsible', 'attributes' => ['required' => 'true', 'class'    => 'form-control', 'disabled' => 'true']])
            {{-- linha --}}
            @include('templates.components.textarea',  ['label' => 'Medicação',     'col' => '12',  'input'  => 'mediacation',    'attributes' => ['class' => 'form-control']])
            @include('templates.components.textarea',  ['label' => 'Observações',   'col' => '12',  'input'  => 'observationPat', 'attributes' => ['class' => 'form-control']])
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
    </div>
    {!! Form::close() !!}
</div>