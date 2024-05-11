<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "login";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve scheduling information for both students and instructors
$sqlSchedulingInformation = "SELECT ss.*, s.student_name, i.instructor_name
                             FROM student_scheduling ss
                             JOIN students s ON ss.student_id = s.id
                             JOIN instructors i ON ss.instructor_id = i.id";
$resultSchedulingInformation = $conn->query($sqlSchedulingInformation);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            background-color: #ffffff;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        .day {
            border: 1px solid #ced4da;
            padding: 10px;
            height: 100px;
            position: relative;
            background-color: #ffffff;
            border-radius: 5px;
        }

        .event {
            background-color: #007bff;
            color: #fff;
            padding: 5px;
            margin-bottom: 5px;
            border-radius: 5px;
            cursor: pointer;
        }

        .popover {
            max-width: 300px;
        }

        .popover-header {
            background-color: #007bff;
            color: #fff;
            border-bottom: none;
            border-top-left-radius: calc(.3rem - 1px);
            border-top-right-radius: calc(.3rem - 1px);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../home.php" class="btn btn-primary btn-warning mb-3">Home</a>
        <h2 class="mb-4">Calendar</h2>

        <div class="calendar">
            <?php
            // Print the calendar cells with scheduling information
            while ($row = $resultSchedulingInformation->fetch_assoc()) {
                $date = date("Y-m-d", strtotime($row['start_time']));
                $startTime = date("h:i A", strtotime($row['start_time']));
                $endTime = date("h:i A", strtotime($row['end_time']));
                $studentName = $row['student_name'];
                $instructorName = $row['instructor_name'];

                echo '<div class="day">
                        <div class="event" data-bs-toggle="popover" title="' . $studentName . ' with ' . $instructorName . '" data-bs-content="Date: ' . $date . '<br>Time: ' . $startTime . ' - ' . $endTime . '">
                            ' . $date . '
                        </div>
                      </div>';
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            $('[data-bs-toggle="popover"]').popover({
                trigger: 'hover',
                placement: 'top',
                html: true
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
