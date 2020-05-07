
<div class="card">
    {!! Form::open(['route' => 'activity.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            <h3 class="lead"> <b>{{ count($collect_list) }}</b> Coletas para hoje <b>{{ $date }}</b> | {{ $collector->name }} - <SMALL>{{ Auth::user()->name }}</SMALL></h3>
            @include('templates.components.hidden', ['hidden' => 'collector_id', 'value' => $collector->id])
            @include('templates.components.hidden', ['hidden' => 'user_id', 'value' =>  Auth::user()->id])
        </div>    
        <div class="row">
            <input type="submit" value="CLIQUE AQUI PARA COMEÇAR" class="btn btn-primary">
        </div>
    </div>
    {!! Form::close() !!}
</div>