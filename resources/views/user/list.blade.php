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
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->formatted_type }}</td>
            <td>{{ $user->formatted_createdAt }}</td>
            <td>{{ $user->formatted_updatedAt }}</td>
            <td>
                <div class="btn-group">
                  {!! Form::open(['route' => ['user.destroy', $user->id], 'method' => 'DELETE']) !!}
                    <div class="btn-group">
                        <button type="button" onclick="location.href='{{ route('user.edit', $user->id) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button>
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
