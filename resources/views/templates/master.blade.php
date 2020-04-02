@include('head')
    <div class="wrapper">
        @include('templates.navbar')
        @include('templates.menuside')
        @yield('content')
        @include('templates.bottom')
    </div>
@include('footer')
