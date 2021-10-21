<?php
if(isset($_POST['subj_code'])){
  include 'dbconn.inc.php';
  $subj_code = $_POST['subj_code'];

  $sql = "DELETE FROM subjects WHERE subject_code = ?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location:../subjects_admin.php?error=sqlerror");
    exit();
  }
  else{

  mysqli_stmt_bind_param($stmt, "s", $subj_code);
  mysqli_stmt_execute($stmt);
  header("Location:../subjects_admin.php?success=delsubj");
  exit();

  }
}
?>
