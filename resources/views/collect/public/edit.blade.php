@extends('templates.public')

@section('head-distinct')
  {{-- Select2 --}}
  <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }} ">
  <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'collect.public.schedule'])
  @include('templates.content.modallarge',  ['titlemodal' => $titlemodal , 'contentmodal' => 'collect.person.register',])
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
        "language": {
          "decimal": "",
          "emptyTable": "Nenhum paciente cadastrado! Clique no + para adicionar.",
          "loadingRecords": "A carregar dados...",
          "processing": "A processar...",
          "zeroRecords": "Não foram encontrados resultados"
        }
      });
    });

    function changeAuthUser()
    {
      var selectPayment = document.getElementById('selPayament');
      var inputChangePay = document.getElementById('changePay');

      if(selectPayment.selectedIndex == "3")
        inputChangePay.disabled = true;
      else if(selectPayment.selectedIndex != "0")
        inputChangePay.disabled = true;
      else
        inputChangePay.disabled = false;
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
      var submitSchedule = document.getElementById('submitSchedule');
      var labelValue = document.getElementById('labelValue');

      if(labelValue.innerHTML != "R$ 0")
        submitSchedule.disabled = false;
      else
        submitSchedule.disabled = true;
    }

    $(document).ready(function() {
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
