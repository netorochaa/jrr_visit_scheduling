<div class="col-{{ $col ?? null }} form-group">
	{!! Form::checkbox($inputCheck, $valueCheck ?? null, $checked ?? null, $attributesCheck ?? null) !!}
	<label class="{{ $class ?? null }}">{{ $label ?? $input ?? "ERRO" }}</label>
	{!! Form::text($input, $value ?? null, $attributes) !!}
</div>
