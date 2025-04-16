<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report lists</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQ9zKIiDzwK4yuNSR1IoNJjFJjG2kC8BTU8lU2eBzj9K/nTpvxGo61PDC" crossorigin="anonymous">
</head>
<body>
    @if (isset($id))
        <a href="{{ route('exportExcel', $id) }}">Export To Excel</a>
    @endif

    <a href="{{ route('report.search',["searchId"=>$id]) }}">Go Back</a>

    @include('report.results')

</body>
<footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        $(document).on('click', '.downloadFile', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            // Make AJAX request
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if (response.success === true) {
                        // Create an invisible anchor element
                        var a = document.createElement('a');
                        a.href = response.downloadUrl; // URL from the server
                        a.download = ''; // Optional: specify the filename if necessary
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    } else {
                        alert(response.message); // Show error if file not found
                    }
                },
                error: function(xhr, status, error) {
                    alert('No files found in the specified folder.'); // Show generic error alert
                }
            });
        });
    </script>
</footer>
</html>
