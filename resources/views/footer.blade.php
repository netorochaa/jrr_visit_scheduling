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
    {{-- DataTables --}}
    <script src=" {{ asset('js/datatables/jquery.dataTables.min.js') }} "></script>
    <script src=" {{ asset('datatables-bs4/js/dataTables.bootstrap4.min.js') }} "></script>
    <script src=" {{ asset('datatables-responsive/js/dataTables.responsive.min.js') }} "></script>
    <script src=" {{ asset('datatables-responsive/js/responsive.bootstrap4.min.js') }} "></script>

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
    @yield('footer-distinct')
</html>
