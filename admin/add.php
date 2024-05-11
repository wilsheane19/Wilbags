<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "login";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_student"])) {
        $studentName = $_POST["student_name"];

        $sqlCheckStudent = "SELECT id FROM students WHERE student_name = '$studentName'";
        $resultCheckStudent = $conn->query($sqlCheckStudent);

        if ($resultCheckStudent && $resultCheckStudent->num_rows > 0) {
            $successMessage = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                Student already exists.
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>";
        } else {
            $sqlAddStudent = "INSERT INTO students (student_name) VALUES ('$studentName')";
            $resultAddStudent = $conn->query($sqlAddStudent);

            if ($resultAddStudent) {
                $newStudentId = $conn->insert_id;
                $successMessage = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Student added successfully with ID: $newStudentId
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>";
            } else {
                $successMessage = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Error adding student: " . $conn->error .
                    "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>";
            }
        }
    } elseif (isset($_POST["remove_student_by_name"])) {
        $studentNameToRemove = $_POST["student_name_to_remove"];
        $sqlRemoveStudent = "DELETE FROM students WHERE student_name = '$studentNameToRemove'";
        $resultRemoveStudent = $conn->query($sqlRemoveStudent);

        if ($resultRemoveStudent) {
            $successMessage = "<div class='alert alert-success' role='alert'>Student removed successfully.</div>";
        } else {
            $successMessage = "<div class='alert alert-danger' role='alert'>Error removing student: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: url('rel8.jpg') center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 400px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4 mb-4">Student Management</h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="student_name">Student Name:</label>
                <input type="text" name="student_name" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="../home.php" class="btn btn-secondary">Home</a>
                <button type="submit" name="add_student" class="btn btn-primary">Add Student</button>
            </div>

            <?php echo $successMessage; ?>
        </form>

        <hr>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="student_name_to_remove">Enter Student Name:</label>
                <input type="text" name="student_name_to_remove" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" name="remove_student_by_name" class="btn btn-danger">Remove Student</button>
            </div>
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
