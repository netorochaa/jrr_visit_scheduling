<table id="table-{{ $table }}" class="table table-striped table-sm">
    <thead>
        <tr>
            @foreach ($thead_for_datatable as $ths)
                <th>{{ $ths }}</th>
            @endforeach
            <th class='sorting_desc_disabled sorting_asc_disabled'></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($freedays_list as $freedays)
        <tr>
            <td>{{ $freedays->name }}</td>
            {{-- <td>{{ $freedays->formatted_type }}:<br>
                @if ($freedays->type == 1 )
                    @foreach ($freedays->collectors as $item)
                        - {{ $item->name }}<br>
                    @endforeach
                @else
                    @foreach ($freedays->cities as $item)
                        - {{ $item->name }}<br>
                    @endforeach
                @endif
             </td> --}}
            <td>{{ $freedays->getDateRange() }}</td>
            <td>
                <div class="btn-group">
                  {!! Form::open(['route' => ['freedays.destroy', $freedays->id], 'method' => 'DELETE']) !!}
                    <div class="btn-group">
                        {{-- <button type="button" onclick="location.href='{{ route('freedays.edit', $freedays->id) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button> --}}
                        @include('templates.components.submit', ['input' => 'Deletar', 'attributes' => ['class' => 'btn btn-danger btn-sm']])
                    </div>
                    {!! Form::close() !!}
                    </td>
                </div>
              </td>
        </tr>
        @endforeach
    </tbody>
</table>
