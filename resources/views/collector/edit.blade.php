@extends('templates.master')

@section('head-distinct')
  {{-- Select2 --}}
  <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }} ">
  <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'collector.register'])
@endsection

@section('footer-distinct')
  <script src=" {{ asset('select2/js/select2.full.min.js') }} "></script>
  <script>
    $(function () {
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
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
