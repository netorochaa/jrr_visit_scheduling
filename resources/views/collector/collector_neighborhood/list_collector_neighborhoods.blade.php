<table id="table-collector_neighborhood" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Bairro</th>
            <th>Cidade</th>
            <th class='sorting_desc_disabled sorting_asc_disabled'></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collector->neighborhoods as $neighborhoods)
        <tr>
            <td>{{ $neighborhoods->name }}</td>
            <td>{{ $neighborhoods->city->name }} - {{ $neighborhoods->city->UF }}</td>
            <td>
                <div class="btn-group">
                    {!! Form::open(['route' => ['collector.neighborhoods.detach', $collector->id, $neighborhoods->id]]) !!}
                      <div class="btn-group">
                          {{-- <button type="button" onclick="location.href='{{ route('collector.edit', $collector->id) }}'" class="btn btn-info"  ><i class='fas fa-pen'></i></button> --}}
                          @include('templates.components.submit', ['input' => 'Deletar', 'attributes' => ['class' => 'btn btn-danger']])
                      </div>
                    {!! Form::close() !!}
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
