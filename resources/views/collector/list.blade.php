<table id="table-{{ $table }}" class="table table-sm">
    <thead>
        <tr>
            <th>#</th>
            @foreach ($thead_for_datatable as $ths)
                <th>{{ $ths }}</th>
            @endforeach
            <th class='sorting_desc_disabled sorting_asc_disabled'></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collectors_list as $collector)
        <tr>
            <td>
                {{ $collector->id }}
            </td>
            <td>
                {{ $collector->name }}<br>
                <small class="text-muted">
                    Criado: {{ $collector->formatted_createdAt }}<br>
                    Atualizado: {{ $collector->formatted_updatedAt }}
                </small>
            </td>
            <td>
                <small class="text-muted">{!! "Data inicial da última atualização nos horários: <b>" . $collector->formatted_dateStartLastModify . "</b>" ?? "-" !!}</small>
                <table class="table table-striped table-sm">
                    @if($collector->mondayToFriday)
                        <tr>
                            <td>Seg-Sex</td>
                            <td><small>{{ $collector->mondayToFriday }}</small></td>
                        </tr>
                    @endif
                    @if($collector->saturday)
                        <tr>
                            <td>Sábados</td>
                            <td><small>{{ $collector->saturday }}</small></td>
                        </tr>
                    @endif
                    @if($collector->sunday)
                        <tr>
                            <td>Domingos</td>
                            <td><small>{{ $collector->sunday }}</small></td>
                        </tr>
                    @endif
                </table>
            </td>
            <td>{{ $collector->formatted_showInSite }}</td>
            <td>{{ $collector->user->name }}</td>
            <td><u class="badge badge-primary"> <a href="{{ route('collector.show', $collector->id) }}" style="color: white"> {{ count($collector->neighborhoods) }}</a></u> </td>
            <td>
                {{ $collector->formatted_active }}
            </td>
            <td>
                @if ($collector->active == 'on')
                    <div class="btn-group">
                        {!! Form::open(['route' => ['collector.destroy', $collector->id], 'method' => 'DELETE']) !!}
                            <div class="btn-group">
                                <button type="button" onclick="location.href='{{ route('collector.edit', $collector->id) }}'" class="btn btn-info btn-sm"><i class='fas fa-pen'></i></button>
                                @include('templates.components.submit', ['input' => 'Inativar', 'attributes' => ['class' => 'btn btn-danger btn-sm']])
                            </div>
                        {!! Form::close() !!}
                    </div>
                @else
                    <button type="button" onclick="location.href='{{ route('collector.collector.reactivate', $collector->id) }}'" class="btn btn-warning btn-sm">Ativar</button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
