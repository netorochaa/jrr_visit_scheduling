@extends('templates.master')

@section('head-distinct')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('datatables-bs4/css/dataTables.bootstrap4.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('datatables-responsive/css/responsive.bootstrap4.min.css') }} ">
@endsection

@section('content')
  {{-- @if (\Session::has('message'))
      <h1>deu certo!</h1>
  @endif --}}
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'user.list'])
  @include('templates.content.modallarge', ['titlemodal' => $titlemodal , 'contentmodal' => 'user.register'])
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
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
          });
        });
        
    </script>
@endsection