<?php
session_start();
require 'dbcon.php';

if(isset($_POST['save_student']))
{
	$student_name = mysqli_real_escape_string($con, $_POST['student_name']);
	$age = mysqli_real_escape_string($con, $_POST['age']);
	$gender = mysqli_real_escape_string($con, $_POST['gender']);
	$birthdate = mysqli_real_escape_string($con, $_POST['birthdate']);
	


	$query = "INSERT INTO tblstudent (student_name,age,gender,birthdate) VALUES 
	('$student_name','$age','$gender','$birthdate')";

	$query_run = mysqli_query($con, $query);
	if($query_run)
	{
		$_SESSION['message'] = "Student Created Successfully";
		header("Location: student-create.php");
		exit(0);
	}
	else
	{
		$_SESSION['message'] = "Student not Created";
		header("Location: student-create.php");
		exit(0);
	
	}

}
?>