<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "login";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete
if (isset($_GET["id"])) {
    $studentId = $_GET["id"];

    $sql = "DELETE FROM students WHERE id=$studentId";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error deleting student: " . $conn->error . "</div>";
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
    <title>Delete Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Delete Student</h2>
        <p>Are you sure you want to delete this student?</p>

        <a href="index.php" class="btn btn-secondary">Cancel</a>
        <a href="<?php echo "delete_student.php?id={$studentId}&confirm=true"; ?>" class="btn btn-danger">Delete</a>

        <?php
        if (isset($_GET["confirm"]) && $_GET["confirm"] === "true") {
            echo "<div class='alert alert-success mt-3' role='alert'>Student deleted successfully.</div>";
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
