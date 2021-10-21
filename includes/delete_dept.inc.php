<?php
if(isset($_POST['dept_id'])){
  include 'dbconn.inc.php';
  $dept_id = $_POST['dept_id'];

  $sql = "DELETE
          FROM department
          WHERE department_id = '$dept_id';
          DELETE
          FROM subjects
          WHERE department_id = '$dept_id';";
  $result = mysqli_multi_query($conn, $sql);
  if ($result){
    header("Location:../departments_admin.php?success=deldept");
    exit();
  }
  else{
    header("Location:../departments_admin.php?error=sqlerror");
    exit();
  }

}
?>
