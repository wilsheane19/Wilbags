<?php

  require 'dbcon.php';


?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Brilliant Gem</title>
  </head>
  <body>
    

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Student Details
              <a href="student-create.php" class="btn btn-primary float-end:">Add Students</a>
            </h4>
          </div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Student Name</th>
                  <th>Age</th>
                  <th>Gender</th>
                  <th>Birth Date</th>
                  
            </table>
          </tr>
        </thead>
        <tbody>
       <?php
$query = "SELECT * FROM tblstudent";
$query_run = mysqli_query($con, $query);

if (mysqli_num_rows($query_run) > 0) {
    echo '<table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Birthdate</th>
                </tr>
            </thead>
            <tbody>';
    
    while ($student = mysqli_fetch_assoc($query_run)) {
        echo '<tr>
                <td>' . $student['student_name'] . '</td>
                <td>' . $student['age'] . '</td>
                <td>' . $student['gender'] . '</td>
                <td>' . $student['birthdate'] . '</td>
              </tr>';
    }

    echo '</tbody></table>';
} else {
    echo "<h5>No Record Found</h5>";
}
?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>