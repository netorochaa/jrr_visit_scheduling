<div class="form-group">
	<div class="form-check">
		{!! Form::checkbox($input, $value ?? null, $checked ?? null, $attributes ?? null) !!}
		<label class="form-check-label lead">{{ $label ?? null }}</label>
	</div>
</div>
