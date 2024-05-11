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

// Check if the instructor_id is provided in the URL
if (isset($_GET['instructor_id'])) {
    $instructorId = $_GET['instructor_id'];

    // Fetch instructor details
    $sqlInstructor = "SELECT * FROM instructors WHERE id = $instructorId";
    $resultInstructor = $conn->query($sqlInstructor);

    if ($resultInstructor->num_rows > 0) {
        $instructor = $resultInstructor->fetch_assoc();

        // Fetch scheduling details for the instructor
        $sqlScheduling = "SELECT * FROM scheduling WHERE instructor_id = $instructorId";
        $resultScheduling = $conn->query($sqlScheduling);

        // Display instructor and scheduling details
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Driving School Management System - Instructor Schedule</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <style>
                body {
                    margin: 20px;
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
            </style>
        </head>
        <body>
            <div class="container">
                <a href="../home.php" class="btn btn-primary">Back to Home</a>
                <a href="add_instructor.php" class="btn btn-warning">Back</a>
                <h2 class="mt-4 mb-4">Instructor Schedule</h2>

                <h3>Instructor Details:</h3>
                <p>ID: <?php echo $instructor['id']; ?></p>
                <p>Name: <?php echo $instructor['instructor_name']; ?></p>
                <p>Status: <?php echo $instructor['status']; ?></p>
                <img src="<?php echo $instructor['profile_image']; ?>" alt="Profile Image" style="max-width: 100px;">

                <h3>Schedule Details:</h3>
                <?php
                if ($resultScheduling->num_rows > 0) {
                    echo "<table class='table table-bordered table-striped'>
                            <thead>
                                <tr>
                                    <th>Day of the Week</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                </tr>
                            </thead>
                            <tbody>";

                    while ($row = $resultScheduling->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["day_of_week"] . "</td>
                                <td>" . $row["start_time"] . "</td>
                                <td>" . $row["end_time"] . "</td>
                            </tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo "<p>No schedule found for this instructor.</p>";
                }
                ?>
            </div>
        </body>
        </html>
<?php
    } else {
        echo "Instructor not found.";
    }
} else {
    echo "Instructor ID not provided in the URL.";
}

$conn->close();
?>