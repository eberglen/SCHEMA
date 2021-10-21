<?php
include 'dbconn.inc.php';
if ((isset($_POST['day'])) && (isset($_POST['sched_num'])) && ($_POST['start'] != '') && ($_POST['end'] != '') && ($_POST['rooms'] != '') && ($_POST['profs'] != '')){
  $sched_num = $_POST['sched_num'];
  $start = $_POST['start'];
  $end = $_POST['end'];
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

  $daystr = '';
  if (isset($day[0]))
    $daystr .= $day[0].',';
  if (isset($day[1]))
    $daystr .= $day[1].',';
  if (isset($day[2]))
    $daystr .= $day[2].',';
  if (isset($day[3]))
    $daystr .= $day[3].',';
  if (isset($day[4]))
    $daystr .= $day[4].',';
  if (isset($day[5]))
    $daystr .= $day[5].',';
  if ($daystr != ''){
    $daystr = substr($daystr, 0, -1);
    $daystr = '('.$daystr.')';
  }

  if ($_POST['profs'] != ''){
    $prof_num = $_POST['profs'];
  }
  else{
    $prof_num = null;
  }
  if ($_POST['rooms'] != ''){
    $room_num = $_POST['rooms'];
  }
  else{
    $room_num = null;
  }

  $query="SELECT curriculum_num, year_level_section
          FROM schedule
          where schedule_num=".$sched_num.";";
  $result = mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)){
    $cn = $row['curriculum_num'];
    $yls = $row['year_level_section'];
  }


    //PROFESSOR CONFLICT
  $sql_professor_conflict = "SELECT first_name, last_name, year_level_section, subject_code
                        from schedule, schedule_days, employee
                        where schedule.schedule_num=schedule_days.schedule_num
                        AND schedule.schedule_num != $sched_num
                        AND employee.employee_num=schedule.employee_num
                        AND schedule.employee_num='$prof_num'
                        AND day_code IN $daystr
                        AND curriculum_num=$cn
                        AND ((s_start_at >= '$start' AND s_start_at < '$end')
                        OR (s_end_at > '$start' AND s_end_at <= '$end'))
                        GROUP BY year_level_section;";
  $result_professor_conflict = mysqli_query($conn, $sql_professor_conflict);
  //ROOM CONFLICT
  $sql_room_conflict = "SELECT year_level_section, subject_code
                        from schedule, schedule_days
                        where schedule.schedule_num=schedule_days.schedule_num
                        AND schedule.schedule_num != $sched_num
                        AND room_num='$room_num'
                        AND day_code IN $daystr
                        AND curriculum_num=$cn
                        AND ((s_start_at >= '$start' AND s_start_at < '$end')
                        OR (s_end_at > '$start' AND s_end_at <= '$end'))
                        GROUP BY year_level_section;";
  $result_room_conflict = mysqli_query($conn, $sql_room_conflict);


  //ROOM UNAVAILABLE
  $sql_room_unavailable = "SELECT room_num from room where NOT EXISTS
                          (SELECT room_num
                          from room
                          where room_num='$room_num'
                          AND '$start' >= avail_start AND '$end' <= avail_end
                          );";
  if ($room_num == null){
    $room_unavailable = 0;
  }
  else{
    $result_room_unavailable = mysqli_query($conn, $sql_room_unavailable);
    if (mysqli_num_rows($result_room_unavailable) > 0){
      $room_unavailable = 1;
    }
    else{
      $room_unavailable = 0;
    }
  }
  //SECTION ALREADY HAS A CLASS
  $sql_class_conflict = " SELECT subject_code, room_num
                          from schedule, schedule_days
                          WHERE schedule.schedule_num=schedule_days.schedule_num
                          AND schedule_days.day_code IN $daystr
                          AND schedule.schedule_num != $sched_num
                          AND year_level_section='$yls'
                          AND curriculum_num=$cn
                          AND ((s_start_at >= '$start' AND s_start_at < '$end')
                          OR (s_end_at > '$start' AND s_end_at <= '$end')
						  OR (s_start_at <= '$start' AND s_end_at >= '$end'))
                          GROUP BY subject_code;";
  $result_class_conflict = mysqli_query($conn, $sql_class_conflict);




  if($start==$end){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Warning: </strong>Start time and end time cannot be equal.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>';
  }
  if($start > $end){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Warning: </strong>Start time cannot be greater than End time.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>';
  }

  else if (mysqli_num_rows($result_room_conflict) > 0) {
    while($row_room_conflict = mysqli_fetch_assoc($result_room_conflict)) {
      $year_level_section_conflict=$row_room_conflict["year_level_section"];
      $subject_code_conflict=$row_room_conflict["subject_code"];
      echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Room Conflict</strong> Room selected is already occupied by: '.$year_level_section_conflict.' '.$subject_code_conflict.'
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
      </div>';
    }
  }

  else if (mysqli_num_rows($result_professor_conflict) > 0) {
    while($row_professor_conflict = mysqli_fetch_assoc($result_professor_conflict)) {
      $fn_conflict=$row_professor_conflict["first_name"];
      $ln_conflict=$row_professor_conflict["last_name"];
      $year_level_section_conflict_p=$row_professor_conflict["year_level_section"];
      $subject_code_conflict_p=$row_professor_conflict["subject_code"];
      echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Professor Conflict: </strong>'.$fn_conflict.' '.$ln_conflict.' is already teaching: '.$year_level_section_conflict_p.'
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
      </div>';
    }
  }
  else if ($room_unavailable > 0){
      echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Room Unavailable: </strong>Room '.$room_num.'
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
      </div>';
    }


  else if (mysqli_num_rows($result_class_conflict) > 0) {
    while($row_class_conflict = mysqli_fetch_assoc($result_class_conflict)) {
      $sc_class_conflict=$row_class_conflict["subject_code"];
      $rn_class_conflict=$row_class_conflict["room_num"];
      echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Class Conflict</strong> The schedule you selected for '.$yls.' already has a class on '.$sc_class_conflict.' '.$rn_class_conflict.'
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
      </div>';
    }
  }
  else{
  if (($room_num == null) && ($prof_num == null)){
    $query = "UPDATE schedule
              SET s_start_at = '$start',
              s_end_at = '$end',
              employee_num = NULL,
              room_num = NULL
              WHERE schedule_num = $sched_num;";
    //$result = $mysqlidb->sql_insert_update($query);
  }
  else if ($room_num == null){
    $query = "UPDATE schedule
              SET s_start_at = '$start',
              s_end_at = '$end',
              employee_num = $prof_num,
              room_num = NULL
              WHERE schedule_num = $sched_num;";
    //$result = $mysqlidb->sql_insert_update($query);
  }
  else if ($prof_num == null){
    $query = "UPDATE schedule
              SET s_start_at = '$start',
              s_end_at = '$end',
              employee_num = NULL,
              room_num = '$room_num'
              WHERE schedule_num = $sched_num;";
    //$result = $mysqlidb->sql_insert_update($query);
  }
  else{
    $query = "UPDATE schedule
              SET s_start_at = '$start',
              s_end_at = '$end',
              employee_num = '$prof_num',
              room_num = '$room_num'
              WHERE schedule_num = $sched_num;";
    //$result = $mysqlidb->sql_insert_update($query);
  }


  $query .= "DELETE
            FROM schedule_days
            WHERE schedule_num = $sched_num;";
  //$del_result = $mysqlidb->sql_insert_update($query);

  #add in schedule_days
  $values = '';
  foreach ($days as $day){
    $values .= "(".$sched_num.",".$day."),";
  }
  $values = substr($values, 0, -1);
  $query .= "INSERT INTO schedule_days
            VALUES
            $values;";
  //$day_result = $mysqlidb->sql_insert_update($query);
  if (mysqli_multi_query($conn,$query)){
  //if (($result) && ($del_result) && ($day_result)) {
    echo '<div class="alert alert-success alert-dismissible fade show animated fadeIn" role="alert">
    <strong>Success! </strong> Records updated successfully.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick="wind_ref()">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>';
  } else {
    echo '<div class="alert alert-danger alert-dismissible fade show animated fadeIn" role="alert">
    <strong>Warning! </strong> SQL error.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>';
  }
}




}
else{
  echo '<div class="alert alert-warning alert-dismissible fade show animated fadeIn" role="alert">
  <strong>Warning: </strong>EMPTY FIELDS. Please fill out all fields.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  </div>';
}


?>
