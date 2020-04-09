@extends('templates.master')

@section('head-distinct')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('datatables-bs4/css/dataTables.bootstrap4.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('datatables-responsive/css/responsive.bootstrap4.min.css') }} ">
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
    <script src=" {{ asset('js/datatables/jquery.dataTables.min.js') }} "></script>
    <script src=" {{ asset('datatables-bs4/js/dataTables.bootstrap4.min.js') }} "></script>
    <script src=" {{ asset('datatables-responsive/js/dataTables.responsive.min.js') }} "></script>
    <script src=" {{ asset('datatables-responsive/js/responsive.bootstrap4.min.js') }} "></script>
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
        });
        $(function () {
          $('#table-{{ $table2 }}').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true,
          });
        });

    </script>
@endsection
