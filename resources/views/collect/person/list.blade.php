<table id="table-people" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nome</th>
            <th>RA</th>
            <th>Tipo</th>
            <th>Reponsável</th>
            <th class='sorting_desc_disabled sorting_asc_disabled'></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collect->people as $person)
        <tr>
            <td>{{ $person->name }}</td>
            <td>{{ $person->ra }}</td>
            <td>{{ $person->patientType->name }}</td>
            <td>{{ $person->nameReponsible }}</td>
            <td>
                <div class="btn-group">
                   {!! Form::open(['route' => ['person.collect.detach', $person->id, $collect->id]]) !!}
                    <div class="btn-group">
                        <button type="button" onclick="location.href='{{ route('person.edit', $person->id, $collect->id) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button>
                        @include('templates.components.submit', ['input' => 'Deletar', 'attributes' => ['class' => 'btn btn-danger']])
                    </div>
                    {!! Form::close() !!}
                    </td>
                </div>
              </td>
        </tr>
        @endforeach
    </tbody>
</table>
