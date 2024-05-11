<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Home</title>
    
</head>

<body>
    <?php 
       session_start();

       include("admin/assets/header.php");
       include("admin/assets/footer.php");
       include("php/config.php");
       if(!isset($_SESSION['valid'])){
        header("Location: index.php");
       }
    ?>
    <div class="right-links">
        <div class="text-end">
            <a href="php/logout.php"> <button class="btn">Log Out</button> </a>
            <?php 
                $id = $_SESSION['id'];
                $query = mysqli_query($con,"SELECT*FROM users WHERE Id=$id");

                while($result = mysqli_fetch_assoc($query)){
                    $res_Uname = $result['Username'];
                    $res_Email = $result['Email'];
                    $res_Age = $result['Age'];
                    $res_id = $result['Id'];
                }
            ?>
        </div>
    </div>

    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="font-weight-bold mb-4">Overview</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-info text-white mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Instructors</h5>
                                        <p class="card-text">3</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-primary text-white mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Students</h5>
                                        <p class="card-text">3</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Courses</h5>
                                <p class="card-text">2</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
