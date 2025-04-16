{{--
@if(!empty($fields))
<div class="row">
    <input type="hidden" name="selectedFields" value="{!! !empty($selectedFields) ? implode(",", $selectedFields) : null !!}">
    @foreach($fields as $field)
    <div class="col-12 mb-2">
        <label for="{{$field->field_name}}" class = "form-label">{{ ucfirst(str_replace('_', ' ', $field->field_name)) }}</label>
        @includeFirst(["components.{$field->field_type}", "components.text"], ['field' => $field])
    </div>
    @endforeach
</div>
@endif --}}
@if(!empty($fields))
<div class="row">
    <input type="hidden" name="selectedFields" value="{!! !empty($selectedFields) ? implode(",", $selectedFields) : null !!}">
    @foreach($fields as $field)
    <div class="col-12 mb-2">
        <label for="{{$field->field_name}}" class = "form-label">{{ ucfirst(str_replace('_', ' ', $field->field_name)) }}</label>
        @includeFirst(["components.{$field->field_type}", "components.text"], ['field' => $field, 'configurations' => $configurations,"value"=>$configurations['filter'][$field->field_name??null]??null])
    </div>
    @endforeach
</div>
@endif
