<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-4">
            <div class="card m-2">
                <div class="card-header">Select Fields</div>
                <div class="card-body">
                    <form id="searchFilterForm" action="#" method="POST">
                        @csrf
                        <div class="row">
                            @foreach($fields as $field)
                                <div class="col-12">
                                    <input type="checkbox" name="selected_fields[]" value="{{ $field }}" id="{{ $field }}">
                                    <label for="{{ $field }}">
                                        {{ ucwords(str_replace('_', ' ', $field)) }}
                                    </label>
                                </div>
                            @endforeach
                            <div class="col-12">
                                <button type="submit" id="search_record" class="btn btn-primary">Search Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
            <div class="col-8">
            <div class="card m-2">
                <div class="card-header">Search Form</div>
                <div class="card-body">
                    <form id="apply-filter"  action="{{ route('getrecord') }}" method="POST">
                        <input type="hidden" name="searchId" id="searchId" value="{{ $searchId??null }}">
                        @csrf
                        <div id="render_field"></div>
                        <button type="submit" id="getrecord" class="btn btn-primary">Apply Filter</button>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        // Handle form submission to dynamically display selected fields
    $('#searchFilterForm').on('submit', function(event) {
        event.preventDefault();  // Prevent the default form submission

        // Get the selected fields from the checkboxes
        const selectedFields = [];
        $('input[name="selected_fields[]"]:checked').each(function() {
            selectedFields.push($(this).val());  // Add the value of each checked checkbox
        });

        var uuid = $('input#uuid').val();

        // Make an AJAX request to get the selected fields from the report_builder
        $.ajax({
            url: '/get-filtered-fields', // Your route to the controller method
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',  // Include CSRF token
                selected_fields: selectedFields
            },
            success: function(response) {
                // Clear previous rendered fields
                $('#render_field').html('');  // Clear any previous HTML content in the div
                $('#render_field').append(response.html);  // Append the new HTML from the response
                // $('#uuid').val(response.uuid);  // Set the uuid value in the hidden input
            },
            error: function() {
                alert("Failed to load the selected fields.");
            }
        });
    });


    // Function to render options for a select field
    function renderSelectOptions(optionInfo) {
        let optionsHtml = '';

        // Parse the optionInfo string and ensure it's an object
        let options = {};
        try {
            options = optionInfo ? JSON.parse(optionInfo) : {};
        } catch (error) {
            console.error('Error parsing optionInfo:', error);
        }

        // Check if options is an object and loop through the keys and values
        if (typeof options === 'object' && options !== null) {
            // Convert the object into an array of key-value pairs (e.g., [[key, value], [key, value], ...])
            Object.entries(options).forEach(([key, value]) => {
                optionsHtml += `<option value="${key}">${value}</option>`;
            });
        } else {
            console.warn('options is not an object:', options);
        }

        return optionsHtml;
    }


    // Function to capitalize the first letter of each word in the field name
    function capitalizeFirstLetter(string) {
        return string.replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
    }
</script>

</body>

</html>
