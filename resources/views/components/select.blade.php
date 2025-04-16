<select class="form-select" id="{{ $field->field_name }}" name="{{ $name??$field->field_name }}">
    <option value="" selected="selected">--Select--</option>
    @foreach ($options as $key => $option)
        <option value="{{ $key }}" {{ ((isset($configurations['filter'][$field->field_name]) && $configurations['filter'][$field->field_name] == $key || (isset($configurations['filter'][$main_field_name??null][$index??null][$field->field_name]) && $configurations['filter'][$main_field_name??null][$index??null][$field->field_name] == $key))) ? 'selected' : '' }}>{{ $option }}</option>
    @endforeach
</select>



