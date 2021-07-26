
<h4 class="lead">Período: {{ $period_get ?? "Nenhuma data informada" }}</h4>
<hr>
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