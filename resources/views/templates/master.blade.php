@include('head')
    <div class="wrapper">
        @if (\Session::has('message'))
            <input type="hidden" id="info" value="{{ \Session::get('message') }}" class="btn btn-info swalDefaultInfo"/>
        @endif
        @include('templates.navbar')
        @include('templates.menuside')
        @yield('content')
        @include('templates.bottom')
    </div>
@include('footer')
