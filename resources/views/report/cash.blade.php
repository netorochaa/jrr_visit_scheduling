
<h4 class="lead">Período: {{ $period_get ?? "Hoje" }} | {{ $collector_selected ?? "Todos os coletadores" }}</h4>
<hr>

    <table id="table-{{ $report }}" class="table table-striped table-sm">
        <thead class="thead-dark">
            <tr>
                @foreach ($thead_for_datatable as $ths)
                    <th>{{ $ths }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <?php $sum_collects = 0; ?>
            @foreach ($collects as $collect)
            <tr>
                <td>{{ $collect->id }}</td>
                <td>{{ $collect->formatted_date }} {{ $collect->hour }}</td>
                <td>
                    @foreach ($collect->people as $person)
                        - <small>{{ $person->name }} ({{ $person->patientType->name }})<br>
                        {{ $person->os }}</small>
                    @endforeach
                </td>
                <td>{{ $collect->neighborhood->name ?? null }}</td>
                <td>
                    {{ $collect->collector->name }}<br>
                    <small>{{ $collect->collector->user->name }}</small>
                </td>
                <td>{{ $collect->formatted_status }}</td>
                <td>
                    {{ $collect->formatted_payment }}
                    @if ($collect->payment == 4)
                        <br><small>{{ $collect->AuthCourtesy }}</small>
                    @endif
                </td>
                <?php
                    $quant  = count($collect->people);
                    $price  = $quant == 0   ? 0 : $collect->neighborhood->displacementRate;
                    $price  = $quant  > 2   ? number_format(($quant-1) * $collect->neighborhood->displacementRate, 2, '.', '') : $collect->neighborhood->displacementRate;
                    $collect->payment != 4 ? $sum_collects += $price : 0;
                ?>
                <td>{{$collect->payment == 4 ? 0.00 : $price }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="7" style="text-align: right">Valor total</td>
                <td><b>{{ number_format($sum_collects, 2, '.', '') }}</b></td>
            </tr>
        </tbody>
    </table>
<p>Emitido: {{ Auth::user()->name }} - {{ date('d/m/Y H:i') }}</p>
