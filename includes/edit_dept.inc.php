<?php
if (!empty($_POST['dept_id']) && !empty($_POST['dept_desc']) && !empty($_POST['dept_idn'])){
  require 'dbconn.inc.php';
  $dept_id = $_POST['dept_id'];
  $dept_desc = $_POST['dept_desc'];
  $dept_idn = $_POST['dept_idn'];



  $sql = "SELECT *
          FROM department
          WHERE dept_num <> $dept_id
          AND department_id = '$dept_idn'
          OR dept_num <> $dept_id
          AND department_desc = '$dept_desc'
          ;";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0){
    header("Location:../departments_admin.php?error=dept_exists");
    exit();
  }
  else{

    $sql = "UPDATE department
            SET department_id = ?,
            department_desc = ?
            WHERE dept_num = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location:../departments_admin.php?error=sqlerror");
      exit();
    }
    else{
      mysqli_stmt_bind_param($stmt, "sss", $dept_idn, $dept_desc, $dept_id);
      mysqli_stmt_execute($stmt);
      header("Location:../departments_admin.php?success=editdept");
      exit();
    }

  }


}
else{
  header("Location:../departments_admin.php?error=emptyfields?");
  exit();
}

?>
