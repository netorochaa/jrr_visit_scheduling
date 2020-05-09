@extends('templates.master')

@section('head-distinct')
@endsection

@section('content')
  @include('templates.content.header')
  @include('templates.content.content1col', ['contentbody' => 'collect.list'])
@endsection

@section('footer-distinct')
  <script>
    $(function ()
    {
      $('#table-{{ $table }}').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "order": [[ 0, 'desc' ]],
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
          "decimal": "",
          "emptyTable": "Sem dados disponíveis",
          "loadingRecords": "A carregar dados...",
          "processing": "A processar...",
          "search": "<span class='text-muted'><small>Digite mais de um valor separando por espaço</span><br> Procurar:</small>",
          "zeroRecords": "Não foram encontrados resultados",
          "paginate": {
            "first": "Primeiro",
            "last": "Último",
            "next": "Seguinte",
            "previous": "Anterior"
          }
        }
      });
    });
  </script>
@endsection
