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
      $('input[id="schedulingDate"]').daterangepicker(
        {
          "minDate": moment().add(1, 'd'),
          "singleDatePicker": true,
          locale: {
            "format": "DD/MM/YYYY ddd",
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
        },
      });
    });

    function verificateDate()
    {
      //Convert date to formmat moment()
      var schedulingDate = document.getElementById("schedulingDate");
      var schedulingDateSplit = schedulingDate.value.split(" ");
      schedulingDate.value = schedulingDateSplit[0];
      alterLabel(schedulingDateSplit[1]);
      //customizeOptions(schedulingDateSplit[0]);
    }

    function alterLabel(ddd)
    {
      //Label day of week
      var labelDay = document.getElementById("DayOfWeek");
        if( ddd == 'Mon' ||
            ddd == 'Tue' ||
            ddd == 'Wed' ||
            ddd == 'Thu' ||
            ddd == 'Fri')
        {
          document.getElementById("infoCollectSunday").style.display = "none";
          document.getElementById("infoCollectSelSunday").disabled = true;
          document.getElementById("infoCollectSaturday").style.display = "none";
          document.getElementById("infoCollectSelSaturday").disabled = true;
          document.getElementById("infoCollectMondayToFriday").style.display = "block";
          document.getElementById("infoCollectSelMondayToFriday").disabled = false;
          labelDay.innerHTML = "Segunda a Sexta";
        }
        else if(ddd == 'Sat')
        {
          document.getElementById("infoCollectMondayToFriday").style.display = "none";
          document.getElementById("infoCollectSelMondayToFriday").disabled = true;
          document.getElementById("infoCollectSunday").style.display = "none";
          document.getElementById("infoCollectSelSunday").disabled = true;
          document.getElementById("infoCollectSaturday").style.display = "block";
          document.getElementById("infoCollectSelSaturday").disabled = false;
          console.log(document.getElementById("infoCollectSaturday").disabled);
          labelDay.innerHTML = "Sábado";
        }
        else if(ddd == 'Sun')
        {
          document.getElementById("infoCollectMondayToFriday").style.display = "none";
          document.getElementById("infoCollectSelMondayToFriday").disabled = true;
          document.getElementById("infoCollectSaturday").style.display = "none";
          document.getElementById("infoCollectSelSaturday").disabled = true;
          document.getElementById("infoCollectSunday").style.display = "block";
          document.getElementById("infoCollectSelSunday").disabled = false;
          labelDay.innerHTML = "Domingo";
        }
    }

    function customizeOptions(date)
    {
      // var dateFormatted = date.replace(/[//"]/g, '');
      console.log(date);
      var ids = document.getElementsByClassName(date);

      for (var i = 0; i < ids.length; i++) {
        ids[i].disabled = "disabled";
        ids[i].innerHTML += " (Reservado)";
        console.log(ids[i]);
      }
    }
  </script>
@endsection
