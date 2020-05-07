<div class="card">
    {!! Form::open(['route' => ['activity.close', $activity->id], 'method' => 'get', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        <div class="row">
            @include('templates.components.textarea', ['label' => 'Motivo do cancelamento', 'col' => '12', 'input'  => 'reasonCancellation', 'attributes' => ['required' => 'true', 'class' => 'form-control', 'rows' => 3]])
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
    </div>
    {!! Form::close() !!}
</div>
