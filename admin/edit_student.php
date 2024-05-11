<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "login";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_student"])) {
        $studentId = $_POST["student_id"];
        $studentName = $_POST["student_name"];
        $email = $_POST["email"];
        $phoneNumber = $_POST["phone_number"];

        $sql = "UPDATE students
                SET student_name='$studentName', email='$email', phone_number='$phoneNumber'
                WHERE id=$studentId";

        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error updating student: " . $conn->error . "</div>";
        }
    }
}

// Read student details
if (isset($_GET["id"])) {
    $studentId = $_GET["id"];
    $sql = "SELECT * FROM students WHERE id=$studentId";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $studentName = $row["student_name"];
        $email = $row["email"];
        $phoneNumber = $row["phone_number"];
    } else {
        echo "<div class='alert alert-danger' role='alert'>Student not found.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>Invalid request.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Student</h2>

        <!-- Edit Student Form -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="student_id" value="<?php echo $studentId; ?>">
            <div class="form-group">
                <label for="student_name">Student Name:</label>
                <input type="text" name="student_name" class="form-control" value="<?php echo $studentName; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" class="form-control" value="<?php echo $phoneNumber; ?>" required>
            </div>
            <button type="submit" name="update_student" class="btn btn-primary">Update Student</button>
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