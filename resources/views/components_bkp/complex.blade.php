<table class="table table-bordered" id="{{$field->field_name}}Table">
    <thead>
        <tr>
            @foreach ($children as $child)
                <th>{!! $child->field_name !!}</th>
            @endforeach
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach ($children as $key => $child)
                <td>
                    @includeFirst(["components.{$child->field_type}", "components.text"], ['field' => $child, 'name' => "$field->field_name[0][{$child->field_name}]"])
                </td>
            @endforeach
            <td>
                <button type="button" class="btn btn-danger removeRow d-none">Remove</button>
            </td>
        </tr>
    </tbody>
</table>

<!-- Button to add more rows -->
<button type="button" id="{{$field->field_name}}AddMore" class="btn btn-primary">Add More</button>

<script>
    var addMoreObject = "#{{$field->field_name}}AddMore";
    var tableObject = "#{{$field->field_name}}Table";
    $(addMoreObject).on('click', function() {
        // Clone the first row
        var rowCount = $(tableObject+' tbody tr').length;
        var newRow = $(tableObject+' tbody tr:first').clone();

        // Clear input values in the new row
        newRow.find('input').val('');
        newRow.find('textarea').text('');
        newRow.find('select').val('');

        // Update the name attributes to reflect the new index
        newRow.find('input, textarea, select').each(function() {
            var name = $(this).attr('name');
            if (name) {
                // Update the index in the name attribute
                // console.log("before", name);
                var updatedName = name.replace(/\d+/, rowCount);
                // console.log("After", updatedName);

                $(this).attr('name', updatedName);
            }
        });

        newRow.find('button.removeRow').removeClass('d-none');

        // Append the new row to the table
        $(tableObject+' tbody').append(newRow);
    });

    // Handle row removal
    $(tableObject).on('click', '.removeRow', function() {
        $(this).closest('tr').remove();

        // Re-index the remaining rows
        $(tableObject+' tbody tr').each(function(index) {
            $(this).find('input, textarea, select').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    var updatedName = name.replace(/\d+/, index);
                    $(this).attr('name', updatedName);
                }
            });
        });
    });

</script>
