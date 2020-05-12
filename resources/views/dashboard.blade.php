<h4 class="lead">Suas estatísticas</h4>
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1">
                <i class="fas fa-vial"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Confirmadas neste mês</span>
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
                <span class="info-box-text">Aguardando confirmação neste mês</span>
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
                <span class="info-box-text">Canceladas neste mês</span>
                <span class="info-box-number">
                    {{ count($collects->where('user_id_cancelled', Auth::user()->id)) }} <small>coletas</small>
                </span>
            </div>
        </div>
    </div>
</div>
