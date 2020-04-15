<div class="col-{{ $col ?? null }} form-group">
    <div class="custom-control custom-switch">
        {!! Form::checkbox($input, $value ?? null, $checked ?? null, $attributes ?? null) !!}
        <label class="{{ $class ?? null }}" for="{{ $id ?? null }}">{{ $label ?? $input ?? "ERRO" }}</label>
    </div>
</div>