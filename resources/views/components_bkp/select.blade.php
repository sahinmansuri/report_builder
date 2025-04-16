<select class="form-select" id="{{ $field->field_name }}" name="{{ $name??$field->field_name }}">
    <option value="" selected="selected">--Select--</option>
    @foreach ($options as $key => $option)
        <option value="{{ $key }}">{{ $option }}</option>
    @endforeach
</select>
