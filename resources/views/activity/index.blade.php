@extends('templates.master')

@section('head-distinct')
@endsection

@section('content')
  @include('templates.content.header')
  @if($activity ?? null)
    @include('templates.content.timeline', ['contentbody' => 'activity.progress']) 
    @include('templates.content.modallarge', [
    'titlemodal'    => $titlemodal , 
    'contentmodal'  => 'activity.close',
    'titlemodal2'   => $titlemodal2, 
    'contentmodal2' => 'activity.cancellation'
    ])
  @else
    @include('templates.content.content1col', ['contentbody' => 'activity.start'])
  @endif
@endsection

@section('footer-distinct')
  <script>
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
  </script>
@endsection
