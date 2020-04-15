@extends('templates.master')

@section('head-distinct')
  {{-- daterangepicker --}}
  <link rel="stylesheet" href="{{ asset('daterangepicker/daterangepicker.css') }} ">
  <link rel="stylesheet" href="{{ asset('tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  {{-- Select2 --}}
  <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }} ">
  <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
  @include('templates.content.header')  
  @include('templates.content.content1col', ['contentbody' => 'collector.list'])
  @include('templates.content.modallarge', ['titlemodal' => $titlemodal , 'contentmodal' => 'collector.register'])
@endsection

@section('footer-distinct')
  <script src=" {{ asset('select2/js/select2.full.min.js') }} "></script>
  <script src=" {{ asset('moment/moment.min.js') }}"></script>
  <script src=" {{ asset('js/inputmask/min/jquery.inputmask.bundle.min.js') }} "></script>
  <script src=" {{ asset('daterangepicker/daterangepicker.js') }} "></script>
  <script src=" {{ asset('tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
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

      //Timepicker
      $('#startTime').datetimepicker({
        format: 'HH:mm',
        pickDate: false,
        pickSeconds: false,
        pick12HourFormat: false
      });
      $('#endTime').datetimepicker({
        format: 'HH:mm',
        pickDate: false,
        pickSeconds: false,
        pick12HourFormat: false
      });

      //Datemask dd/mm/yyyy
      $('[data-mask]').inputmask('', {'placeholder': ''})
    });
  </script>
@endsection
