<div class="col-{{ $col }}">
	<label>{{ $label ?? $input ?? "ERRO" }}</label>
	{!! Form::file($input, $attributes) !!}
</div>
