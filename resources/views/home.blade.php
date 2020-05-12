@extends('templates.master')

@section('content')
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'dashboard'])
@endsection

@section('footer-distinct')
    <script>

    </script>
@endsection
