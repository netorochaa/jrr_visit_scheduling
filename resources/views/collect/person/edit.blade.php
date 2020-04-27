@extends('templates.master')

@section('content')
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'collect.person.register'])
@endsection

@section('footer-distinct')
  <script>
    
  </script>
@endsection
