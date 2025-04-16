<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>User Form</h1>
        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" >
                </div>


            <!-- Gender -->
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="Male" >Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

            <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="">
                </div>

            <!-- Languages -->
                <div class="mb-3">
                    <label for="languages" class="form-label">Languages</label>
                    <select class="form-control" id="languages" name="languages[]" multiple>
                            <option value=""></option>
                    </select>
                </div>

            <!-- Education -->
                <div class="mb-3">
                    <label for="education" class="form-label">Education</label>
                    <div id="education">
                            <div class="mb-3">
                                <input type="text" class="form-control mb-2" name="education[year][]" placeholder="Year" value="">
                                <input type="text" class="form-control mb-2" name="education[degree_id][]" placeholder="Degree ID" >
                                <input type="text" class="form-control mb-2" name="education[university][]" placeholder="University" >
                                <input type="text" class="form-control" name="education[result][]" placeholder="Result" >
                            </div>
                    </div>
                </div>

            <!-- Date of Birth -->
                <div class="mb-3">
                    <label for="date_of_birth_from" class="form-label">Date of Birth</label>
                    <div class="d-flex gap-3">
                        <input type="date" class="form-control" id="date_of_birth_to" name="date_of_birth" >
                    </div>
                </div>

            <!-- Address -->
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address"></textarea>
                </div>


            <!-- File -->
                <div class="mb-3">
                    <label for="file" class="form-label">File</label>
                    <input type="file" class="form-control" id="file" name="file">
                </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
