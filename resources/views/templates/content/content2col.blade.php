<section class="content">
    <div class="row">
      <div class="col-8">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title left">{{ $titlecard }}</h3>
            @if ($add ?? null) <button type="button" data-toggle="modal" data-target="#modal-xl" style="float: right!important" class="btn btn-outline-primary"><i class="fas fa-plus"></i></button>@endif
          </div>
          <div class="card-body">
            @include( $contentbody )
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title left secondary">{{ $titlecard2 }}</h3>
            @if ($add ?? null) <button type="button" data-toggle="modal" data-target="#modal-lg" style="float: right!important" class="btn btn-outline-primary"><i class="fas fa-plus"></i></button>@endif
          </div>
          <div class="card-body">
            @include( $contentbody2 )
          </div>
        </div>
      </div>
    </div>
  </section>
</div>