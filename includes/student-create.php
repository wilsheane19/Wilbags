<?php
  session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
    <div class="container mt-3">
      <?php include('message.php');
      ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Add Student
              <a href="" class="btn btn-danger float-end">Back</a>
                </h4>
              </div>
              <div class="card-body">
                <form action="code.php" method="POST">


                   <div class="mb-3"> 
                  <label>Student Name</label>
                  <input type="text" name="student_name" class="form-control">
                </div>    

                   <div class="mb-3"> 
                  <label>Age</label>
                  <input type="text" name="age" class="form-control">
                </div>    


                   <div class="mb-3"> 
                  <label>Gender</label>
                  <input type="text" name="gender" class="form-control">
                </div>    


                   <div class="mb-3"> 
                  <label>Birthdate</label>
                  <input type="text" name="birthdate" class="form-control">
                </div>    
                <div class="mb-3">
                  <button type="submit" name="save_student" class="btn btn-primary">Save Student</button>
                </div>


    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>