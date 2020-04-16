@extends('templates.master')

@section('head-distinct')
  {{-- Select2 --}}
  <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }} ">
  <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  {{-- DateRange --}}
  <link rel="stylesheet" href="{{ asset('daterangepicker/daterangepicker.css') }} ">
@endsection

@section('content')
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'freedays.list'])
  @include('templates.content.modallarge', ['titlemodal' => $titlemodal , 'contentmodal' => 'freedays.register'])
@endsection

@section('footer-distinct')
  <script src=" {{ asset('select2/js/select2.full.min.js') }} "></script>
  <script src=" {{ asset('moment/moment.min.js') }}"></script>
  <script src=" {{ asset('daterangepicker/daterangepicker.js') }} "></script>
  <script>
      $(function () {
        //Initialize Select2 Elements
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
        $('input[name="dateRange"]').daterangepicker({
          "timePicker": true,
          "timePicker24Hour": true,
          locale: {
            format: 'DD/MM/YYYY HH:mm'
          }
        });
      });

      function activeSelect()
      {
        var tipo = document.getElementById('type');
        var collector = document.getElementById('selectCollector');
        var city = document.getElementById('selectCity');

        // console.log()

        if (tipo.value == 1) {
          city.style.display = 'none';
          city.disabled = true;
          collector.style.display = 'block';
          collector.disabled = false;
        } else {
          collector.style.display = 'none';
          collector.disabled = true;
          city.style.display = 'block';
          city.disabled = false;
        }
      }
      
  </script>
@endsection
