@extends('templates.master')

@section('content')
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'collect.person.register'])
@endsection

@section('footer-distinct')
  <script src=" {{ asset('js/inputmask/min/jquery.inputmask.bundle.min.js') }} "></script>
  <script>
    $(function () {
      //Datemask 00,00
      $('[data-mask]').inputmask('', {'placeholder': '00,00'})
      $('[data-cep]').inputmask('', {'placeholder': '00000-000'})
      $('[data-date]').inputmask('', {'placeholder': '00/00/0000'})
      $('[data-ra]').inputmask('', {'placeholder': '0000000000'})
      $('[data-cpf]').inputmask('', {'placeholder': '00000000000'})
      $('[data-fone]').inputmask('', {'placeholder': '(00) 00000-0000)'})
    });
  </script>
@endsection
