<section class="content">
    <div class="container-fluid">
        <div class="row">  
            <div class="col-md-12">
                <div class="timeline">
                    <div class="time-label">
                        <span class="bg-blue">{{ $titlecard ?? null }}</span>
                        @if ($notStart ?? null) <button type="button" style="float: right!important" class="btn btn-outline-primary">Iniciar atividade</button>@endif
                    </div>
                    <div class="card-body">
                        @include( $contentbody )
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>
</div>