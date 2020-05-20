<html>
    <head>
        <title>Login | RDomciliar</title>
    <link rel="stylesheet" href="{{ asset('css/stylesheet.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    </head>
    <body>
        @if ($errors ?? null)
            @foreach ($errors->all() as $erro)
                <input type="hidden" id="error" value="{{ $erro }}" class="btn btn-info swalDefaultInfo"/>
            @endforeach
        @endif
        <div class="conteudo-info">
            <img src="{{ asset('img/1.png') }}" alt="">
        </div>
        <section id="conteudo-view">
            <img src="http://roseannedore.com.br/img/logo_roseanelab.png" alt="" width="100%">
            <h1>RDomiciliar</h1>
            <h3>Sistema gerenciador de colestas domiciliares</h3>

            {!! Form::open(['route' => 'auth.login.do', 'method' => 'post']) !!}
                @csrf
                <p>Acesse o sistema
                    <!--<br><small>Caso você não possua credênciais entre em contato com seu superior para solicitar.</small>-->
                </p>
                <label>
                    {!! Form::email('email', 'jose.neto@roseannedore.com.br', ['required' => 'true', 'placeholder' => 'E-mail da empresa']) !!}
                </label>
                <label>
                    {!! Form::password('password', ['required' => 'true', 'placeholder' => 'Senha']) !!}
                </label>
                {!! Form::submit('Entrar', []) !!}
            {!! Form::close() !!}
        </section>
    </body>
    <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 7000
        });

        if(document.getElementById('info')){
            var hiddenInput = document.getElementById('info');
            $(document).ready(function(){
                Toast.fire({
                type: 'info',
                title: hiddenInput.value
                })
            });
        }

        if(document.getElementById('error')){
            var hiddenInput = document.getElementById('error');
            $(document).ready(function(){
                Toast.fire({
                type: 'error',
                title: hiddenInput.value
                })
            });
        }
    </script>
</html>
