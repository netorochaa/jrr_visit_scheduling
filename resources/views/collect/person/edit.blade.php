@extends('templates.master')

@section('content')
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'collect.person.register'])
@endsection

@section('footer-distinct')
  <script>
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

      $(document).ready(function() 
      {
        var selectPatientTypes = document.getElementById('selectPatientTypes');
        var selectType = document.getElementById('selectType');

        changeResponsible(selectPatientTypes);
        changeTypeResponsible(selectType);
      });
  </script>
@endsection
