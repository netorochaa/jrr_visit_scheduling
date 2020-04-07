@include('head')
    <div class="wrapper">
        @if (session('return'))
            <input type="hidden" id="{{ session('return')['type'] }}" value="{{ session('return')['message'] }}" class="btn btn-info swalDefaultInfo"/>
        @endif
        @include('templates.navbar')
        @include('templates.menuside')
        @yield('content')
        @include('templates.bottom')
    </div>
@include('footer')
