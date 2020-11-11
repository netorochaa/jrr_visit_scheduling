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
  @include('templates.content.content1col', ['contentbody' => 'collector.register'])
@endsection

@section('footer-distinct')
  <script src=" {{ asset('select2/js/select2.full.min.js') }} "></script>
  <script src=" {{ asset('moment/moment.min.js') }}"></script>
  <script src=" {{ asset('daterangepicker/daterangepicker.js') }} "></script>
  <script>
    function changeDateLast(check)
    {
      var input_date_last = document.getElementsByName("date_start_last_modify");
      var select_mondayToFriday = document.getElementById("select_mondayToFriday");
      var select_saturday = document.getElementById("select_saturday");
      var select_sunday = document.getElementById("select_sunday");
      
      if(check.checked)
      {
        input_date_last[0].disabled = true;
        select_mondayToFriday.disabled = true;
        select_saturday.disabled = true;
        select_sunday.disabled = true;
      }
      else
      {
        input_date_last[0].disabled = false;
        select_mondayToFriday.disabled = false;
        select_saturday.disabled = false;
        select_sunday.disabled = false;
      }
    }
    
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

      //Initialize daterangepicker Elements
      $('input[id="dateStart"]').daterangepicker({
        "minDate": moment().add(1, 'days'),
        "singleDatePicker": true,
        locale: {
          "format": "DD/MM/YYYY",
          "separator": " - ",
          "applyLabel": "Aplicar",
          "cancelLabel": "Cancelar",
          "fromLabel": "De",
          "toLabel": "Até",
          "customRangeLabel": "Custom",
          "daysOfWeek": [
              "Dom",
              "Seg",
              "Ter",
              "Qua",
              "Qui",
              "Sex",
              "Sáb"
          ],
          "monthNames": [
              "Janeiro",
              "Fevereiro",
              "Março",
              "Abril",
              "Maio",
              "Junho",
              "Julho",
              "Agosto",
              "Setembro",
              "Outubro",
              "Novembro",
              "Dezembro"
          ],
          "firstDay": 0
        }
      });
    });
  </script>
@endsection
