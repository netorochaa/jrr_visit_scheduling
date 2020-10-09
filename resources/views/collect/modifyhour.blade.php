<div class="card">
    {!! Form::open(['route' => ['collect.modifyhour', $collect->id], 'method' => 'get', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            <h3 class="lead">{{ $collect->id }} |
                Status: <b>{{ $collect->formatted_status }}</b> |
                Coletador: {{ $collect->collector->name }} |
                Dia: {{ $collect->formatted_date }}
            </h3>
        </div>
        <div class="row">
            <h2>Horário: <b>{{ $collect->hour }}</b></h2>
        </div>
        <hr>
        <div class="row">
            <!-- time Picker -->
            <div class="bootstrap-timepicker">
                <div class="form-group">
                <label>Novo horário da coleta:</label>

                <div class="input-group date" id="timepicker" data-target-input="nearest">
                    <input type="text" name="hour" class="form-control datetimepicker-input" data-target="#timepicker" required/>
                    <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                    </div>
                    </div>
                <!-- /.input group -->
                </div>
                <!-- /.form group -->
            </div>
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Continuar', 'attributes' => ['id' => 'submitSelectNeighborhood', 'class' => 'btn btn-outline-primary']])
    </div>
    {!! Form::close() !!}
</div>
