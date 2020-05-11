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
        @foreach ($cancellationTypes as $cancellationType)
        <tr>
            <td>{{ $cancellationType->name }}</td>
            <td>{{ $cancellationType->formatted_createdAt }}</td>
            <td>
                <div class="btn-group">
                  {!! Form::open(['route' => ['cancellationtype.destroy', $cancellationType->id], 'method' => 'DELETE']) !!}
                    <div class="btn-group">
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
