@extends('templates.public')

@section('head-distinct')
  {{-- Select2 --}}
  <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }} ">
  <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  {{-- DateRange --}}
  <link rel="stylesheet" href="{{ asset('daterangepicker/daterangepicker.css') }} ">
@endsection

@section('content')
    @include('templates.content.header')
    <div class="container">
        @if($neighborhood_model)
            @include('templates.content.content1col', ['contentbody' => 'collect.public.register'])
        @else
            @include('templates.content.content1col', ['contentbody' => 'collect.public.neighborhood'])
        @endif
    </div>
@endsection

@section('footer-distinct')
  <script src=" {{ asset('select2/js/select2.full.min.js') }} "></script>
  <script src=" {{ asset('moment/moment.min.js') }}"></script>
  <script src=" {{ asset('daterangepicker/daterangepicker.js') }} "></script>
  <script>
    $(function ()
    {
      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })

      var dateNow = moment();;
      //Initialize daterangepicker Elements
      $('input[id="schedulingDate"]').daterangepicker({
        "minDate": dateNow.day() == 6 ? moment().add('3', 'days') : (dateNow.hour() >= 14 || dateNow.day() == 0 ? moment().add('2', 'days') : moment().add('1', 'days')),
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

    function isEmpty(obj) 
    {
        for(var prop in obj) {
            if(obj.hasOwnProperty(prop))
                return false;
        }    
        return true;
    }

    $(document).ready(function()
    {
        $("#schedulingDate").change(function()
        {
            if(verificateDate())
            {
                var date = $(this).val();
                var neighborhood = $('#inputNeighborhood').val();
                var dateSplit = date.split('/');
                var dateMomment = moment(dateSplit[2] + "-" + dateSplit[1] + "-" + dateSplit[0]);
                var dayOfWeek = dateMomment.day();

                $("#describe-feedback").html("Carregando...");

                $.getJSON("../available?site=true&neighborhood_id=" + neighborhood + "&datecollect=" + date, function(dados)
                {
                    var result = null;
                    if(!isEmpty(dados))
                        result = Object.keys(dados).map(e=>dados[e]);
                    else 
                        result = dados;

                    if(result.length > 0)
                    {
                        var option = '<option>Selecione</option>';
                        $.each(result, function(i, obj)
                        {
                            option += '<option value="'+obj.id+'">' + obj.hour + " - " + obj.name + '</option>';
                        })
                        $("#describe-feedback").html(result.length + " horários para agendamento nesta data");
                        $('#infoCollectSel').prop('disabled', false);
                        $('#submitSelectNeighborhood').attr('disabled', false);
                    }
                    else
                    {
                        $("#describe-feedback").html("Não há horários disponíveis para esta data!");
                        $('#infoCollectSel').prop('disabled', true);
                        $('#submitSelectNeighborhood').attr('disabled', true);
                    }
                    $('#infoCollectSel').html(option).show();
                });
            }
            else
            {
                $("#describe-feedback").html("Você não pode marcar coletas para esta data!");
                $('#submitSelectNeighborhood').attr('disabled', true);
            }
        });
    });
    
    //Verified date to enable select
    function verificateDate(){
      var date = document.getElementById('schedulingDate');
      var select = document.getElementById('infoCollectSel');

      var dateSplit = date.value.split('/');
      var day = dateSplit[0];
      var month = dateSplit[1];
      var year = dateSplit[2];;

      var dateMomment = moment(year + "-" + month + "-" + day);
      var dateNow = moment().format("YYYY-MM-DD");

      if(dateMomment.isAfter(dateNow)){
        select.disabled = false;
        return true;
      }
      else
      {
        select.disabled = true;
        return false;
      }
    }
  </script>
@endsection
