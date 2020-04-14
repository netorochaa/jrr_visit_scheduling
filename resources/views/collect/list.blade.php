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
        @foreach ($collects as $collect)
        <tr>
            <td>{{ $collect->id }}</td>
            <td>{{ $collect->date }}</td>
            <td>{{ $collect->hour }}</td>
            {{-- <td>{{ $collect->poeple }}</td> --}}
            <td>{{ $collect->collectType }}</td>
            <td>{{ $collect->status }}</td>
            <td>{{ $collect->payment }}</td>
            <td>{{ $collect->changePayment }}</td>
            <td>{{ $collect->cep }}</td>
            <td>{{ $collect->address }}</td>
            <td>{{ $collect->numberAddress }}</td>
            <td>{{ $collect->complementAddress }}</td>
            <td>{{ $collect->referenceAddress }}</td>
            <td>{{ $collect->linkMaps }}</td>
            <td>{{ $collect->noFee }}</td>
            <td>{{ $collect->unityCreated }}</td>
            {{-- <td>{{ $collect->cancellationtype }}</td>
            <td>{{ $collect->patienttype }}</td>
            <td>{{ $collect->user }}</td>
            <td>{{ $collect->collector }}</td> --}}
            <td>
                <div class="btn-group">
                  {!! Form::open(['route' => ['collect.destroy', $freedays->id], 'method' => 'DELETE']) !!}
                    <div class="btn-group">
                        {{-- <button type="button" onclick="location.href='{{ route('freedays.edit', $freedays->id) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button> --}}
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
