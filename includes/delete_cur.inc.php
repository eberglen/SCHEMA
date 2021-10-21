<?php
if(isset($_POST['cur_num'])){
  include 'dbconn.inc.php';
  $cur_num = $_POST['cur_num'];

  $sql = "DELETE
          FROM curriculum
          WHERE curriculum_num = $cur_num;
          DELETE
          FROM schedule
          WHERE curriculum_num = $cur_num;";
  $result = mysqli_multi_query($conn, $sql);
  if ($result){
    header("Location:../sections_admin.php?success=delcur");
    exit();
  }
  else{
    header("Location:../sections_admin.php?error=sqlerror");
    exit();
  }

}
?>
