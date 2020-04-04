@if(!empty($col))
	<div class="col-{{ $col }} form-group">
@else
	<div class="form-group">
@endif
@if (!empty($inputCheck))
	{!! Form::checkbox($inputCheck, $valueCheck ?? null, $checked ?? null, $attributesCheck ?? null) !!}
@endif
  <label>{{ $label ?? null }}</label>
	{!! Form::select($select, $data, null, $attributes) !!}
</div>
