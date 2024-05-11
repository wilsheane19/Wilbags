<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 20px;
        }
        .profile-image {
            max-width: 50px;
            max-height: 50px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../home.php" class="btn btn-primary btn-warning">Home</a>
        <h2 class="mt-4 mb-4">Schedules</h2>

        <!-- Display existing schedules in a table -->
        <table class='table table-bordered table-striped'>
            <thead>
                <tr>
                    <th scope="col">Instructor Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Profile Image</th>
                </tr>
            </thead>
            <tbody>
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

                // Read schedules from the database
                $sql = "SELECT instructor_name, status, profile_image FROM instructors";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["instructor_name"] . "</td>
                                <td>" . $row["status"] . "</td>
                                <td><img src='" . $row["profile_image"] . "' alt='Profile Image' class='profile-image'></td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No schedules found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
