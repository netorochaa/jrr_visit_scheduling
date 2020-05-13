<div class="card">
    {!! Form::open(['route' => 'collect.public', 'method' => 'get', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <label>Selecione o bairro da coleta</label>
                <div clas="col-12 form-group">
                    <select name="neighborhood" class="form-control select2bs4" style="width: 100%;">
                        @foreach ($neighborhood_list as $neighborhood)
                            <option value="{{ $neighborhood->id }}">
                               {{ $neighborhood->name }}, {{ $neighborhood->city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Avançar', 'attributes' => ['class' => 'btn btn-outline-primary']])
    </div>
    {!! Form::close() !!}
</div>


