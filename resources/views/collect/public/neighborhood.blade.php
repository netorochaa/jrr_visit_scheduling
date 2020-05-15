<div class="card">
    {!! Form::open(['route' => 'public.index', 'method' => 'get', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        @if ($sessionActive != null)
            <div class="info-box mb-3 bg-warning">
                <span class="info-box-icon"><i class="fas fa-tag"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Você possui um agendamento pendente</span>
                    <span class="info-box-number"><h4 class="lead">Nº {{ $sessionActive->id }} | {{ $sessionActive->formatted_date }} ÀS {{ $sessionActive->hour }} | {{ $sessionActive->neighborhood->name }} - {{ $sessionActive->neighborhood->city->name }}</h4></span>
                    <button type="button" onclick="location.href='{{ route('collect.public.schedule', $sessionActive->id) }}'" class="btn btn-secondary">Voltar para finalizar agendamento</button>
                    <button type="button" onclick="location.href='{{ route('collect.public.cancellation', $sessionActive->id) }}'" class="btn btn-danger">Cancelar agendamento</button>
                </div>
                <!-- /.info-box-content -->
            </div>
        @endif
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


