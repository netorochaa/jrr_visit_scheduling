@if(!empty($col))
	<div class="col-{{ $col }} form-group">
@else
	<div class="form-group">
@endif
@if (!empty($inputCheck))
	{!! Form::checkbox($inputCheck, $valueCheck ?? null, $checked ?? null, $attributesCheck ?? null) !!}
@endif
	<label>{{ $label ?? null }} 
		@if ($listExists ?? null)
			- <span style="color: #228B22; font-size: larger">{{ $listExists }}</span>
		@endif</label>
	{!! Form::select($select, $data, null, $attributes) !!}
</div>
