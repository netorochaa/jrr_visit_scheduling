@extends('templates.master')

@section('head-distinct')
@endsection

@section('content')
  @include('templates.content.header')
  @if($activity ?? null)
    @include('templates.content.timeline', ['contentbody' => 'activity.progress']) 
  @else
    @include('templates.content.content1col', ['contentbody' => 'activity.start'])
  @endif
  {{-- @include('templates.content.modallarge', ['titlemodal' => $titlemodal , 'contentmodal' => 'collect.register']) --}}
@endsection

@section('footer-distinct')
  <script>
   
  </script>
@endsection
