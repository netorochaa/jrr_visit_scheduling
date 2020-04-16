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
  @include('templates.content.content1col', ['contentbody' => 'collect.list'])
  @include('templates.content.modallarge', ['titlemodal' => $titlemodal , 'contentmodal' => 'collect.register'])
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
        $('input[id="schedulingDate"]').daterangepicker({
          "singleDatePicker": true,
          "startDate": moment(),
          locale: {
            format: 'DD/MM/YYYY dd',
            "daysOfWeek": [
                "Dom",
                "Seg",
                "Ter",
                "Qua",
                "Qui",
                "Sex",
                "Sab"
            ]
          },
        });
      });

      function verificateDate()
      {
        var schedulingDate = document.getElementById("schedulingDate");
        var schedulingDateSplit = schedulingDate.value.split(" ");
        var dateSplit = schedulingDateSplit[0].split("/");
        var day = dateSplit[0];
        var month = dateSplit[1];
        var year = dateSplit[2];
        var dateCorrect = year + "-" + month + "-" + day;

        var dateSchedule = moment(dateCorrect);
        var dateNow = moment().format("YYYY-MM-DD");
        //console.log(dateSchedule.isBefore(dateNow));

        if(dateSchedule.isBefore(dateNow))
        {
          $('input[id="schedulingDate"]').daterangepicker({
            "startDate": moment(),
            "singleDatePicker": true,
            locale: {
              format: 'DD/MM/YYYY dd',
              "daysOfWeek": [
                  "Dom",
                  "Seg",
                  "Ter",
                  "Qua",
                  "Qui",
                  "Sex",
                  "Sab"
              ]
            },
          });
        }

      }



  </script>
@endsection
