<?php
if (($_POST['dept'] != '') && !empty($_POST['uidUsers']) && !empty($_POST['pwd']) && !empty($_POST['pwd_rpt']) && ($_POST['position'] != '')){
  require 'dbconn.inc.php';
  $dept = $_POST['dept'];
  $username = $_POST['uidUsers'];
  $password = $_POST['pwd'];
  $passwordRepeat = $_POST['pwd_rpt'];
  $position = $_POST['position'];

  if (!preg_match("/^[a-zA-Z0-9]*$/", $username)){
		echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
		<strong>Invalid. </strong>Invalid username.
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
		</button>
		</div>';
	}
	else if ($password !== $passwordRepeat){
		echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
		<strong>Passwords do not match. </strong>Please check the passwords.
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
		</button>
		</div>';
	}
  else {
    $sql = "SELECT uidUsers FROM users WHERE uidUsers=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>ERROR. </strong>SQL error.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
      </div>';
    }
    else{
      mysqli_stmt_bind_param($stmt, "s", $username);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCheck = mysqli_stmt_num_rows($stmt);
      if ($resultCheck > 0) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Username taken. </strong>Please enter a new username.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>';
      }
      else{
        $sql = "INSERT INTO users (uidUsers, pwdUsers, department_id, level_access) VALUES (?,?,?,?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>ERROR. </strong>SQL error.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>';
      }
      else{

        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ssss", $username, $hashedPwd, $dept, $position);
        mysqli_stmt_execute($stmt);
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>User created successfully.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick="wind_ref()">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>';

      }
      }
    }

  }
}
else{
  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Warning: </strong>Empty fields.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  </div>';
}

?>
