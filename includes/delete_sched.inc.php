<?php
if(isset($_POST['sched_num'])){
  include 'dbconn.inc.php';
  $sched_num = $_POST['sched_num'];

  $sql = "DELETE
          FROM schedule
          WHERE schedule_num = $sched_num;
          DELETE
          FROM schedule_days
          WHERE schedule_num = $sched_num;";
  $result = mysqli_multi_query($conn, $sql);
  if ($result){
    header("Location:../sections_admin.php?success=delsubj");
    exit();
  }
  else{
    header("Location:../sections_admin.php?error=sqlerror");
    exit();
  }

}
?>
