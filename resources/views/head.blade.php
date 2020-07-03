<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>RDomiciliar</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('css/fontawesome-free/css/all.min.css') }}">
        <!-- sweetalert2 -->
        <link rel="stylesheet" href="{{ asset('css/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <!-- AdminLTE css -->
        <link rel="stylesheet" href="{{ asset('css/dist/adminlte.min.css') }}">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        {{-- DataTables --}}
        <link rel="stylesheet" href="{{ asset('datatables-bs4/css/dataTables.bootstrap4.min.css') }} ">
        <link rel="stylesheet" href="{{ asset('datatables-responsive/css/responsive.bootstrap4.min.css') }} ">

        @yield('head-distinct')
    </head>
    <body class="hold-transition sidebar-mini">
        @if (session('return'))
            <input type="hidden" id="{{ session('return')['type'] }}" value="{{ session('return')['message'] }}" class="btn btn-info swalDefaultInfo"/>
        @endif
        @if ($errors ?? null)
            @foreach ($errors->all() as $erro)
                <input type="hidden" id="error" value="{{ $erro }}" class="btn btn-info swalDefaultInfo"/>
            @endforeach
        @endif

