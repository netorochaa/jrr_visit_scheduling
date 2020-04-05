<div class="col-{{ $col ?? null }} {{ $bootstrapTimepicker ?? null}}">
	<label>{{ $label ?? $input ?? "ERRO" }}</label>
	@if(!empty($icon))
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-{{ $icon }}"></i>
			</div>
	@endif
	{!! Form::text($input, $value ?? null, $attributes) !!}
	@if(!empty($icon))
		</div>
	@endif
</div>