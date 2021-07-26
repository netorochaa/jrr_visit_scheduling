@extends('templates.master')

@section('head-distinct')
    {{-- DateRange --}}
    <link rel="stylesheet" href="{{ asset('daterangepicker/daterangepicker.css') }} ">
@endsection

@section('content')
    @include('templates.content.header')
    @include('templates.content.content1col', ['contentbody' => $report == 'cash' ? 'report.cash' : 'report.graphic'])
    @include('templates.content.modallarge',  ['titlemodal' => $titlemodal , 'contentmodal' => 'report.filter'])
@endsection

@section('footer-distinct')
    @push('scripts-bar-chat-qtd')
    <script src="{{ asset('js/ChartJS/Chart.min.js') }}"></script>
    <script src=" {{ asset('moment/moment.min.js') }}"></script>
    <script src=" {{ asset('daterangepicker/daterangepicker.js') }} "></script>
    <script src="{{ asset('tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
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
