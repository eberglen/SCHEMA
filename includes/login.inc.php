<?php
	require 'dbconn.inc.php';

	$username = $_POST['username'];
	$password = $_POST['password'];

	if (empty($username) || empty($password)){
		header("Location:../index.php?error=emptyfields");
		exit();
}
else{
	$sql = "SELECT * FROM users WHERE uidUsers=?;";
	$stmt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt, $sql)){
		header("Location:../index.php?error=sqlerror");
		exit();
	}
	else{
		mysqli_stmt_bind_param($stmt, "s", $username);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		if ($row = mysqli_fetch_assoc($result)){
			$pwdCheck = password_verify($password, $row['pwdUsers']);
			if($pwdCheck == false){
				header("Location:../index.php?error=wrongpwd");
			exit();
			}
			else if($pwdCheck == true){
				session_start(); //STORE DEPARTMENT ID HERE

				$_SESSION['dept_id'] = $row['department_id'];
				$_SESSION['level_access'] = $row['level_access'];
				$_SESSION['username'] = $row['uidUsers'];

				if($_SESSION['level_access'] == 'admin'){
					header("Location:../home_admin.php");
					exit();
				}
				else if($_SESSION['level_access'] == 'dept_chair'){
					header("Location:../schedule.php?");
					exit();
				}
				else if($_SESSION['level_access'] == 'dean'){
					header("Location:../home_dean.php");
					exit();
				}

			}
			else{
				header("Location:../index.php?error=wrongpwd");
				exit();
			}
			}
			else{
				header("Location:../index.php?error=nouser");
				exit();
			}


		}
	}
