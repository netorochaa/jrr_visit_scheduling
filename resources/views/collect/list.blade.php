<table id="table-{{ $table }}" class="table table-bordered table-striped">
    <thead>
        <tr>
            @foreach ($thead_for_datatable as $ths)
                <th>{{ $ths }}</th>
            @endforeach
            <th class='sorting_desc_disabled sorting_asc_disabled'></th>
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
                <p><span class="lead">{{ $collectMarked->id }}</span></p>
            </td>
            {{-- PAG. TAXA --}}
            <td>
                {{ $collectMarked->formatted_payment }} <br>
                @if($collectMarked->payment == "1") 
                    <span class="text-muted">Troco: R$</span> {{ $collectMarked->changePayment ?? "0.00"}}
                @elseif($collectMarked->payment == "4")
                    <span class="text-muted">Autorizada:</span> {{ $collectMarked->AuthCourtesy }}
                @endif
            </td>
            {{-- BAIRRO --}}
            <td>
                <b>{{ $collectMarked->neighborhood->getNeighborhoodZone() }}</b><br>
                <small>{{ $collectMarked->neighborhood->city->name }}</small>
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
                    <span class="badge badge-warning rounded">
                @elseif($collectMarked->status == 4)    
                    <span class="badge badge-success rounded">
                @elseif($collectMarked->status == 5)
                    <span class="badge badge-white rounded">
                @elseif($collectMarked->status == 6)    
                    <span class="badge badge-secondary rounded">
                @elseif($collectMarked->status > 6)    
                    <span class="badge badge-danger rounded">
                @endif
                {{ $collectMarked->formatted_status }}</span>
                @if($collectMarked->status == 4)    
                    <br><small>Via: {{ $collectMarked->confirmed->name ?? null }}</small>
                @elseif($collectMarked->status > 6)    
                    <br><small>Via: {{ $collectMarked->cancelled->name ?? null }} - {{ $collectMarked->cancellationtype->name }}</small>
                @endif
                @if(count($collectMarked->people) != 0)
                    <br><span class="text-muted">Pacientes: {{ count($collectMarked->people) }}</span>
                @endif
            </td>
            <td>
                <div class="btn-group">
                    <button type="button" onclick="location.href='{{ route('collect.schedule', $collectMarked->id) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button>        
                </div>
              </td>
        </tr>
        @endforeach
    </tbody>
</table>
