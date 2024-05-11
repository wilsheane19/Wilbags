<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "login";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to remove scheduling entry
function removeSchedule($conn, $scheduleId) {
    $sqlRemoveSchedule = "DELETE FROM student_scheduling WHERE id = $scheduleId";
    if ($conn->query($sqlRemoveSchedule) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to edit scheduling entry
function editSchedule($conn, $scheduleId, $startTime, $endTime, $scheduleDate, $startDate, $endDate) {
    $sqlEditSchedule = "UPDATE student_scheduling SET start_time = '$startTime', end_time = '$endTime', schedule_date = '$scheduleDate', start_date = '$startDate', end_date = '$endDate' WHERE id = $scheduleId";
    if ($conn->query($sqlEditSchedule) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Handle form submission for removing or editing schedule
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["remove_schedule"])) {
        $scheduleIdToRemove = $_POST["schedule_id_to_remove"];
        // Show SweetAlert confirmation dialog
        echo '<script>
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this schedule!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        document.getElementById("removeScheduleForm_' . $scheduleIdToRemove . '").submit();
                    } else {
                        swal("Your schedule is safe!", {
                            icon: "info",
                        });
                    }
                });
            </script>';
    } elseif (isset($_POST["edit_schedule"])) {
        $scheduleIdToEdit = $_POST["schedule_id_to_edit"];
        $newStartTime = $_POST["start_time"];
        $newEndTime = $_POST["end_time"];
        $newScheduleDate = $_POST["schedule_date"];
        $newStartDate = $_POST["start_date"];
        $newEndDate = $_POST["end_date"];
        if (editSchedule($conn, $scheduleIdToEdit, $newStartTime, $newEndTime, $newScheduleDate, $newStartDate, $newEndDate)) {
            echo "<div class='alert alert-success' role='alert'>Schedule updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error updating schedule: " . $conn->error . "</div>";
        }
    }
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
    <title>Scheduling Information</title>
    <!-- Add Bootstrap Datepicker and Timepicker CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="https://unpkg.com/sweetalert/dist/sweetalert.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <a href="../home.php" class="btn btn-primary btn-warning mb-3">Home</a>
        <h2 class="mb-4">Scheduling Information</h2>

        <?php
        if ($resultSchedulingInformation->num_rows > 0) {
            echo '<table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Instructor Name</th>
                            <th>Schedule Date</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($row = $resultSchedulingInformation->fetch_assoc()) {
                // Format time as AM/PM
                $formattedStartTime = date("h:i A", strtotime($row['start_time']));
                $formattedEndTime = date("h:i A", strtotime($row['end_time']));
                $formattedScheduleDate = date("Y-m-d", strtotime($row['schedule_date']));
                $formattedStartDate = date("Y-m-d", strtotime($row['start_date']));
                $formattedEndDate = date("Y-m-d", strtotime($row['end_date']));

                echo '<tr>
                        <td>' . $row['id'] . '</td>
                        <td>' . $row['student_name'] . '</td>
                        <td>' . $row['instructor_name'] . '</td>
                        <td>' . $formattedScheduleDate . '</td>
                        <td>' . $formattedStartDate . '</td>
                        <td>' . $formattedEndDate . '</td>
                        <td>' . $formattedStartTime . '</td>
                        <td>' . $formattedEndTime . '</td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="editSchedule(' . $row['id'] . ', \'' . $row['start_time'] . '\', \'' . $row['end_time'] . '\', \'' . $row['schedule_date'] . '\', \'' . $row['start_date'] . '\', \'' . $row['end_date'] . '\')">Edit</button>
                            <form id="removeScheduleForm_' . $row['id'] . '" method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" class="d-inline">
                                <input type="hidden" name="schedule_id_to_remove" value="' . $row['id'] . '">
                                <button type="button" onclick="confirmDelete(' . $row['id'] . ')" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                      </tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<div class="alert alert-info" role="alert">No scheduling information available.</div>';
        }
        ?>

    </div>

    <div class="modal fade" id="editScheduleModal" tabindex="-1" role="dialog" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editScheduleModalLabel">Edit Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" id="scheduleId" name="schedule_id_to_edit">
                    <div class="form-group">
                        <label for="scheduleDate">Schedule Date:</label>
                        <input type="text" id="scheduleDate" name="schedule_date" class="form-control datepicker" required>
                    </div>
                    <div class="form-group">
                        <label for="startDate">Start Date:</label>
                        <input type="text" id="startDate" name="start_date" class="form-control datepicker" required>
                    </div>
                    <div class="form-group">
                        <label for="endDate">End Date:</label>
                        <input type="text" id="endDate" name="end_date" class="form-control datepicker" required>
                    </div>
                    <div class="form-group">
                        <label for="startTime">Start Time:</label>
                        <input type="text" id="startTime" name="start_time" class="form-control timepicker" required>
                    </div>
                    <div class="form-group">
                        <label for="endTime">End Time:</label>
                        <input type="text" id="endTime" name="end_time" class="form-control timepicker" required>
                    </div>
                    <button type="submit" name="edit_schedule" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editSchedule(scheduleId, startTime, endTime, scheduleDate, startDate, endDate) {
        $('#editScheduleModal').modal('show');
        $('#scheduleId').val(scheduleId);
        $('#scheduleDate').val(scheduleDate);
        $('#startDate').val(startDate);
        $('#endDate').val(endDate);
        $('#startTime').val(convertToPhilippineTime(startTime));
        $('#endTime').val(convertToPhilippineTime(endTime));

        // Initialize Bootstrap Datepicker and Timepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        $('.timepicker').timepicker({
            showMeridian: true,
            minuteStep: 1,
            defaultTime: false
        });
    }

    // Function to convert time to Philippine time
    function convertToPhilippineTime(time) {
        var timeUtc = moment.utc(time, 'HH:mm:ss');
        var timePh = timeUtc.clone().tz('Asia/Manila');
        return timePh.format('hh:mm A');
    }

    function confirmDelete(scheduleId) {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this schedule!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                // If user confirms, submit the form
                document.getElementById("removeScheduleForm_" + scheduleId).submit();
            } else {
                swal("Your schedule is safe!", {
                    icon: "info",
                });
            }
        });
    }

    // SweetAlert for editing schedule
    function confirmEditSchedule() {
        swal({
            title: "Are you sure?",
            text: "Once edited, this schedule will be updated.",
            icon: "info",
            buttons: true,
            dangerMode: false,
        })
        .then((willEdit) => {
            if (willEdit) {
                // If user confirms, submit the form
                document.getElementById("editScheduleForm").submit();
            } else {
                swal("Your changes have been discarded.", {
                    icon: "info",
                });
            }
        });
    }
</script>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
