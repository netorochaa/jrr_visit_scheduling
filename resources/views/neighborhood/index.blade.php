@extends('templates.master')

@section('content')
  @include('templates.content.header')
  @include('templates.content.content2col', [
    'contentbody' => 'neighborhood.list', 
    'contentbody2' => 'city.list',
    ])
  @include('templates.content.modallarge', [
    'titlemodal'   => $titlemodal , 
    'contentmodal' => 'neighborhood.register',
    'titlemodal2' => $titlemodal2, 
    'contentmodal2' => 'city.register'
    ])
@endsection

@section('footer-distinct')
    <script>
        $(function () {
          $('#table-{{ $table }}').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
          });
        });
        $(function () {
          $('#table-{{ $table2 }}').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true,
          });
        });

    </script>
@endsection
