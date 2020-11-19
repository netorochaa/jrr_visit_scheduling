{!! Form::open(['route' => 'auth.home', 'method' => 'get']) !!}
    <div class="row">
        <div class="col-sm-2">
            <div class="form-group">
                <select name="rangedate" class="custom-select" style="float: left">
                    <option value="7">Últimos 7 dias</option>
                    <option value="30">Últimos 30 dias</option>
                    <option value="90">Últimos 3 meses</option>
                </select>
            </div>
        </div>
        <div class="col-sm-2">
            @include('templates.components.submit', ['input' => 'Filtrar', 'attributes' => ['class' => 'btn btn-outline-secondary']])
        </div>
    </div>
{!! Form::close() !!}
<h4 class="lead">Suas estatísticas este mês</h4>
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1">
                <i class="fas fa-vial"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Confirmadas</span>
                <span class="info-box-number">
                    {{ $collects->where('user_id_confirmed', Auth::user()->id)->count() }} <small>coletas</small>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning elevation-1">
                <i class="fas fa-vial"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Reservadas</span>
                <span class="info-box-number">
                    {{ $collects->where('user_id', Auth::user()->id)->count() }} <small>coletas</small>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger elevation-1">
                <i class="fas fa-vial"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Canceladas</span>
                <span class="info-box-number">
                    {{ $collects->where('user_id_cancelled', Auth::user()->id)->count() }} <small>coletas</small>
                </span>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->type > 3)
    <h4 class="lead">Estatísticas gerais de coletas</h4>
    <!-- /.form group -->
    <div class="row">
        <div class="col-sm-8">
            <div class="card card-secondary">
                <div class="card-header">
                    <h5 class="card-title">Quantitativo de coletas</h5>
                    <div class="card-tools pull-right">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        @if(!empty($barChatQtd))
                            {!! $barChatQtd->render() !!}
                        @endif
                    </div>
                </div>
            </div>        
        </div>
    </div>
    {{-- <div class="row">
        @foreach ($users as $user)
            <?php //$count = count($collects->where('user_id', $user->id)->where('status', '<', '7')); ?>
            @if ($count < 1)
                <?php //continue; ?>
            @else
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary elevation-1">
                            <i class="fas fa-vial"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ $user->name }}</span>
                            <span class="info-box-number">
                                {{ $count }} <small>coletas marcadas</small>
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div> --}}

    @push('scripts-bar-chat-qtd')
        <!-- ChartJS 1.0.1 -->
        <script src="{{ asset('js/ChartJS/Chart.min.js') }}"></script>
        <script src="{{ asset('moment/moment.min.js') }}"></script>
        <script src="{{ asset('daterangepicker/daterangepicker.js') }}"></script>
        <script src="{{ asset('tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

        <script>
            $(function (){
                //Date range as a button
                $('#daterange-btn').daterangepicker(
                {
                    ranges   : {
                        'Últimos 7 dias' : [moment().subtract(6, 'days'), moment()],
                        'Últimos 30 dias' : [moment().subtract(29, 'days'), moment()],
                        'Este mês' : [moment().startOf('month'), moment().endOf('month')],
                        'Últimos 3 meses' : [moment().subtract(3, 'month').startOf('month'), moment().endOf('month')]
                    },
                    startDate: moment().subtract(7, 'days'),
                    endDate  : moment()
                })
            });
        </script>
    @endpush
@endif