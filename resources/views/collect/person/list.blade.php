<table id="table-people" class="table table-striped display compact">
    <thead>
        <tr>
            <th>Nome</th>
            @if($collect->status != 2) <th>RA</th> @endif
            <th>Convênio</th>
            <th>Exames</th>
            <th class='sorting_desc_disabled sorting_asc_disabled'></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collect->people as $person)
        <tr>
            <td>
                <b>{{ $person->name }}</b><br>
                <small class="text-muted">{{ $person->patientType->name }} @if($person->typeResponsible) | {{ $person->formatted_TypeResponsible }} {{ $person->nameResponsible }} @endif</small>
            </td>
            @if($collect->status != 2) <td>{{ $person->ra }}</td> @endif
            <td>{{ $person->getCovenantAttribute($person->pivot->covenant) ?? "NÃO INFORMADO"}}</td>
            <td>{{ $person->pivot->exams ?? "NÃO INFORMADO" }}</td>
            <td>
                @if($collect->status < 7)
                    <div class="btn-group">
                        @if($collect->status != 2) <button type="button" onclick="location.href='{{ route('collect.person.edit', [$collect->id, $person->id]) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button> @endif
                        <button type="button" onclick="location.href='{{ route('person.collect.detach', [$person->id, $collect->id]) }}'" class="btn btn-danger"  ><i class="fas fa-trash"></i></button>
                    </div>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
