<?php
if (isset($_POST['cur_num']) && isset($_POST['yls'])){
  include 'dbconn.inc.php';
  $cn = $_POST['cur_num'];
  $yls = $_POST['yls'];

  $sql = "INSERT INTO approved (year_level_section, curriculum_num)
          VALUES
          ('$yls', $cn)
          ;";
  $result = mysqli_query($conn, $sql);
  if ($result){
    header("Location:../home_dean.php?success=approved");
    exit();
  }
  else{
    header("Location:../home_dean.php?error=sqlerror");
    exit();
  }
}

?>
