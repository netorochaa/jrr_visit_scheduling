
<div class="card">
    @if ($neighborhood ?? null)
        {!! Form::model($neighborhood, ['route' => ['neighborhood.update', $neighborhood->id], 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @else
        {!! Form::open(['route' => 'neighborhood.store', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    @endif
        <div class="card-body">
            <div class="row">
                @include('templates.components.input', ['label' => 'Nome do bairro',  'col' => '8', 'input' => 'name',  'attributes' => ['require' => 'true', 'class' => 'form-control']])
                @include('templates.components.input', ['label' => 'Taxa', 'col' => '4', 'input' => 'displacementRate',  'attributes' => ['require' => 'true', 'class' => 'form-control', 'data-inputmask' => "'mask': '99.99'", 'data-mask', 'im-insert' => 'true']])
            </div>
            <div class="row">
                <div class="col-sm-6">
                    @include('templates.components.select', ['label' => 'Região', 'col' => '12', 'select' => 'region', 'data' => $regions_list, 'attributes' => ['require' => 'true', 'class' => 'form-control']])
                </div>
                <div class="col-sm-6">
                    @include('templates.components.select', ['label' => 'Cidade', 'col' => '12', 'select' => 'city_id', 'data' => $cities_pluck_list, 'attributes' => ['require' => 'true', 'class' => 'form-control']])
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('templates.components.submit', ['input' => 'Salvar', 'attributes' => ['class' => 'btn btn-outline-primary']])
        </div>
    {!! Form::close() !!}
</div>
