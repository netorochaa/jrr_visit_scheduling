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
                    {{ count($collects->where('user_id_confirmed', Auth::user()->id)) }} <small>coletas</small>
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
                    {{ count($collects->where('user_id', Auth::user()->id)) }} <small>coletas</small>
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
                    {{ count($collects->where('user_id_cancelled', Auth::user()->id)) }} <small>coletas</small>
                </span>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->type > 3)
    <h4 class="lead">Estatísticas deste mês por <b>operadores</b></h4>
    <div class="row">
        @foreach ($users as $user)
            <?php $count = count($collects->where('user_id', $user->id)->where('status', '<', '7')); ?>
            @if ($count < 1)
                <?php continue; ?>
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
    </div>
@endif