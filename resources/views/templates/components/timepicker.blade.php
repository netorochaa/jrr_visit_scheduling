<div class="col-{{ $col ?? null }} bootstrap-timepicker">
	<label>{{ $label ?? $input ?? "ERRO" }}</label>
	<div class="input-group date" id={{ $id }} data-target-input="nearest">
		{!! Form::text($input, $value ?? null, $attributes) !!}
		<div class="input-group-append" data-target={{ $datatargetdiv }} data-toggle="datetimepicker">
			<div class="input-group-text"><i class="far fa-clock"></i></div>
		</div>
	</div>
  </div>