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
    $(function ()
    {
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
      //Initialize daterangepicker Elements
      $('input[id="schedulingDate"]').daterangepicker({
        "minDate": moment(),
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

    //Jquery to filter options in select
    jQuery.fn.filterByText = function(textbox) {
      return this.each(function() {
        var select = this;
        var options = [];
        $(select).find('option').each(function() {
          options.push({
            value: $(this).val(),
            text: $(this).text()
          });
        });
        $(select).data('options', options);

        $(textbox).bind('change keyup', function() {
          var options = $(select).empty().data('options');
          var search = $.trim($(this).val());
          var regex = new RegExp(search, "gi");

          $.each(options, function(i) {
            var option = options[i];
            if (option.text.match(regex) !== null) {
              $(select).append(
                $('<option>').text(option.text).val(option.value)
              );
            }
          });
        });
      });
    };
    $(function() {
      $('select').filterByText($('input[id=schedulingDate]'));
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

      if(dateMomment.isAfter(dateNow)) select.disabled = false;
      else select.disabled = true;
    }

  </script>
@endsection
