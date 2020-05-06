@extends('templates.master')

@section('head-distinct')
@endsection

@section('content')
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'collect.start'])
  {{-- @include('templates.content.modallarge', ['titlemodal' => $titlemodal , 'contentmodal' => 'collect.register']) --}}
@endsection

@section('footer-distinct')
  <script>
   
  </script>
@endsection
