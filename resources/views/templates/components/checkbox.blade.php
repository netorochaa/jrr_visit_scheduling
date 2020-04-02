<div class="col-xs-{{ $col ?? null }}">
	<div class="checkbox">
		<label>
		{!! Form::checkbox($input, $value ?? null, $checked ?? null, $attributes ?? null) !!}
		{{ $label ?? $input ?? "ERRO" }}
		</label>
	</div>
</div>
