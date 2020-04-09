<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
        @foreach ($titlespage as $titles)
            <div class="col-sm-6">
              <h1>
                @if ($goback ?? null)
                  <a href={{ url()->previous() }}><i class="fas fa-arrow-left"></i></a>
                @endif  
                {{ $titles }}
              </h1>
            </div>
        @endforeach
        </div>
      </div>
    </section>