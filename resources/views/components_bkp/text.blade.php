<input type="{!! $field->field_type != 'file' ?: 'text' !!}" class="form-control" id="{{ $field->field_name }}" name="{{ $name??$field->field_name }}"></div>
