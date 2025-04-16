@foreach ($options as $key => $option)
<div class="form-check">
    <input class="form-check-input" type="radio" name="{{ $name??$field->field_name }}" id="{{ $field->field_name }}_{{ $option }}" value="{{ $key }}" {{ isset($value) && $value == $key ? 'checked' : '' }}>
    <label class="form-check-label" for="{{ $field->field_name }}_{{ $option }}">{{ $option }}</label>
</div>
@endforeach
