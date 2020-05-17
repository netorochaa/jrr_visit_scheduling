<div class="card">
    {!! Form::open(['route' => 'collect.public.reserve', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="card-body">
        {{-- COLOCAR INFOBOX EM PÁGINA SEPARADA POIS ESTA SENDO USADA EM DOIS ARQUIVOS --}}
        @if ($sessionActive != null)
            <div class="info-box mb-3 bg-warning">
                <span class="info-box-icon"><i class="fas fa-tag"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Você possui uma solicitação pendente</span>
                    <span class="info-box-number"><h4 class="lead">Nº {{ $sessionActive->id }} | {{ $sessionActive->formatted_date }} ÀS {{ $sessionActive->hour }} | {{ $sessionActive->neighborhood->name }} - {{ $sessionActive->neighborhood->city->name }}</h4></span>
                    <button type="button" onclick="location.href='{{ route('collect.public.schedule', $sessionActive->id) }}'" class="btn btn-secondary">Voltar para finalizar solicitação</button>
                    <button type="button" onclick="location.href='{{ route('collect.public.cancellation', $sessionActive->id) }}'" class="btn btn-danger">Cancelar solicitação</button>
                </div>
                <!-- /.info-box-content -->
            </div>
        @endif
        <h3 class="lead">{{ $neighborhood_model->name }}<small><p class="text-muted" id="describe-feedback"></p></small></h3>
        <div class="row">
            <div class="col-sm-2">
                @include('templates.components.input', ['label' => 'Selecione a data', 'col' => '12', 'input' => 'date', 'value' => '', 'attributes' => ['id' => 'schedulingDate', 'require' => 'true', 'class' => 'form-control', 'autocomplete' => 'off']])
                @include('templates.components.hidden', ['hidden' => 'neighborhood_id', 'value' => $neighborhood_model->id, 'attributes' => ['id' => 'inputNeighborhood']])
                @include('templates.components.hidden', ['hidden' => 'site', 'value' => true])
            </div>
            <div class="col-sm-10">
                @include('templates.components.select', ['label' => 'Selecione um horário e o tipo de coletador', 'col' => '12', 'select' => 'infoCollect', 'data' => [], 'attributes' => ['id' => 'infoCollectSel', 'class' => 'form-control select2bs4', 'disabled' => 'true']])
            </div>
            <p>Prezado cliente, este agendamento será avaliado de acordo com a disponibilidade do laboratório e estará sujeito a alterações.</p>
            <p><b>ANTEÇÃO: Para coleta em domicilio do exame de detecção do COVID-19 você deve entrar em contato com o laboratório pela central telefônica ou Whatsapp.</b></p>
        </div>
    </div>
    <div class="card-footer">
        @include('templates.components.submit', ['input' => 'Avançar', 'attributes' => ['id' => 'submitSelectNeighborhood', 'class' => 'btn btn-outline-primary', 'disabled' => 'true']])
        {{-- Ao cancelar deve-se deixar vago o horário --}}
    </div>
    {!! Form::close() !!}
</div>


