@extends('templates.master')

@section('head-distinct')
  <link rel="stylesheet" href="{{ asset('tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endsection

@section('content')
  @include('templates.content.header')
  @include('templates.content.content2col', [
    'contentbody' => 'neighborhood.list', 
    'contentbody2' => 'city.list',
    ])
  @include('templates.content.modallarge', [
    'titlemodal'   => $titlemodal , 
    'contentmodal' => 'neighborhood.register',
    'titlemodal2' => $titlemodal2, 
    'contentmodal2' => 'city.register'
    ])
@endsection

@section('footer-distinct')
  <script src=" {{ asset('js/inputmask/min/jquery.inputmask.bundle.min.js') }} "></script>
  <script src=" {{ asset('tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script>
        $(function () {
          $('#table-{{ $table }}').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
          });
          $('#table-{{ $table2 }}').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true,
          });
          //Datemask 00,00
          $('[data-mask]').inputmask('', {'placeholder': '00,00'})
        });

    </script>
@endsection
