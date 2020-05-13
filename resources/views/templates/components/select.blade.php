@if(!empty($col))
	<div class="col-sm-{{ $col }} form-group">
@else
	<div class="form-group">
@endif
	@if($label != null)
		<label>{{ $label }} 
			@if ($listExists ?? null)
			- <span style="color: #228B22; font-size: larger">{{ $listExists }}</span>
			@endif
		</label>
	@endif
	{!! Form::select($select, $data, $selected ?? null, $attributes) !!}
</div>
