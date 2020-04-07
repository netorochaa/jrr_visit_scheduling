    </body>
    <!-- jQuery -->
    <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- sweetalert2 -->
    <script src="{{ asset('js/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('js/dist/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('js/dist/demo.js') }}"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
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
    </script>
    @yield('footer-distinct')
</html>
