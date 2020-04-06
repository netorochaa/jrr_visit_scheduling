@extends('templates.master')

@section('head-distinct')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('datatables-bs4/css/dataTables.bootstrap4.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('datatables-responsive/css/responsive.bootstrap4.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('daterangepicker/daterangepicker.css') }} ">
    <link rel="stylesheet" href="{{ asset('tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endsection

@section('content')
  {{-- @if (\Session::has('message'))
      <h1>deu certo!</h1>
  @endif --}}
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'collector.list'])
  @include('templates.content.modallarge', ['titlemodal' => $titlemodal , 'contentmodal' => 'collector.register'])
@endsection

@section('footer-distinct')
    <script src=" {{ asset('js/datatables/jquery.dataTables.min.js') }} "></script>
    <script src=" {{ asset('datatables-bs4/js/dataTables.bootstrap4.min.js') }} "></script>
    <script src=" {{ asset('datatables-responsive/js/dataTables.responsive.min.js') }} "></script>
    <script src=" {{ asset('datatables-responsive/js/responsive.bootstrap4.min.js') }} "></script>
    <script src=" {{ asset('moment/moment.min.js') }}"></script>
    <script src=" {{ asset('js/inputmask/min/jquery.inputmask.bundle.min.js') }} "></script>
    <script src=" {{ asset('daterangepicker/daterangepicker.js') }} "></script>
    <script src=" {{ asset('tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
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

        function montaHorarios()
        {
          var interval = document.getElementById('interval');
          var initial = document.getElementById('inputStartTime');
          var end = document.getElementById('inputEndTime');
          var description = document.getElementById('descriptionHour');

          if(interval.value < 10) interval.value = 10;

          if(interval.value >= 10)
          {
            if(initial.value != "" && end.value != "")
            {
              var hourIni = moment(initial.value, 'HH:mm');
              var hourEnd = moment(end.value, 'HH:mm');

              console.log(hourIni.hours());
              console.log(hourEnd.hours());

              // if(hourIni.isValid() && hourEnd.isValid()){
                while (hourIni.isBefore(hourEnd)) 
                {
                  hourIni.add(interval.value, 'minutes');
                  console.log(hourIni.inspect() + "\n");
                }
              // }else{
              //   console.log("Campos não válidos");
              // }
            }else{
              console.log("Início e fim ainda em brancos");
            }
          }else{
            console.log("Número não é inteiro ou < 10");
          }

        }
        
      </script>
@endsection