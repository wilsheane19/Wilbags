<?php
// ... (your existing code)

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_instructor"])) {
    // ... (your existing code)

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success' role='alert'>Instructor updated successfully.</div>";
        
        // Redirect to the main page after successful update
        header("Location: instructors.php");
        exit;
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
    }
}

// ... (rest of your existing code)
?>
