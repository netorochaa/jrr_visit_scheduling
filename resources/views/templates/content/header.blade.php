<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            @if($titlespage ?? null)
                @foreach ($titlespage as $titles)
                    <div class="col-sm-6">
                        <h1>
                            @if($ambulancy ?? null)
                                <i class="fas fa-ambulance"></i>
                            @endif
                            @if ($goback ?? null)
                                <a href={{ url()->previous() }}><i class="fas fa-arrow-left"></i></a>
                            @endif
                            {{ $titles ?? null }}
                        </h1>
                    </div>
                @endforeach
            @endif
        </div>
      </div>
    </section>
