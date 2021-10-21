<?php
if(isset($_POST['signup-submit'])){

	require 'dbh.inc.php';

	$username = $_POST['uid'];
	$email = $_POST['mail'];
	$password = $_POST['pwd'];
	$passwordRepeat = $_POST['pwd-repeat'];
	$first_name = $_POST['fn'];
	$last_name = $_POST['ln'];
	$middle_name = $_POST['mn'];
	$department_id = $_POST['did'];

	$result=mysqli_query($conn,"SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'SCHEMAZ' AND TABLE_NAME = 'employee'");
	$datas = array();
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			$datas[] = $row;
		}
	}
	foreach ($datas[0] as $employee_num){


	if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat) || empty($first_name) || empty($last_name)){
		header("Location:../signup.php?error=emptyfields&uid=".$username."&mail=".$email);
		exit();
	}
	else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)){
		header("Location:../signup.php?error=invalidmailuid");
	exit();
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
		header("Location:../signup.php?error=invalidmail&uid=".$username);
	exit();
	}
	else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)){
		header("Location:../signup.php?error=invaliduid&mail=".$email);
	exit();
	}
	else if ($password !== $passwordRepeat){
		header("Location:../signup.php?error=passwordcheck&uid=".$username."&mail=".$email);
	exit();
	}
	else {
		$sql = "SELECT uidUsers FROM users WHERE uidUsers=?";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location:../signup.php?error=sqlerror");
			exit();
		}
		else{
			mysqli_stmt_bind_param($stmt, "s", $username);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$resultCheck = mysqli_stmt_num_rows($stmt);
			if ($resultCheck > 0) {
				header("Location:../signup.php?error=usertaken&mail=".$email);
				exit();
			}
			else{
				$sql = "INSERT INTO users (uidUsers, emailUsers, pwdUsers, employee_num) VALUES (?,?,?,?)";
				$stmt = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					header("Location:../signup.php?error=sqlerror");
					exit();
			}
			else{


				//INSERT INTO EMPLOYEE_DEPARTMENTS
				$sql_d = "INSERT INTO Employee_Departments (employee_num, department_id)
				VALUES ($employee_num,'$department_id')";
				if(mysqli_query($conn, $sql_d)){
						$employee_department_result= "Department Recorded Succesfully.";
				} else{
						$employee_department_result= "ERROR: Could not execute $sql_d. " . mysqli_error($conn);
				}


				//INSERT INTO EMPLOYEE
				$sql = "INSERT INTO Employee (first_name, last_name, middle_name, position)
				VALUES ('$first_name','$last_name','$middle_name','Department Chairperson')";
				if(mysqli_query($conn, $sql)){
						$employee_result= "Employee Record Added.";
				} else{
						$employee_result= "ERROR: Could not execute. $sql. " . mysqli_error($conn);
				}
				$hashedPwd = password_hash($password, PASSWORD_DEFAULT);
				mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $hashedPwd, $employee_num);
				mysqli_stmt_execute($stmt);
				header("Location:../signup.php?signup=success");
				exit();
			}
			}
		}

	}
	mysqli_stmt_close($stmt);
	mysqli_close($conn);

	}
}
	else{
		header("Location:../signup.php");
		exit();
	}
	?>
