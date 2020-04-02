<div class="panel box box-default">
  <div class="box-header with-border">
      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
        {{ $title }}
      </a>
  </div>
  <div id="collapseTwo" class="panel-collapse collapse">
    <div class="box-body">
      <ol>
      @foreach($unity->sectors as $sectorsInUnity)
        <li>{{ $sectorsInUnity->name }}</li>
      @endforeach
      </ol>
    </div>
  </div>
</div>
