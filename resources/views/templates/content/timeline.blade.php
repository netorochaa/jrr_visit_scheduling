<section class="content">
    <div class="container-fluid">
        <div class="row">  
            <div class="col-md-12">
                <div class="timeline">
                    <div class="time-label">
                        <span class="bg-primary">{{ $titlecard ?? null }} 
                            @if($activity->status != '2') <span class="badge badge-warning">{{ $activity->formatted_status }} </span> @endif
                        </span> @if($activity->status == '1') <button type="button"  data-toggle="modal" data-target="#modal-xl" class="btn btn-secondary float-right"> Encerrar rota</button> @endif
                    </div> 
                    {{-- {{ route('activity.cancelled', $activity->id) }} --}}
                    @include( $contentbody )
                </div>
            </div>
        </div>
    </div>
  </section>
</div>