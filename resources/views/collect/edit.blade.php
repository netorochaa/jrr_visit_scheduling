@extends('templates.master')

@section('head-distinct')
  {{-- Select2 --}}
  <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }} ">
  <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'collect.schedule'])
  @include('templates.content.modallarge',  [
    'titlemodal' => $titlemodal ,
    'contentmodal' => 'collect.person.register',
    'titlemodal2' => $titlemodal,
    'contentmodal2' => 'collect.person.registered'
    ])
@endsection

@section('footer-distinct')
  <script src=" {{ asset('select2/js/select2.full.min.js') }} "></script>
  <script>
    $(function () {
      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
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

      if(sel.options[sel.selectedIndex].text.includes('[RESPONSÁVEL]'))
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
    });
  </script>
@endsection
