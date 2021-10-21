<?php
include 'dbconn.inc.php';
$idUsers = $_POST['idUsers'];
$password = 'sbu@2020';

$sql = "UPDATE users SET pwdUsers = ? WHERE idUsers = ?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  header("Location:../departments_admin.php?error=sqlerror");
  exit();
}
else{

$hashedPwd = password_hash($password, PASSWORD_DEFAULT);
mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $idUsers);
mysqli_stmt_execute($stmt);
header("Location:../departments_admin.php?success=pwdreset");
exit();

}
?>
