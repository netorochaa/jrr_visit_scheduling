@extends('templates.master')

@section('head-distinct')
  {{-- Select2 --}}
  <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }} ">
  <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  {{-- DateRange --}}
  <link rel="stylesheet" href="{{ asset('daterangepicker/daterangepicker.css') }} ">
  {{--  tempus  --}}
  <link rel="stylesheet" href="{{ asset('tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endsection

@section('content')
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'collect.schedule'])
  @include('templates.content.modallarge',  [
    'titlemodal' => $titlemodal ,
    'contentmodal' => 'person.register',
    'titlemodal2' => $titlemodal,
    'contentmodal2' => 'person.find'
    ])
  @if(Auth::user()->type > 2 && $collect->status < 5)
    @include('templates.content.uniquemodal',  [
        'titlemodal' => 'Transferir coleta' ,
        'contentmodal' => 'collect.register'
    ])
    @include('templates.content.uniquemodal',  [
      'titlemodal' => 'Mudar horário da coleta' ,
      'contentmodal' => 'collect.modifyhour',
      'idmodal' => 'modifyhour_' . $idmodal
    ])
  @endif
@endsection

@section('footer-distinct')
  <script src=" {{ asset('select2/js/select2.full.min.js') }} "></script>
  <script src=" {{ asset('moment/moment.min.js') }}"></script>
  <script src=" {{ asset('daterangepicker/daterangepicker.js') }} "></script>
  <script src=" {{ asset('tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
  <script>
    // Iniciliza daterangepicker e select
    $(function ()
    {
      //Timepicker
      $('#timepicker').datetimepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 1,
        format: 'HH:mm'

      });

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $("input[type='file']").change(function(e){
        var $fileUpload = $("input[type='file']");
        var msg = "Não foi possível enviar o(s) arquivo(s). Selecione apenas dois (2) e que possuam tamanho máximo de dois (2) mb.";
        console.log($fileUpload.get(0).files[0].size);
        if (parseInt($fileUpload.get(0).files.length) > 2)
        {
         alert(msg);
         $fileUpload.get(0).value = "";
        }
        else
        {
          for (index = 0; index < $fileUpload.get(0).files.length; index++)
          {
            if($fileUpload.get(0).files[index].size > 2000000)
            {
                alert(msg);
                $fileUpload.get(0).value = "";
            }
          }
        }
        e.preventDefault();
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

    function activeButton()
    {
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
    function verificateDate()
    {
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

    function checkEmail(input)
    {
        var usuario = input.value.substring(0, input.value.indexOf("@"));
        var dominio = input.value.substring(input.value.indexOf("@")+ 1, input.value.length);

        if(input.value == "")
            return;
        else if((usuario.length >=1) &&
            (dominio.length >=3) &&
            (usuario.search("@")==-1) &&
            (dominio.search("@")==-1) &&
            (usuario.search(" ")==-1) &&
            (dominio.search(" ")==-1) &&
            (dominio.search(".")!=-1) &&
            (dominio.indexOf(".") >=1)&&
            (dominio.lastIndexOf(".") < dominio.length - 1))
        {
            var email = input.value;
            //GMAIL.COM
            if( dominio == "gemail.com" ||
                dominio == "gmail.com.br" ||
                dominio == "gmali.com")
                email = usuario + "@gmail.com";
            //HOTMAIL.COM
            else if( dominio == "hotmail.com.br" ||
                     dominio == "hotmail.bom" ||
                     dominio == "hotmail.bom" ||
                     dominio == "hormail.com")
                email = usuario + "@hotmail.com";
            //YAHOO.COM.BR
            else if( dominio == "yahho.com.br" ||
                     dominio == "yaho.com.br" ||
                     dominio == "yahou.com.br"
                     )
                email = usuario + "@yahoo.com.br";

            input.value = email;
        }
        else
        {
            alert("E-mail " + input.value + " inválido");
            input.value = "";
        }
    }


    $(document).ready(function()
    {
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

      function isEmpty(obj) 
      {
          for(var prop in obj) {
              if(obj.hasOwnProperty(prop))
                  return false;
          }    
          return true;
      }

      //Quando escolhe a data, procura os horários
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

              $.getJSON("../available?neighborhood_id=" + neighborhood + "&datecollect=" + date, function(dados) 
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
                        @if(Auth::user()->type > 2)
                          option += '<option value="'+obj.id+'">' + obj.hour + " - " + obj.name  + ' - ' + obj.id + '</option>';
                        @else
                          var range = null;

                          if(dayOfWeek > 0 && dayOfWeek < 6)
                              var array = obj.mondayToFriday.split(',');
                          else if(dayOfWeek == 6)
                              var array = obj.saturday.split(',');
                          else if(dayOfWeek == 0)
                              var array = obj.sunday.split(',');

                          range = "Entre " + array[0] + " e " + array[array.length - 1];
                          option += '<option value="'+obj.id+'">' + obj.id + " - " + range + '</option>';
                        @endif

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

      //Procura paciente já cadastrado
      $("#search").on("input", function()
      {
        var typeSearch = $('#selTypeSearch').val();
        var value = $(this).val();

        if(value.length > 3)
        {
            $("#status-find-client").html("Carregando...");

            $.getJSON("../findperson?typeSearch=" + typeSearch + "&value=" + value, function(dados)
            {
                if(dados.length > 0)
                {
                    var option = "";
                    $.each(dados, function(i, obj)
                    {
                        var rg      = obj.rg    != null ? ", RG: "      + obj.rg    : "";
                        var email   = obj.email != null ? ", E-mail: "  + obj.email : "";
                        option += '<option value="'+obj.id+'">' + obj.name + ", CPF: " + obj.cpf + rg + ", Nasc: " + obj.birth  + ", Fone: " + obj.fone  + email +'</option>';
                    })
                    $("#status-find-client").html(dados.length + " pacientes encontrados");
                    $('#registeredPatientSel').prop('disabled', false);
                }
                else
                {
                    $("#status-find-client").html("Nenhum paciente encontrado");
                    $('#registeredPatientSel').prop('disabled', true);
                }
                $('#registeredPatientSel').html(option).show();
            });
        }
        else
        {
            var option = "";
            $('#registeredPatientSel').html(option).show();
            $("#status-find-client").html("Digite mais de 3 caracteres para realizar a pesquisa");
        }
      });
    });
  </script>
@endsection
