@extends('templates.public')

@section('content')
  @include('templates.content.header')
    <div class="card">
        <div class="card-body" >
            <div class="row">
                <div style="text-align: center">
                    <img src="http://roseannedore.com.br/img/logo_roseanelab.png"
                        alt="Laboratório Roseanne Dore" width="200px">
                    @if (session('return'))
                        @if(session('return')['type'] == 'error')
                            <h1 style="color: red">{{ session('return')['message'] }}</h1>
                        @elseif(session('return')['type'] == 'info')
                            <h1 style="color: lightgreen">{{ session('return')['message'] }}</h1>
                            <p>{{ session('return')['describe'] }}</p>
                            <p>{{ session('return')['text'] }}</p>
                            <h2>Em breve o laboratório entrará em contato pelo telefone ou e-mail para CONFIRMAR seu agendamento.</h2>
                            {{-- <h3 class="lead">
                                Prezado cliente, este agendamento será avaliado de acordo com a disponibilidade e estará sujeito a alterações na data ou horário.<br><br>
                                O AGENDAMENTO PARA DETECÇÃO DO COVID-19 NÃO ESTA SENDO REALIZADO POR MEIO DESTE FORMULÁRIO, É NECESSÁRIO QUE O CLIENTE ENTRE EM CONTATO COM O
                                LABORATÓRIO PELA CENTRAL TELEFÔNICA OU WHATSAPP.
                            </h3> --}}
                        @elseif(session('return')['type'] == 'confirmed')
                            <h1 style="color: darkblue">{{ session('return')['message'] }}</h1>
                            <p>{{ session('return')['describe'] }}</p>
                            <p>{{ session('return')['text'] }}</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


