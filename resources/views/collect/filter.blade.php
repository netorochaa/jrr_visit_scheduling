@isset($_GET['typeSearch'])
    <h5 class="text-info">Busca realizada por
        <?php $type = null; $explodeType = explode('.', $_GET['typeSearch']); ?>
        @switch($explodeType[1])
            @case('name')
                {{ $type = "NOME" }}
                @break
            @case('date')
                {{ $type = "DATA" }}
                @break
            @case('address')
                {{ $type = "RUA" }}
                @break
            @default
                {{ $type = strtoupper($explodeType[1]) }}
                @break
        @endswitch
     pelo valor {{ $_GET['value'] }}</h5>
@endisset
<div class="row">
    <small><p class="text-muted" id="status-find-collect"></p></small>
</div>
{!! Form::open(['route' => 'collect.list.allcollects', 'method' => 'get', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="row">
        @include('templates.components.select', ['label' => 'Pesquisar por',    'col' => '2', 'select' => 'typeSearch', 'attributes' => ['id' => 'selTypeSearchCollect', 'class' => 'form-control'], 'data' => $search_list])
        @include('templates.components.input',  ['label' => 'Digite a procura', 'col' => '8', 'input'  => 'value', 'attributes' => ['id' => 'searchCollect', 'class' => 'form-control']])
    </div>
    <div class="row">
        @include('templates.components.submit', ['input' => 'Procurar', 'attributes' => ['id' => 'submitFindCollect', 'class' => 'btn btn-primary', 'disabled' => 'true']])
    </div>
{!! Form::close() !!}
