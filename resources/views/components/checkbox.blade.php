
<div class="row">
    @foreach ($options as $key => $option)
    <div class="col-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="{{ $name??$field->field_name }}[]" id="{{ $field->field_name }}_{{ $option }}" value="{{ $key }}" {{ in_array($key, $value??[]) ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $field->field_name }}_{{ $option }}">{{ $option }}</label>
        </div>
    </div>
@endforeach
</div>
