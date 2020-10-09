<table id="table-{{ $table }}" class="table table-bordered table-striped">
    <thead>
        <tr>
            @foreach ($thead_for_datatable as $ths)
                <th>{{ $ths }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($collect_list as $collectMarked)
        <tr>
            {{-- COLETA --}}
            <td>
                <span style="display:none">{{ $collectMarked->date }}</span>
                <b>{{ $collectMarked->formatted_date }} - {{ $collectMarked->hour }}</b>
                @if($collectMarked->user != null)
                    <br><small><span class="text-muted">Origem: {{ $collectMarked->user->name }}</span></small>
                @endif
            </td>
            {{-- CÓDIGO --}}
            <td>
                <a href="{{ route('collect.schedule', $collectMarked->id) }}"><u><span class="lead">{{ $collectMarked->id }}</span></u> <small><i class='fas fa-pen'></i></small></a>
            </td>
            {{-- PACIENTES --}}
            <td>
                @foreach ($collectMarked->people as $person)
                    - <small>{{ $person->name }} ({{ $person->patientType->name }})<br>
                        <span class="text-muted">RA: {{ $person->ra }}</span></small><br>
                @endforeach
            </td>
            {{-- PAG. TAXA --}}
            <td>
                @if(count($collectMarked->people) != 0)
                    <span class="text-muted">
                        Pacientes: {{ count($collectMarked->people) }}
                    </span><br>
                @endif
                {{ $collectMarked->formatted_payment ?? null }} <br>
                @if($collectMarked->payment == "1")
                    <span class="text-muted">Troco: R$</span> {{ $collectMarked->changePayment ?? "0.00"}}
                @elseif($collectMarked->payment == "4")
                    <span class="text-muted">Autorizada:</span> {{ $collectMarked->AuthCourtesy }}
                @endif
            </td>
            {{-- BAIRRO --}}
            <td>
                <b>{{ $collectMarked->neighborhood->getNeighborhoodZone() ?? null }}</b><br>
                <small>{{ $collectMarked->neighborhood->city->name ?? null }}</small>
            </td>
            {{-- ENDEREÇO --}}
            <td>@if($collectMarked->address != null)
                <small>
                    Endereço: {{ $collectMarked->address }}, {{ $collectMarked->numberAddress }}, {{ $collectMarked->neighborhood->name }} {{ $collectMarked->cep }}<br>
                    <span class="text-muted">Complemento: {{ $collectMarked->complementAddress }} <br>
                    Referência: {{ $collectMarked->referenceAddress }}</span>
                 </small>
                 @else
                    <p class="lead" style="color: red"> NÃO INFORMADO </p>
                @endif
            </td>
            {{-- COLETADOR --}}
            <td>
                {{ $collectMarked->collector->name }}<br>
                <small><span class="text-muted">{{ $collectMarked->collector->user->name }}</span></small>
            </td>
            {{-- STATUS --}}
            <td>
                @if ($collectMarked->status == 3)
                    <span style="display:none">{{ $collectMarked->reserved_at }}</span>
                    <span class="badge badge-warning rounded">
                @elseif($collectMarked->status == 4)
                    <span class="badge badge-success rounded">
                @elseif($collectMarked->status == 5)
                    <span class="badge badge-white rounded">
                @elseif($collectMarked->status == 6)
                    <span style="display:none">{{ $collectMarked->closed_at }}</span>
                    <span class="badge badge-secondary rounded">
                @elseif($collectMarked->status > 6)
                    <span style="display:none">{{ $collectMarked->closed_at }}</span>
                    <span class="badge badge-danger rounded">
                @elseif($collectMarked->status > 8)
                    <span style="display:none">{{ $collectMarked->closed_at }}</span>
                    <span class="badge badge-indigo rounded">
                @endif
                    {{ $collectMarked->formatted_status }} {{ $collectMarked->hour_new ?? null }}</span>
                @if($collectMarked->status == 3)
                    <br><small class="text-muted">{{ $collectMarked->formatted_reservedAt }}</small>
                @elseif($collectMarked->status == 4)
                    <br><small>Via: {{ $collectMarked->confirmed->name ?? null }}</small>
                @elseif($collectMarked->status == 6)
                    <br><small class="text-muted">{{ $collectMarked->formatted_closedAt }}</small>
                @elseif($collectMarked->status < 8)
                    <br>
                    <small>
                        Via: {{ $collectMarked->cancelled->name ?? null }} - {{ $collectMarked->cancellationtype->name ?? null }} <br>
                        <span class="text-muted">{{ $collectMarked->formatted_closedAt }}</span>
                    </small>
                @elseif($collectMarked->status > 8)
                    <br><small class="text-muted">Via: {{ $collectMarked->modified->name ?? null }}</small>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
