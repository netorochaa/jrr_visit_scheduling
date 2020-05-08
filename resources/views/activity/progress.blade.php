@foreach ($collect_list->all() as $collect)
    <div>
        {{-- ÍCONE DA AMOSTRA --}}
        <i class="fas fa-vial @if($collect->status == '5') bg-blue
                              @elseif($collect->status == '6') bg-gray
                              @else bg-red
                              @endif }}">
        </i>
        <div class="timeline-item">
            {{-- HORA --}}
            <span class="time"><i class="fas fa-clock"></i> <b>{{ $collect->hour }}</b></span>
            {{-- TÍTULO BAIRRO/CIDADE --}}
            <h3 class="timeline-header">
                <b>{{ $collect->neighborhood->getNeighborhoodZone() }}</b> <small>{{ $collect->neighborhood->city->name }}</small> 
            </h3>
            <div class="timeline-body">
                {{-- VERIFICA SE ESTA EM ANDAMENTO --}}
                @if($collect->status == '5')
                    {{-- MOSTRA ENDEREÇO SE NÃO NULO--}}
                    @if($collect->address != null) 
                        <small>
                            Endereço: {{ $collect->address }}, {{ $collect->numberAddress }}, {{ $collect->neighborhood->name }} {{ $collect->cep }}<br>
                            <span class="text-muted">Complemento: {{ $collect->complementAddress }} <br>
                            Referência: {{ $collect->referenceAddress }}</span><br>
                            @if($collect->status == '5') <a class="btn btn-info btn-sm" style="color: white"><i class="fas fa-map-marked-alt"></i> Ir para mapa</a> @endif
                        </small>
                    {{-- SE ENDEREÇO NULO --}}
                    @else
                        <p class="lead" style="color: red"> NÃO INFORMADO </p>
                    @endif
                    <hr>
                    {{-- MOSTRA PACIENTES --}}
                    <h5 class="lead">Taxa: {{ $collect->formatted_payment }} <b>R$ {{ (string) count($collect->people) * $collect->neighborhood->displacementRate }}</b> 
                        @if($collect->payment == 1) - Troco: R$ {{ $collect->changePayment ?? "0.00" }} @endif</h5>
                    <?php $count = 1; ?>
                    @foreach ($collect->people as $person)
                        <p>{{ $count++ }}. {{ $person->name }} | {{ $person->patientType->name }}
                            @if($person->patientType->needResponsible == "on") | {{ $person->formatted_typeResponsible }}: {{ $person->nameResponsible }} @endif <br>
                        <small class="text-muted">{{ $person->pivot->exams }}</small></p>
                    @endforeach
                    <hr>
                    {{-- CANCELLATION --}}
                    {!! Form::open(['route' => ['collect.update', $collect->id], 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal']) !!}
                        @include('templates.components.select', ['label' => '', 'col' => '6', 'select' => 'cancellationType_id', 'attributes' => ['required' => 'true', 'id' => '', 'class' => 'form-control', 'style' => 'float: left'], 'data' => ['' => '', 'Selecione' => $cancellationType_list]])
                        @include('templates.components.hidden', ['hidden' => 'status', 'value' => '8'])
                        @include('templates.components.submit', ['input' => 'Cancelar coleta', 'attributes' => ['id' => '', 'class' => 'btn btn-danger']])
                    {!! Form::close() !!}
                {{-- NÃO ESTANDO EM ANDAMENTO --}}
                @else
                    <h5 class="lead">{{ $collect->formatted_status }} em {{ $collect->formatted_closed_at }}</h5>
                @endif
            </div>
            <hr>
            {{-- BUTTONS --}}
            <div class="timeline-footer">
                {{-- <a class="btn btn-default btn-sm" style="color: black">Avaliar</a> --}}
                @if($collect->status == '5')
                    <a href="" class="btn btn-success">Finalizar</a>
                @else
                    <a href="{{ route('collect.schedule', $collect->id) }}" class="btn btn-secondary btn-sm">Detalhes</a>
                @endif
            </div>
        </div>
    </div>
  @endforeach
  {{-- FINALIZA ROTA --}}
  @if($end)
    <div class="time-label">
        <span class="bg-gray">{{ $activity->formatted_status }}: {{ $end }} </span></span>
    </div>
@endif