@extends('templates.public')

@section('content')
  @include('templates.content.header')
    <div class="card">
        <div class="card-body" >
            <div class="row">
                <div style="text-align: center">
                    @if (session('return'))
                        @if(session('return')['type'] == 'error')
                            <h1 style="color: red">{{ session('return')['message'] }}</h1>
                        @else
                            <h1 style="color: lightgreen">{{ session('return')['message'] }}</h1>
                            <p>{{ session('return')['text'] }}</p>
                        @endif
                        <h3 class="lead">Prezado cliente, este agendamento será avaliado de acordo com a disponibilidade e estará sujeito a alterações na data ou horário.<br>
                        O AGENDAMENTO PARA DETECÇÃO DO COVID-19 NÃO ESTA SENDO REALIZADO POR MEIO DESTE FORMULÁRIO, É NECESSÁRIO QUE O CLIENTE ENTRE EM CONTATO COM O LABORATÓRIO PELA CENTRAL TELEFÔNICA OU WHATSAPP.</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


