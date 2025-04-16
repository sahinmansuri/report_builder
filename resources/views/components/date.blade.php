<div class="d-flex">
    <input type="date" class="form-control mr-2" id="{{ $field->field_name }}_from" name="{{ $name??$field->field_name }}[from]" placeholder="From" value="{{ $value['from']??null}}">
    <input type="date" class="form-control" id="{{ $field->field_name }}_to" name="{{ $name??$field->field_name }}[to]" placeholder="To" value="{{ $value['to']??null}}">
</div>
