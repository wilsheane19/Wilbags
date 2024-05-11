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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_instructor"])) {
    $instructorId = $_POST["instructor_id"];

    // Check if the instructor exists
    $checkInstructorQuery = "SELECT id FROM instructors WHERE id = $instructorId";
    $checkInstructorResult = $conn->query($checkInstructorQuery);

    if ($checkInstructorResult->num_rows > 0) {
        // Delete the instructor
        $deleteInstructorQuery = "DELETE FROM instructors WHERE id = $instructorId";
        if ($conn->query($deleteInstructorQuery) === TRUE) {
            echo "<div class='alert alert-success' role='alert'>Instructor deleted successfully.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>Instructor not found.</div>";
    }
}

// Retrieve instructor data for pre-filling the form
if (isset($_GET["id"])) {
    $instructorId = $_GET["id"];
    $sql = "SELECT * FROM instructors WHERE id = $instructorId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $instructorName = $row["instructor_name"];
        $profileImage = $row["profile_image"];
    } else {
        echo "<div class='alert alert-danger' role='alert'>Instructor not found.</div>";
        exit;
    }
} else {
    // Redirect to the main page or handle the situation as needed
    header("Location: add_instructor.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Instructor - Driving School Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            margin: 20px;
        }

        form {
            max-width: 400px;
            margin: 20px 0;
        }

        .profile-image {
            max-width: 200px;
            margin-bottom: 20px;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4 mb-4">Delete Instructor</h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="instructor_id" value="<?php echo $instructorId; ?>">
            
            <div class="form-group">
                <label for="instructor_name">Instructor Name:</label>
                <input type="text" class="form-control" value="<?php echo $instructorName; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="profile_image">Profile Image:</label>
                <br>
                <img src="<?php echo $profileImage; ?>" alt="Profile Image" class="profile-image">
            </div>

            <button type="submit" name="delete_instructor" class="btn btn-danger">Delete Instructor</button>
            <a href="add_instructor.php" class="btn btn-secondary">Back to Main Page</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
