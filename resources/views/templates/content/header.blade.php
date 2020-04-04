<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
        @foreach ($titlespage as $titles)
            <div class="col-sm-6">
                <h1>{{ $titles }}</h1>
            </div>
        @endforeach
        </div>
      </div>
    </section>