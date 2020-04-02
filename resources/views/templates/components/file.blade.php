<div class="col-xs-{{ $col }}">
	@if (!empty($inputCheck))
		{!! Form::checkbox($inputCheck, $valueCheck ?? null, $checked ?? null, $attributesCheck ?? null) !!}
	@endif
	<label>{{ $label ?? $input ?? "ERRO" }}</label>
	{!! Form::file($input, $attributes) !!}
</div>
