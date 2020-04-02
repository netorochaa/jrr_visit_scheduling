@if(!empty($col))
	<div class="col-xs-{{ $col }} form-group">
@else
	<div class="form-group">
@endif
	@if (!empty($inputCheck))
		{!! Form::checkbox($inputCheck, $valueCheck ?? null, $checked ?? null, $attributesCheck ?? null) !!}
	@endif
	<label>{{ $label ?? $input ?? "ERRO" }}</label>
	<div class="input-group">
		{!! Form::number($input, $value ?? null, $attributes) !!}
		@if (!empty($addon))<span class="input-group-addon">{{ $addon }}</span>
		@endif
	</div>
</div>
