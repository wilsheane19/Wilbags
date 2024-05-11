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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_instructor"])) {
    $instructorId = $_POST["instructor_id"];
    $instructorName = $_POST["instructor_name"];

    // Handle profile image update
    $targetDir = "uploads/";
    $profileImage = basename($_FILES["profile_image"]["name"]);
    $targetFilePath = $targetDir . $profileImage;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Check if a file has been selected
    if (!empty($_FILES["profile_image"]["tmp_name"]) && is_uploaded_file($_FILES["profile_image"]["tmp_name"])) {
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger' role='alert'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Create the "uploads" directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
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
    } else {
        // No file selected, continue without updating the profile image
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<div class='alert alert-danger' role='alert'>Sorry, your file was not uploaded.</div>";
    } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath)) {
            // Check if the instructor with the given name already exists
            $existingInstructorQuery = "SELECT id FROM instructors WHERE instructor_name = '$instructorName'";
            $existingInstructorResult = $conn->query($existingInstructorQuery);

            if ($existingInstructorResult->num_rows > 0) {
                // Update existing instructor
                $row = $existingInstructorResult->fetch_assoc();
                $existingInstructorId = $row["id"];
                $sql = "UPDATE instructors SET profile_image = '$targetFilePath' WHERE id = $existingInstructorId";
            } else {
                // Create a new instructor
                $sql = "INSERT INTO instructors (instructor_name, profile_image) VALUES ('$instructorName', '$targetFilePath')";
            }

            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success' role='alert'>Instructor ";
                echo (!empty($instructorId) ? "updated" : "added") . " successfully.</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Sorry, there was an error uploading your file.</div>";
        }
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
    $instructorId = ""; // Set instructorId to empty for new instructor
    $instructorName = "";
    $profileImage = "";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Instructor - Driving School Management System</title>
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
        <h2 class="mt-4 mb-4">Edit Instructor</h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <input type="hidden" name="instructor_id" value="<?php echo $instructorId; ?>">
            
            <div class="form-group">
                <label for="instructor_name">Instructor Name:</label>
                <input type="text" name="instructor_name" class="form-control" value="<?php echo $instructorName; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="profile_image">Profile Image:</label>
                <input type="file" name="profile_image" class="form-control-file" accept="image/*">
            </div>
            
            <div class="form-group">
                <img src="<?php echo $profileImage; ?>" alt="Profile Image" class="profile-image">
            </div>

            <button type="submit" name="update_instructor" class="btn btn-primary">Update Instructor</button>
            <!-- Add Back to Main Page button -->
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
