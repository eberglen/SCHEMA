<?php
if(!empty($_POST['room_num']) && $_POST['type'] != '' && !empty($_POST['a_start']) && !empty($_POST['a_end']) && isset($_POST['day'])){
  include 'dbconn.inc.php';
  $room_num = $_POST['room_num'];
  $type = $_POST['type'];
  $a_start = $_POST['a_start'];
  $a_end = $_POST['a_end'];
  $day = $_POST['day'];
  $days = [];
  if (isset($day[0]))
    array_push($days,$day[0]);
  if (isset($day[1]))
    array_push($days,$day[1]);
  if (isset($day[2]))
    array_push($days,$day[2]);
  if (isset($day[3]))
    array_push($days,$day[3]);
  if (isset($day[4]))
    array_push($days,$day[4]);
  if (isset($day[5]))
    array_push($days,$day[5]);


  $sql = "SELECT *
          FROM room
          WHERE room_num = '$room_num'
          ;";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Warning: </strong>Room number already existing.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>';
  }
  else{
    $values = '';
    foreach ($days as $day){
      $values .= "('".$room_num."',".$day."),";
    }
    $values = substr($values, 0, -1);
    $query = "INSERT INTO room_days
              VALUES
              $values;";
    $sql = "INSERT INTO room (room_num, avail_start, avail_end, room_class)
            VALUES ('$room_num','$a_start','$a_end','$type');".$query;
    $result = mysqli_multi_query($conn, $sql);
    if($result){
      echo '<div class="alert alert-success alert-dismissible fade show animated fadeIn" role="alert">
      <strong>Success! </strong> Room added successfully.'.$sql.'
      <button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick="wind_ref()">
      <span aria-hidden="true">&times;</span>
      </button>
      </div>';
    }
  }
}
else{
  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Warning: </strong>Empty fields.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  </div>';
}


?>
