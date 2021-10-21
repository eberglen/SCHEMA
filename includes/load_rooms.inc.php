<?php
include 'dbconn.inc.php';
$sched_days = '';


if(!empty($_POST["schedule_num"])){
  $query = "SELECT *
            FROM schedule
            WHERE schedule_num=".$_POST["schedule_num"].";";
  #$result = $db->query($query);
  $result = mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)){
    $sql = "SELECT day_code
            FROM schedule_days
            WHERE schedule_num=".$_POST['schedule_num'].";";
    $sched_days_result = mysqli_query($conn, $sql);
    while($row_d = mysqli_fetch_assoc($sched_days_result)){
      $sched_days .= '"'.$row_d['day_code'].'",';
    }
    $sched_days = substr($sched_days, 0, -1);
    $sched_days = '('.$sched_days.')';
    $start = $row['s_start_at'];
    $end = $row['s_end_at'];
    $cn = $row['curriculum_num'];
      if (isset($row['room_num'])){
        echo '<option disabled selected value="'.$row['room_num'].'">'.$row['room_num'].'</option>';
        echo '<option disabled value="">Select Room...</option>';
      }
      else{
        echo '<option selected disabled value="">Select Room...</option>';
      }
  }



  $query="SELECT room_num
          FROM schedule, schedule_days
          where schedule.schedule_num = schedule_days.schedule_num
          AND curriculum_num=$cn
          and day_code IN $sched_days
          AND ((s_start_at >= '$start' AND s_start_at < '$end')
          OR (s_end_at > '$start' AND s_end_at <= '$end'))GROUP BY room_num;";
  $rowname='room_num';
  $taken = $mysqlidb->new_sql_query($query,$rowname);

  $query="SELECT room.room_num, GROUP_CONCAT(day_code) as day_code
          from room, room_days
          where room.room_num=room_days.room_num
          AND '$start' >= avail_start
          AND '$start' < avail_end
          AND '$end' <= avail_end
          AND day_code IN $sched_days
          GROUP BY room.room_num;";
  $result=mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)){
    if (strpos($taken, $row['room_num']) !== false){
      #echo '<option disabled>'.$row['room_num'].' Occupied</option>';
    }
    else {
      echo '<option>'.$row['room_num'].'</option>';
    }
  }
}
#echo '<option value="">'.$taken.'</option>';
#if (isset($_POST['day']))
  #echo $_POST['day'];





?>
