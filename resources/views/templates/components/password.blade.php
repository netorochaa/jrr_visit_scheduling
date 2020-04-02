<div class="col-xs-{{ $col }}">
	<label class="{{ $class ?? null }}">{{ $label ?? $input ?? "ERRO" }}</label>
	{!! Form::password($input, $attributes) !!}
</div>
