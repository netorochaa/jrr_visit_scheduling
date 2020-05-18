<section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title left">{{ $titlecard ?? null }}</h3>
            @if ($add ?? null)
                <button type="button" data-toggle="modal" data-target="#modal-xl" style="float: right!important" class="btn btn-outline-primary">
                    @if($filtersReport ?? null)
                        <i class="fas fa-filter"></i>
                    @else
                        <i class="fas fa-plus"></i>
                    @endif
                </button>
            @endif
          </div>
          <div class="card-body">
            @include( $contentbody )
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
