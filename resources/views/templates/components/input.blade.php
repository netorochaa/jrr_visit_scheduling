<div class="col-sm-{{ $col ?? null }}">
	@if ($label != null) 
		<label>{{ $label }}</label>
	@endif
	@if(!empty($icon))
		<div class="input-group">
			<div class="input-group-prepend">
				<i class="fa fa-{{ $icon }}"></i>
			</div>
	@endif
	{!! Form::text($input, $value ?? null, $attributes) !!}
	@if(!empty($icon))
		</div>
	@endif
</div>