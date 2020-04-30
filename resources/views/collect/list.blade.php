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
        @foreach ($collect_list->where('neighborhood_id', '!=', null) as $collectMarked)
        <tr>
            {{-- COLETA --}}
            <td> 
                <span style="display:none">{{ $collectMarked->date }}</span>
                <p><b>{{ $collectMarked->formatted_date }} - {{ $collectMarked->hour }}</b></p>
            </td>
            {{-- CÓDIGO --}}
            <td>
                <p><span class="lead">{{ $collectMarked->id }}</p>
            </td>
            {{-- STATUS --}}
            <td>
                @if(count($collectMarked->people) != 0)
                    <span class="text-muted">Pacientes: {{ count($collectMarked->people) }} <br>
                @endif
                Status:</span> {{ $collectMarked->formatted_status }}
                @if($collectMarked->user != null)
                <small><span class="text-muted">Origem: {{ $collectMarked->user->name }}</span></small>
                @endif
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
                @endif
            </td>
            {{-- COLETADOR --}}
            <td>
                {{ $collectMarked->collector->name }}<br>
                <span class="text-muted">{{ $collectMarked->collector->user->name }}</span>
            </td>
            <td>
                <div class="btn-group">
                   {{-- {!! Form::open(['route' => ['collect.destroy', $collectMarked->id], 'method' => 'DELETE']) !!}
                    <div class="btn-group"> --}}
                        <button type="button" onclick="location.href='{{ route('collect.schedule', $collectMarked->id) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button>
                        {{-- @include('templates.components.submit', ['input' => 'Deletar', 'attributes' => ['class' => 'btn btn-danger']])
                    </div>
                    {!! Form::close() !!} --}}
                </div>
              </td>
        </tr>
        @endforeach
    </tbody>
</table>
