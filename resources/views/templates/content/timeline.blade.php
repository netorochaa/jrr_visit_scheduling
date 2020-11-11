<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if(Auth::user()->type > 2)
                    <div class="card">
                        {!! Form::open(['route' => 'activity.index', 'method' => 'get', 'role' => 'form', 'class' => 'form-horizontal']) !!}
                            <div class="card-body">
                                <div class="row">
                                    @include('templates.components.select', ['label' => 'Coletador', 'col' => '4', 'select' => 'collector', 'data' => $collector_list->pluck('name', 'id'), 'attributes' => ['require' => 'true', 'class' => 'form-control']])
                                    @include('templates.components.input',  ['label' => 'Data',   'col' => '4', 'input'  => 'dateConsult',  'attributes' => ['required' => 'true', 'class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'data-date', 'im-insert' => 'true']])
                                </div>
                            </div>
                            <div class="card-footer">
                                @include('templates.components.submit', ['input' => 'Consultar', 'attributes' => ['class' => 'btn btn-outline-primary']])
                            </div>
                        {!! Form::close() !!}
                    </div>
                @endif
                @if($activity ?? null)
                    <div class="timeline">
                        <div class="time-label">
                            <span class="bg-primary">{{ $titlecard ?? null }}</span>
                            @if($activity->status == '1') <button type="button"  data-toggle="modal" data-target="#modal-xl" class="btn btn-danger float-right"> Encerrar com pendências</button> @endif<br>
                            @if($activity->status != '2') <span class="badge badge-warning">{{ $activity->formatted_status }} @endif</span>
                        </div>
                        @include( $contentbody )
                    </div>
                @else
                    <h3 style="text-align: center">COLETADOR SEM ROTA OU Nﾃグ INCIADA NA DATA!</h3>
                @endif
            </div>
        </div>
    </div>
  </section>
</div>
