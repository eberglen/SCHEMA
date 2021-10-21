<?php
if (!empty($_POST['cur_pwd']) && !empty($_POST['new_pwd']) && !empty($_POST['rpt_pwd'])){
  session_start();
  include 'dbconn.inc.php';
  $cur_pwd = $_POST['cur_pwd'];
  $new_pwd = $_POST['new_pwd'];
  $rpt_pwd = $_POST['rpt_pwd'];
  if ($new_pwd !== $rpt_pwd){
		echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
		<strong>Passwords do not match. </strong>Please check the passwords.
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
		</button>
		</div>';
	}
  else{
    $sql = "SELECT * FROM users WHERE uidUsers=?;";
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
  		mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
  		mysqli_stmt_execute($stmt);
  		$result = mysqli_stmt_get_result($stmt);
  		if ($row = mysqli_fetch_assoc($result)){
  			$pwdCheck = password_verify($cur_pwd, $row['pwdUsers']);
  			if($pwdCheck == false){
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      		<strong>Wrong password. </strong>Current password does not match the records.
      		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      		<span aria-hidden="true">&times;</span>
      		</button>
      		</div>';
  			}
  			else if($pwdCheck == true){
          $sql = "UPDATE users SET pwdUsers = ? WHERE uidUsers = ?";
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

          $hashedPwd = password_hash($new_pwd, PASSWORD_DEFAULT);
          mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $_SESSION['username']);
          mysqli_stmt_execute($stmt);
          echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Success! </strong>Passowrd changed successfully.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick="wind_ref()">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>';

          }
  }
}
}
}
}



?>
