
<div class="card">
    {!! Form::open(['route' => 'activity.show', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            @include('templates.components.submit', ['input' => 'Continuar', 'attributes' => ['class' => 'btn btn-outline-primary']])  
        </div>
    </div>
    {!! Form::close() !!}
</div>
