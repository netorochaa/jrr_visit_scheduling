@extends('templates.master')

@section('content')
  @include('templates.content.header')
  @if($filter ?? null)
    @include('templates.content.infobox', [
        'content_body'      => 'collect.filter',
        'tam_card'          => 12
    ])
  @endif
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
      
      //Procura paciente já cadastrado
      $("#searchCollect").on("input", function()
      {
        var value = $(this).val();

        if(value.length > 3)
        {
            $("#status-find-collect").html("");
            $('#submitFindCollect').prop('disabled', false);
        }
        else
        {
            $("#status-find-collect").html("Digite mais de 3 caracteres para realizar a pesquisa");
            $('#submitFindCollect').prop('disabled', true);
        }
      });
      
      $('#selTypeSearchCollect').on("input", function()
      {
        var inputSearchCollect = document.getElementById('searchCollect');
        if($(this).val() == 'collects.date')
        {
            $('#searchCollect').prop('placeholder', '__/__/____');
            $('#searchCollect').inputmask({'mask': '99/99/9999'});
        }else
            $('#searchCollect').prop('placeholder', 'Digite o valor da busca');
      });
    });

    
  </script>
@endsection
