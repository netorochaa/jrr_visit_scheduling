<div class="col-xs-{{ $col ?? null }} form-group">
  {!! Form::checkbox($inputCheck, $valueCheck ?? null, $checked ?? null, $attributesCheck ?? null) !!}
	<label>{{ $label ?? $input ?? "ERRO" }}</label>
	<div class="input-group">
		{!! Form::number($input, $value ?? null, $attributes) !!}
		@if ($addon != 'null') <span class="input-group-addon">{{ $addon }}</span>
		@endif
	</div>
</div>
