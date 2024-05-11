<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "login";

// Recipient email address for notification
$recipientEmail = "takamimsubinokami@gmail.com";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlInstructors = "SELECT id, instructor_name FROM instructors";
$resultInstructors = $conn->query($sqlInstructors);
$instructors = [];

if ($resultInstructors->num_rows > 0) {
    while ($row = $resultInstructors->fetch_assoc()) {
        $instructors[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_student"])) {
    $studentName = $_POST["student_name"];
    $instructorId = $_POST["instructor_id"];

    $sqlGetStudentId = "SELECT id FROM students WHERE student_name = '$studentName'";
    $resultGetStudentId = $conn->query($sqlGetStudentId);

    if ($resultGetStudentId) {
        if ($resultGetStudentId->num_rows > 0) {
            $row = $resultGetStudentId->fetch_assoc();
            $studentId = $row['id'];

            $scheduleCount = count($_POST["start_date"]);

            for ($i = 0; $i < $scheduleCount; $i++) {
                $startDate = $_POST["start_date"][$i];
                $endDate = $_POST["end_date"][$i];

                $sqlStudent = "INSERT INTO student_scheduling (student_id, instructor_id, start_date, end_date)
                              VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sqlStudent);
                $stmt->bind_param("iiss", $studentId, $instructorId, $startDate, $endDate);
                $stmt->execute();
                $stmt->close();
            }

            // Send email notification
            $subject = "New Student Added";
            $message = "A new student, $studentName, has been added with instructor ID: $instructorId.";
            $headers = "From: mikhailvalmores12@gmail.com"; // Change sender@gmail.com to your Gmail address

            mail($recipientEmail, $subject, $message, $headers);

            echo "<div class='alert alert-success' role='alert'>Student and Schedules added successfully. Notification sent.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: Student not found.</div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error fetching student ID: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Interface</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 20px;
        }

        .schedule-group {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="https://demos.creative-tim.com/material-dashboard/pages/dashboard" target="_blank">
                <span class="ms-0 font-weight-bold text-white">Brilliant Gem Technical School</span>
            </a>
        </div>
        <hr class="horizontal light mt-0 mb-2">
        <div class="collapse navbar-collapse w-auto max-height-vh-100" id="sidenav-collapse-main">
            <ul class="navbar-nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white " href="admin/student.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">people</i>
                        </div>
                        <span class="nav-link-text ms-1">Student</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="admin/view_schedules.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">schedule</i>
                        </div>
                        <span class="nav-link-text ms-1">Instructor Schedule</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="admin/add_instructor.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <span class="nav-link-text ms-1">Instructor</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="admin/scheduling_information.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">today</i>
                        </div>
                        <span class="nav-link-text ms-1">Scheduling Information</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="admin/add.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">queue</i>
                        </div>
                        <span class="nav-link-text ms-1">Add Enrollee</span>

                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="admin/calendar.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">event</i>
                        </div>
                        <span class="nav-link-text ms-1">Calendar</span>

                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main content -->
    <div class="container">
        <a href="../home.php" class="btn btn-primary btn-warning">Home</a>
        <h2 class="mt-4 mb-4">Student Interface</h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="student_name" class="form-label">Student Name:</label>
                <input type="text" name="student_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="instructor_id" class="form-label">Select Instructor:</label>
                <select name="instructor_id" class="form-select" required>
                    <?php
                    foreach ($instructors as $instructor) {
                        echo "<option value='" . $instructor["id"] . "'>" . $instructor["instructor_name"] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Scheduling fields -->
            <div id="schedule-container">
                <div class="schedule-group">
                    <label for="start_date" class="form-label">Start Date:</label>
                    <input type="date" name="start_date[]" class="form-control" required>
                </div>
                <div class="schedule-group">
                    <label for="end_date" class="form-label">End Date:</label>
                    <input type="date" name="end_date[]" class="form-control" required>
                </div>
            </div>

            <button type="submit" name="add_student" class="btn btn-primary mt-2">Add Student</button>
        </form>

        <?php
        // Display selected instructor and schedule information here...
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>
