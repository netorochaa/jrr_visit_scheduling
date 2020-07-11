@extends('templates.public')

@section('head-distinct')
  {{-- Select2 --}}
  <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }} ">
  <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
  @include('templates.content.header')
  <div class="container">
    @include('templates.content.content1col', ['contentbody' => 'collect.public.schedule'])
    @include('templates.content.modallarge',  ['titlemodal' => $titlemodal , 'contentmodal' => 'person.register',])
  </div>
@endsection

@section('footer-distinct')
  <script src=" {{ asset('select2/js/select2.full.min.js') }} "></script>
  <script src=" {{ asset('moment/moment.min.js') }}"></script>
  <script>
    $(function () {
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
      var submitSchedule = document.getElementById('submitSchedule');
      var labelValue = document.getElementById('labelValue');

      if(labelValue.innerHTML != "R$ 0")
        submitSchedule.disabled = false;
      else
        submitSchedule.disabled = true;
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
                          $("#bairro").val(dados.bairro);
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
