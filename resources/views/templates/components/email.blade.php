<div class="col-{{ $col }}">
	<label class="{{ $class ?? null }}">{{ $label ?? $input ?? "ERRO" }}</label>
	{!! Form::email($input, $value ?? null, $attributes) !!}
</div>
