<?php
if(isset($_POST['room_num'])){
  include 'dbconn.inc.php';
  $room_num = $_POST['room_num'];

  $sql = "DELETE
          FROM room
          WHERE room_num = '$room_num';
          DELETE
          FROM room_days
          WHERE room_num = '$room_num';";
  $result = mysqli_multi_query($conn, $sql);
  if ($result){
    header("Location:../rooms_admin.php?success=delroom");
    exit();
  }
  else{
    header("Location:../rooms_admin.php?error=sqlerror");
    exit();
  }
}
?>
