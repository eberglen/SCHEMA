<?php
session_start();
include 'dbconn.inc.php';

echo '<option selected disabled value="">Select Room...</option>';
if ((isset($_POST['day'])) && (isset($_POST['sched_num'])) && (isset($_POST['start'])) && (isset($_POST['end']))){
  $day = $_POST['day'];
  $sched_num = $_POST['sched_num'];
  $start = $_POST['start'];
  $end = $_POST['end'];
  $days = '';
  if (isset($day[0]))
    $days .= $day[0].',';
  if (isset($day[1]))
    $days .= $day[1].',';
  if (isset($day[2]))
    $days .= $day[2].',';
  if (isset($day[3]))
    $days .= $day[3].',';
  if (isset($day[4]))
    $days .= $day[4].',';
  if (isset($day[5]))
    $days .= $day[5].',';
  if ($days != ''){
    $days = substr($days, 0, -1);
    $days = '('.$days.')';
  }

  $query="SELECT curriculum_num
          FROM schedule
          where schedule_num=".$sched_num.";";
  $rowname='curriculum_num';
  $cn = $mysqlidb->new_sql_query($query,$rowname);

  $query="SELECT room_num, schedule.schedule_num
          FROM schedule, schedule_days
          where schedule.schedule_num = schedule_days.schedule_num
          AND curriculum_num = $cn
          and day_code IN $days
          AND ((s_start_at >= '$start' AND s_start_at < '$end')
          OR (s_end_at > '$start' AND s_end_at <= '$end'))
          GROUP BY schedule_num;";
  #$rowname='room_num';
  $taken = '';
  $ctr = 0;
  $result_schednum = '';
  #$taken = $mysqlidb->new_sql_query($query,$rowname);
  $result = mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)){
    $taken .= $row['room_num'];
    $ctr = $ctr + 1;
    $result_schednum .= $row['schedule_num'];
  }
  if ($ctr == 1){
    if ($result_schednum == $sched_num){
      $taken = '';
    }
  }

  $query="SELECT room.room_num, GROUP_CONCAT(day_code) as day_code
          from room, room_days
          where room.room_num=room_days.room_num
          AND '$start' >= avail_start
          AND '$start' < avail_end
          AND '$end' <= avail_end
          AND day_code IN $days
          GROUP BY room.room_num
          ORDER BY room.room_num;";
  $result=mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)){
    if (strpos($taken, $row['room_num']) !== false){
      #echo '<option disabled>'.$row['room_num'].' (Occupied)</option>';
    }
    else {
      echo '<option value = "'.$row['room_num'].'">'.$row['room_num'].'</option>';
    }
  }
}
?>
