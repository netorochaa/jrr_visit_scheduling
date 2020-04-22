<table id="table-{{ $table }}" class="table table-bordered table-striped">
    <thead>
        <tr>
            @foreach ($thead_for_datatable as $ths)
                <th>{{ $ths }}</th>
            @endforeach
            <th class='sorting_desc_disabled sorting_asc_disabled'></th>
        </tr>
    </thead>
    {{-- <tbody>
        @foreach ($collect_list->where('neighborhood_id', '!=', null) as $collectMarked)
        <tr>
            <td>{{ $collectMarked->id }}</td>
            <td>{{ $collectMarked->date }}</td>
            <td>{{ $collectMarked->hour }}</td>
            <td>{{ $collectMarked->poeple->name }}</td>
            <td>{{ $collectMarked->collectType }}</td>
            <td>{{ $collectMarked->status }}</td>
            <td>{{ $collectMarked->payment }}</td>
            <td>{{ $collectMarked->changePayment }}</td>
            <td>{{ $collectMarked->cep }}</td>
            <td>{{ $collectMarked->address }}</td>
            <td>{{ $collectMarked->numberAddress }}</td>
            <td>{{ $collectMarked->complementAddress }}</td>
            <td>{{ $collectMarked->referenceAddress }}</td>
            <td>{{ $collectMarked->linkMaps }}</td>
            <td>{{ $collectMarked->noFee }}</td>
            <td>{{ $collectMarked->unityCreated }}</td>
            <td>{{ $collectMarked->cancellationtype->name }}</td>
            <td>{{ $collectMarked->patienttype->name }}</td>
            <td>{{ $collectMarked->user->name }}</td>
            <td>{{ $collectMarked->collector->name }}</td>
            <td>
                <div class="btn-group">
                   {!! Form::open(['route' => ['collectMarked.destroy', $collectMarked->id], 'method' => 'DELETE']) !!}
                    <div class="btn-group">
                        <button type="button" onclick="location.href='{{ route('collectMarked.edit', $collectMarked->id) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button>
                        @include('templates.components.submit', ['input' => 'Deletar', 'attributes' => ['class' => 'btn btn-danger']])
                    </div>
                    {!! Form::close() !!}
                    </td>
                </div>
              </td>
        </tr>
        @endforeach
    </tbody> --}}
</table>
