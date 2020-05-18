@extends('templates.master')

@section('head-distinct')
    {{-- DateRange --}}
    <link rel="stylesheet" href="{{ asset('daterangepicker/daterangepicker.css') }} ">
@endsection

@section('content')
    @include('templates.content.header')
    @if($report == 'cash')
        @include('templates.content.content1col', ['contentbody' => 'report.cash'])
        @include('templates.content.modallarge', ['titlemodal' => $titlemodal , 'contentmodal' => 'report.filter.cash'])
    @endif
@endsection

@section('footer-distinct')
    <script src=" {{ asset('moment/moment.min.js') }}"></script>
    <script src=" {{ asset('daterangepicker/daterangepicker.js') }} "></script>
    <script>
        $(function () {
            $('input[name="dateRange_filter"]').daterangepicker({
            "timePicker": true,
            "timePicker24Hour": true,
            locale: {
                format: 'DD/MM/YYYY HH:mm'
            }
            });
        });
    </script>
@endsection
