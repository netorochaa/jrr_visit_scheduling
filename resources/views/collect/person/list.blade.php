<table id="table-people" class="table table-striped display compact">
    <thead>
        <tr>
            <th>Nome</th>
            <th>RA</th>
            <th>Tipo</th>
            <th>Reponsável</th>
            <th>Convênio</th>
            <th>Exames</th>
            <th class='sorting_desc_disabled sorting_asc_disabled'></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collect->people as $person)
        <tr>
            <td>{{ $person->id }} {{ $collect->id }}  {{ $person->name }}</td>
            <td>{{ $person->ra }}</td>
            <td>{{ $person->patientType->name }}</td>
            <td>{{ $person->formatted_TypeResponsible }} {{ $person->nameResponsible }}</td>
            <td>{{ $person->getCovenantAttribute($person->pivot->covenant) ?? "NÃO INFORMADO" }}</td>
            <td>{{ $person->pivot->exams ?? "NÃO INFORMADO" }}</td>
            <td>
                <div class="btn-group">
                    <button type="button" onclick="location.href='{{ route('collect.person.edit', [$collect->id, $person->id]) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button>
                    <button type="button" onclick="location.href='{{ route('person.collect.detach', [$person->id, $collect->id]) }}'" class="btn btn-danger"  >Remover</button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
