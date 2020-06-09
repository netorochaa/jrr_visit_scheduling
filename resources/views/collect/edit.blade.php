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
  @include('templates.content.content1col', ['contentbody' => 'collect.schedule'])
  @include('templates.content.modallarge',  [
    'titlemodal' => $titlemodal ,
    'contentmodal' => 'person.register',
    'titlemodal2' => $titlemodal,
    'contentmodal2' => 'person.registered'
    ])
  @if(Auth::user()->type > 2 && $collect->status < 5)
    @include('templates.content.uniquemodal',  [
        'titlemodal' => 'Transferir coleta' ,
        'contentmodal' => 'collect.register'
    ])
  @endif
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

      $('#table-people').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": false,
        "autoWidth": false,
        "responsive": true,
      });
    });

    function changeAuthUser()
    {
      var selectPayment = document.getElementById('selPayament');
      var selectAuthUser = document.getElementById('selAuthUser');
      var inputChangePay = document.getElementById('changePay');

      if(selectPayment.selectedIndex == "3"){
        selectAuthUser.disabled = false;
        inputChangePay.disabled = true;
      }
      else if(selectPayment.selectedIndex != "0")
      {
        inputChangePay.disabled = true;
        selectAuthUser.disabled = true;
      }
      else
      {
        selectAuthUser.disabled = true;
        inputChangePay.disabled = false;
      }
    }

    function changeCancellation()
    {
      var checkCancellation = document.getElementById('cancellationCheck');
      var selectCancellation = document.getElementById('cancellationSelect');

      if(checkCancellation.checked)
        selectCancellation.disabled = false;
      else
      {
        selectCancellation.selectedIndex = 0;
        selectCancellation.disabled = true;
      }
    }

    function changeResponsible(sel)
    {
      var selectType = document.getElementById('selectType');
      var inputName = document.getElementById('inputName');
      var inputFone = document.getElementById('inputFone');

      if(sel.options[sel.selectedIndex].text.includes('[COM RESPONSÁVEL]'))
        selectType.disabled = false;
      else
      {
        selectType.selectedIndex = 0;
        selectType.disabled = true;
        inputName.value = "";
        inputName.disabled = true;
        inputFone.value = "";
        inputFone.disabled = true;
      }
    }

    function changeTypeResponsible(sel)
    {
      var inputName = document.getElementById('inputName');
      var inputFone = document.getElementById('inputFone');

      if(sel.value != "1")
      {
        inputName.disabled = false;
        inputFone.disabled = false;
      }
      else
      {
        inputName.value = "";
        inputName.disabled = true;
        inputFone.value = "";
        inputFone.disabled = true;
      }
    }

    function checkAge(input)
    {
        var age = document.getElementById('age');
        var exams = document.getElementById('textExams');
        var dateSplit = input.value.split('/');
        var day = dateSplit[0];
        var month = dateSplit[1];
        var year = dateSplit[2];

        var dateMomment = moment(year + "-" + month + "-" + day);
        var dateNow = moment().format("YYYY-MM-DD");
        if(!dateMomment.isValid()){
            input.value = "";
            return;
        }
        if(dateMomment.diff(dateNow, 'years') > -8){
            textExams.value = "Teste do pezinho";
            textExams.readOnly = true;
            age.style.color = "red";
        }else{
            textExams.value = "";
            textExams.readOnly = false;
            age.style.color = "black";
        }
    }

    function activeButton(){
        // var submitSchedule = document.getElementById('submitSchedule');
        var buttonConfirmed = document.getElementById('buttonConfirmed');
        var labelValue = document.getElementById('labelValue');

        if(buttonConfirmed != null)
        {
            if(labelValue.innerHTML != "R$ 0")
                buttonConfirmed.disabled = false;
            else
                buttonConfirmed.disabled = true;
        }
    }

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

    $(document).ready(function() {
      changeAuthUser();
      activeButton();
      function limpa_formulário_cep()
      {
          // Limpa valores do formulário de cep.
          $("#rua").val("");
      }

      //Quando o campo cep perde o foco.
      $("#cep").blur(function()
      {

          //Nova variável "cep" somente com dígitos.
          var cep = $(this).val().replace(/\D/g, '');

          //Verifica se campo cep possui valor informado.
          if (cep != "") {

              //Expressão regular para validar o CEP.
              var validacep = /^[0-9]{8}$/;

              //Valida o formato do CEP.
              if(validacep.test(cep)) {

                  //Preenche os campos com "..." enquanto consulta webservice.
                  $("#rua").val("...");
                  //Consulta o webservice viacep.com.br/
                  $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                    console.log(dados.logradouro);
                      if (!("erro" in dados)) {
                          //Atualiza os campos com os valores da consulta.
                          $("#rua").val(dados.logradouro);
                          $("#bairro").val(dados.bairro)
                      } //end if.
                      else {
                          //CEP pesquisado não foi encontrado.
                          limpa_formulário_cep();
                          alert("CEP não encontrado.");
                      }
                  });
              } //end if.
              else {
                  //cep é inválido.
                  limpa_formulário_cep();
                  alert("Formato de CEP inválido.");
              }
          } //end if.
          else {
              //cep sem valor, limpa formulário.
              limpa_formulário_cep();
          }
      });

      $("#schedulingDate").blur(function()
      {
        if(verificateDate())
        {
          var date = $(this).val();
          var neighborhood = $('#inputNeighborhood').val();

          $("#describe-feedback").html("Carregando...");

          $.getJSON("../available?neighborhood_id=" + neighborhood + "&datecollect=" + date, function(dados) {
            if(dados.length > 0){
              var option = '<option>Selecione</option>';
              $.each(dados, function(i, obj){
                  option += '<option value="'+obj.id+'">' + obj.hour + " - " + obj.name + '</option>';
              })
              $("#describe-feedback").html(dados.length + " horários para agendamento nesta data");
              $('#infoCollectSel').prop('disabled', false);
              $('#submitSelectNeighborhood').attr('disabled', false);

            }else{
              $("#describe-feedback").html("Não há horários disponíveis para esta data!");
              $('#infoCollectSel').prop('disabled', true);
              $('#submitSelectNeighborhood').attr('disabled', true);
            }
            $('#infoCollectSel').html(option).show();
          });
        }else{
          $("#describe-feedback").html("Você não pode marcar coletas para esta data!");
          $('#submitSelectNeighborhood').attr('disabled', true);
        }
      });
    });
  </script>
@endsection
