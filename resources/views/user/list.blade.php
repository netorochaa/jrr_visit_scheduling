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
        @foreach ($users_list as $user)
        <tr>
            <td>{{ $user->email }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->type }}</td>
            <td>{{ $user->active }}</td>
            {{-- <td>{{ $user->Collector->name }}</td> --}}
            <td>{{ $user->created_at }}</td>
            <td>{{ $user->updated_at }}</td>
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
