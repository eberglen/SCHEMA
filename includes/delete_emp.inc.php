<?php
if(isset($_POST['emp_num'])){
  include 'dbconn.inc.php';
  $emp_num = $_POST['emp_num'];

  $sql = "DELETE
          FROM employee
          WHERE employee_num = '$emp_num';
          DELETE
          FROM employee_departments
          WHERE employee_num = '$emp_num';";
  $result = mysqli_multi_query($conn, $sql);
  if ($result){
    header("Location:../faculty_admin.php?success=delfaculty");
    exit();
  }
  else{
    header("Location:../faculty_admin.php?error=sqlerror");
    exit();
  }
}
?>
