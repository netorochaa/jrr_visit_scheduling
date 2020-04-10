@extends('templates.master')

@section('content')
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'cancellationType.register'])
@endsection

@section('footer-distinct')
    <script>
        $(function () {
          $('#table-{{ $table }}').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
          });
        });

    </script>
@endsection
