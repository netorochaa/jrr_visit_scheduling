<table id="table-people" class="table table-striped table-sm">
    <thead class="thead-dark">
        <tr>
            <th>Nome</th>
            <th>Contato</th>
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
                <a href="{{ route('collect.person.edit', [$collect->id, $person->id]) }}" style="color: black"><b><u>{{ $person->name }}</b></u> <i class='fas fa-pen'></i></a><br>
                <small class="text-muted">{{ $person->patientType->name }} @if($person->typeResponsible) | {{ $person->formatted_TypeResponsible }} {{ $person->nameResponsible }} @endif</small>
            </td>
            <td>
                <small>Telefone: {{ $person->fone }}<br>
                E-mail: {{ $person->email ?? "Não informado" }}</small>
            </td>
            @if($collect->status != 2) <td>{{ $person->ra }}</td> @endif
            <td>{{ $person->getCovenantAttribute($person->pivot->covenant) ?? "NÃO INFORMADO"}}</td>
            <td>{{ $person->pivot->exams ?? "NÃO INFORMADO" }}</td>
            <td>
                @if($collect->status < 7)
                    <button type="button" onclick="location.href='{{ route('person.collect.detach', [$person->id, $collect->id]) }}'" class="btn btn-danger btn-sm"  ><i class="fas fa-trash"></i></button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>