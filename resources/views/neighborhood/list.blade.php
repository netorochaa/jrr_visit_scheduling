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
        @foreach ($neighborhoods as $neighborhood)
        <tr>
            <td>{{ $neighborhood->name }}</td>
            <td>{{ $neighborhood->displacementRate }}</td>
            <td>{{ $neighborhood->formatted_region }}</td>
            <td>{{ $neighborhood->city->name }}</td>
            <td>{{ $neighborhood->formatted_createdAt }}</td>
            <td>{{ $neighborhood->formatted_updatedAt }}</td>
            <td>
                <div class="btn-group">
                  {!! Form::open(['route' => ['neighborhood.destroy', $neighborhood->id], 'method' => 'DELETE']) !!}
                    <div class="btn-group">
                        <button type="button" onclick="location.href='{{ route('neighborhood.edit', $neighborhood->id) }}'" class="btn btn-info btn-sm"><i class='fas fa-pen'></i></button>
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
