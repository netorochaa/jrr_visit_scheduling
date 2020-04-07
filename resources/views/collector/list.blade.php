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
            <td>{{ $collector->name }}</td>
            <td>{{ $collector->initialTimeCollect }}</td>
            <td>{{ $collector->finalTimeCollect }}</td>
            <td>{{ $collector->collectionInterval }}</td>
            <td>{{ $collector->startingAddress }}</td>
            <td>{{ $collector->formatted_active }}</td>
            <td>{{ $collector->user->name }}</td>
            <td>{{ $collector->created_at }}</td>
            <td>{{ $collector->updated_at }}</td>
            <td>
                <div class="btn-group">
                    {!! Form::open(['route' => ['collector.destroy', $collector->id], 'method' => 'DELETE']) !!}
                      <div class="btn-group">
                          <button type="button" onclick="location.href='{{ route('collector.edit', $collector->id) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button>
                          @include('templates.components.submit', ['input' => 'Deletar', 'attributes' => ['class' => 'btn btn-danger']])
                      </div>
                      {!! Form::close() !!}
                      </td>
                  </div>
              </td>
        </tr>
        @endforeach
    </tbody>
</table>
