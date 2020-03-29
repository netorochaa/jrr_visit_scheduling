<html>
    <head>
        <title>Login | RDomciliar</title>
    <link rel="stylesheet" href="{{ asset('css/stylesheet.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One&display=swap" rel="stylesheet">
    </head>
    <body>
        <section id='conteudo-view'>
            <img src="http://roseannedore.com.br/img/logo_roseanelab.png" alt="" width="100%">
            <h1>RDomiciliar</h1>
            <h3>Sistema gerenciador de colestas domiciliares</h3>

            {!! Form::open(['route' => 'auth.login', 'method' => 'post']) !!}
            <p>Acesse o sistema
                <!--<br><small>Caso você não possua credênciais entre em contato com seu superior para solicitar.</small>-->
            </p>
            <label>
                {!! Form::text('user', null, ['placeholder' => 'Login']) !!}
            </label>  
            <label>
                {!! Form::password('password', ['placeholder' => 'Senha']) !!}
            </label>
            
            {!! Form::submit('Entrar', []) !!}
            {!! Form::close() !!}
        </section>
    </body>
</html>