@extends('templates.master')

@section('content')
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'person.register'])
@endsection

@section('footer-distinct')
  <script>
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

    function activeEnrollment()
    {
      var selectCovenant  = document.getElementById('selectCovenant');
      var inputEnroll = document.getElementById('inputEnroll');
      
      if(selectCovenant.options[selectCovenant.selectedIndex].text.includes('PARTICULAR') 
        || selectCovenant.options[selectCovenant.selectedIndex].text.includes('OUTROS'))
        inputEnroll.disabled = true;
      else
        inputEnroll.disabled = false;
    }

    $(document).ready(function() 
    {
      var selectPatientTypes = document.getElementById('selectPatientTypes');
      var selectType = document.getElementById('selectType');

      changeResponsible(selectPatientTypes);
      changeTypeResponsible(selectType);
      activeEnrollment();
    });
  </script>
@endsection
