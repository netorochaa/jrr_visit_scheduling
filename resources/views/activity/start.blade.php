
<div class="card">
    {!! Form::open(['route' => 'activity.index', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            <h4>{{ count($collect_list) }} COLETAS PARA HOJE - {{ $collector->name }} <small>{{ Auth::user()->name }}</small></h4>
        </div>
        <div class="row">
            @include('templates.components.submit', ['input' => 'INICIAR ATIVIDADE', 'attributes' => ['class' => 'btn btn-outline-primary']])  
        </div>
    </div>
    {!! Form::close() !!}
</div>
