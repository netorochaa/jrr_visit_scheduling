<div class="col-{{ $col }}">
	<label class="{{ $class ?? null }}">{{ $label ?? $input ?? "ERRO" }}</label>
	{!! Form::password($input, $attributes) !!}
</div>
