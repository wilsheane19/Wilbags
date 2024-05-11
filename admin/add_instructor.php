<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            margin: 20px;
        }

        form {
            max-width: 400px;
            margin: 20px 0;
        }

        table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
        }

        th, td {
            padding: 15px;
        }

        th {
            background-color: #f2f2f2;
        }

        .alert {
            margin-top: 20px;
        }

        .btn-action {
            margin-right: 5px;
        }

        /* New style for the "Back to Home" button */
        .btn-back-to-home {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../home.php" class="btn btn-primary btn-warning">Home</a>
        <h2 class="mt-4 mb-4">Instructors</h2>

        <?php
        // Connection to the database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "login";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Update status
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_status"])) {
            $instructorId = $_POST["instructor_id"];
            $status = $_POST["status"];

            $sql = "UPDATE instructors SET status='$status' WHERE id=$instructorId";

            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success' role='alert'>Status updated successfully.</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>Error updating status: " . $conn->error . "</div>";
            }
        }

        // Create
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_instructor"])) {
            $instructorName = $_POST["instructor_name"];

            // Handle profile image upload
            $targetDir = "uploads/";
            $profileImage = basename($_FILES["profile_image"]["name"]);
            $targetFilePath = $targetDir . $profileImage;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            // Check if image file is an actual image or fake image
            $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
            if ($check === false) {
                echo "<div class='alert alert-danger' role='alert'>File is not an image.</div>";
                $uploadOk = 0;
            }

            // Create the "uploads" directory if it doesn't exist
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Check if file already exists and handle filename conflicts
            $counter = 1;
            while (file_exists($targetFilePath)) {
                $profileImage = pathinfo($profileImage, PATHINFO_FILENAME) . "_" . $counter . "." . $imageFileType;
                $targetFilePath = $targetDir . $profileImage;
                $counter++;
            }

            // Check file size
            if ($_FILES["profile_image"]["size"] > 500000) {
                echo "<div class='alert alert-danger' role='alert'>Sorry, your file is too large.</div>";
                $uploadOk = 0;
            }

            // Allow certain file formats
            $allowedFormats = ["jpg", "jpeg", "png", "gif"];
            if (!in_array($imageFileType, $allowedFormats)) {
                echo "<div class='alert alert-danger' role='alert'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "<div class='alert alert-danger' role='alert'>Sorry, your file was not uploaded.</div>";
            } else {
                // If everything is ok, try to upload file
                if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath)) {
                    $sql = "INSERT INTO instructors (instructor_name, profile_image) VALUES ('$instructorName', '$targetFilePath')";
                    if ($conn->query($sql) === TRUE) {
                        echo "<div class='alert alert-success' role='alert'>Instructor added successfully.</div>";
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>Error adding instructor: " . $conn->error . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Sorry, there was an error uploading your file.</div>";
                }
            }
        }

        // Read
        $sql = "SELECT * FROM instructors";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Instructor Name</th>
                            <th>Status</th>
                            <th>Profile Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["instructor_name"] . "</td>
                        <td>" . $row["status"] . "</td>
                        <td><img src='" . $row["profile_image"] . "' alt='Profile Image' style='max-width: 100px;'></td>
                        <td>
                            <form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."' style='display:inline;'>
                                <input type='hidden' name='instructor_id' value='" . $row["id"] . "'>
                                <select name='status' class='form-control'>
                                    <option value='Pending'>Pending</option>
                                    <option value='On Leave'>On Leave</option>
                                    <option value='Active'>Active</option>
                                    <option value='Inactive'>Inactive</option>
                                    <option value='Terminated'>Terminated</option>
                                </select>
                                <button type='submit' name='update_status' class='btn btn-primary btn-action'>Update Status</button>
                            </form>
                            <a href='edit_instructor.php?id=" . $row["id"] . "' class='btn btn-info btn-action'>Edit</a>
                            <a href='delete_instructor.php?id=" . $row["id"] . "' class='btn btn-danger btn-action'>Delete</a>
                            <a href='schedule.php?instructor_id=" . $row["id"] . "' class='btn btn-secondary btn-action'>View Schedule</a>
                        </td>
                    </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>No instructors found.</p>";
        }

        $conn->close();
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="instructor_name">Instructor Name:</label>
                <input type="text" name="instructor_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="profile_image">Add Profile Picture:</label>
                <input type="file" name="profile_image" class="form-control-file" accept="image/*" required>
            </div>
            <button type="submit" name="add_instructor" class="btn btn-primary">Add Instructor</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
