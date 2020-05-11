<table id="table-{{ $table2 }}" class="table table-bordered table-striped">
    <thead>
        <tr>
            @foreach ($thead_for_datatable2 as $ths)
                <th>{{ $ths }}</th>
            @endforeach
            <th class='sorting_desc_disabled sorting_asc_disabled'></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cities_list as $city)
        <tr>
            <td>{{ $city->name }}</td>
            <td>{{ $city->UF }}</td>
            <td>{{ $city->formatted_createdAt }}</td>
            <td>
                <div class="btn-group">
                  {!! Form::open(['route' => ['city.destroy', $city->id], 'method' => 'DELETE']) !!}
                    <div class="btn-group">
                        <button type="button" onclick="location.href='{{ route('city.edit', $city->id) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button>
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
