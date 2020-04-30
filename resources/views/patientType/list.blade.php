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
        @foreach ($patientTypes as $patientType)
        <tr>
            <td>{{ $patientType->name }}</td>
            <td>{{ $patientType->formatted_needResponsible }}</td>
            <td>{{ $patientType->formatted_active }}</td>
            <td>{{ $patientType->created_at }}</td>
            <td>{{ $patientType->updated_at }}</td>
            <td>
                <div class="btn-group">
                  {!! Form::open(['route' => ['patienttype.destroy', $patientType->id], 'method' => 'DELETE']) !!}
                    <div class="btn-group">
                        {{-- <button type="button" onclick="location.href='{{ route('patientType.edit', $patientType->id) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button> --}}
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
