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
        @foreach ($collectors_list as $collector)
        <tr>
            <td>
                {{ $collector->name }}<br>
                <small class="text-muted">
                    Criado: {{ $collector->formatted_createdAt }}<br>
                    Atualizado: {{ $collector->formatted_updatedAt }}
                </small>
            </td>
            <td>{{ $collector->mondayToFriday }}</td>
            <td>{{ $collector->saturday }}</td>
            <td>{{ $collector->sunday }}</td>
            {{-- <td>{{ $collector->startingAddress }}</td> --}}
            <td>{{ $collector->user->name }}</td>
            <td style="text-align: center"><u class="badge badge-primary"> <a href="{{ route('collector.show', $collector->id) }}" style="color: white"> {{ count($collector->neighborhoods) }}</a></u> </td>
            <td>
                {{ $collector->formatted_active }}
            </td>
            <td>
                @if ($collector->active == 'on')
                    <div class="btn-group">
                        {!! Form::open(['route' => ['collector.destroy', $collector->id], 'method' => 'DELETE']) !!}
                            <div class="btn-group">
                                <button type="button" onclick="location.href='{{ route('collector.edit', $collector->id) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button>
                                @include('templates.components.submit', ['input' => 'Inativar', 'attributes' => ['class' => 'btn btn-danger']])
                            </div>
                            {!! Form::close() !!}
                        </div>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
