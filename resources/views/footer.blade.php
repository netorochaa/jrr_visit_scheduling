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
    <script src=" {{ asset('js/inputmask/min/jquery.inputmask.bundle.min.js') }} "></script>
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

        // MASK FOR PAGE COLLECT.PERSON
        $(function () {
            //Datemask 00,00
            $('[data-mask]').inputmask('', {'placeholder': '00,00'})
            $('[data-cep]').inputmask('', {'placeholder': '00000-000'})
            $('[data-date]').inputmask('', {'placeholder': '00/00/0000'})
            $('#data-os').inputmask('99[9]-99999-99[9]')
            $('[data-cpf]').inputmask('', {'placeholder': '00000000000'})
            $('[data-fone]').inputmask('', {'placeholder': '(00) 00000-0000)'})
        });
    </script>
    @yield('footer-distinct')
</html>
