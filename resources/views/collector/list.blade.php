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
            <td>{{ $collector->initial-time-collect }}</td>
            <td>{{ $collector->final-time-collect }}</td>
            <td>{{ $collector->collection-interval }}</td>
            <td>{{ $collector->starting-address }}</td>
            <td>{{ $collector->active }}</td>
            <td>{{ $collector->created_at }}</td>
            <td>{{ $collector->updated_at }}</td>
            <td>
                <div class="btn-group">
                  <button type="button" onclick="#" class="btn btn-info"><i class="fas fa-pen"></i></button>
                  <button type="button" onclick="#" class="btn btn-danger"><i class='fas fa-trash'></i></button>
                </div>
              </td>
        </tr>    
        @endforeach
    </tbody>
</table>