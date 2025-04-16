
{{-- <input type="{!! $field->field_type != 'file' ?: 'text' !!}" class="form-control" id="{{ $field->field_name }}" name="{{ $name??$field->field_name }}" value="{{ isset($configurations['filter'][$field->field_name]) ? $configurations['filter'][$field->field_name] : '' }}"> --}}
<input type="{!! $field->field_type != 'file' ?: 'text' !!}" class="form-control" id="{{ $field->field_name }}" name="{{ $name??$field->field_name }}" value="{{ $value??null }}">
