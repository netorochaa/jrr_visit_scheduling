<div class="card">
    <div class="card-body">
    {!! Form::open(['route' => 'person.collect.attach' , 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
        @include('templates.components.hidden', ['hidden' => 'idCollect', 'value' => $collect->id])
        <div class="row">
            <div class="col-sm-12">
                <div clas="col-12 form-group">
                    <label>Procure pelo paciente</label>
                    <select name="registeredPatient" id="registeredPatientSel" class="form-control select2bs4" style="width: 100%;">
                        <option value="" selected></option>
                        @foreach ($people_list as $people)
                            <option value="{{ $people->id }}">
                                {{ $people->name }} | CPF: {{ $people->CPF }} | RG: {{ $people->RG }} | Nasc: {{ $people->birth }} | {{ $people->patientType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Adicionar', 'attributes' => ['class' => 'btn btn-outline-info']])
    </div>
    {!! Form::close() !!}
</div>