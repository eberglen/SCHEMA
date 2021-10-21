<?php
if ($_POST['sched_num_similar'] != '' && isset($_POST['cur_cn']) && isset($_POST['cur_yls'])){
  session_start();
  $dept_id = $_SESSION['dept_id'];
  require 'dbconn.inc.php';
  $cur_cn = $_POST['cur_cn'];
  $cur_yls = $_POST['cur_yls'];
  $sched_num_similar = $_POST['sched_num_similar'];

  $sql = "SELECT curriculum_num, year_level_section
          FROM schedule
          WHERE schedule_num = $sched_num_similar
          ;";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
    $similar_cn = $row['curriculum_num'];
    $similar_yls = $row['year_level_section'];
  }

  //GET VALUES OF SIMILAR SCHEDULE
  $sql = "SELECT schedule.schedule_num, subjects.subject_code, s_start_at, s_end_at, employee_num, room_num, GROUP_CONCAT(day_code) as day_code
          FROM schedule, subjects, schedule_days
          WHERE schedule.subject_code = subjects.subject_code
          AND schedule.schedule_num = schedule_days.schedule_num
          AND subjects.department_id = '$dept_id'
          AND curriculum_num = $similar_cn
          AND year_level_section = '$similar_yls'
          GROUP BY schedule.schedule_num
          ;";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
    $sim_schednum = $row['schedule_num'];
    $sim_subject_code = $row['subject_code'];
    $sim_start = $row['s_start_at'];
    $sim_end = $row['s_end_at'];
    $sim_empnum = $row['employee_num'];
    $sim_roomnum = $row['room_num'];
    $str_days = $row['day_code'];
    //GET SCHEDULE_DAYS
    $sim_days = '';
    $sql2 = "SELECT *
            FROM schedule_days
            WHERE schedule_num = $sim_schednum
            ;";
    $result2 = mysqli_query($conn, $sql2);
    if ($result2){
      $days = [];
      $sim_days = "(";
      while($row2 = mysqli_fetch_assoc($result2)) {
        $day = $row2['day_code'];
        $sim_days .= "'".$day."',";
        array_push($days, $day);
      }
      $sim_days = substr($sim_days, 0, -1);
      $sim_days .= ")";
    }
    //GET VALUES OF CURRENT SCHEDULE
      $sql3 = "SELECT schedule_num
              FROM schedule
              WHERE year_level_section = '$cur_yls'
              AND curriculum_num = $cur_cn
              AND subject_code = '$sim_subject_code'
              ;";
      $result3 = mysqli_query($conn, $sql3);
      if ($result3){
        while($row3 = mysqli_fetch_assoc($result3)) {
          $cur_schednum = $row3['schedule_num'];
          //PROFESSOR CONFLICT
          $sql_professor_conflict = "SELECT first_name, last_name, year_level_section, subject_code
                                from schedule, schedule_days, employee
                                where schedule.schedule_num=schedule_days.schedule_num
                                AND schedule.schedule_num != $cur_schednum
                                AND employee.employee_num=schedule.employee_num
                                AND schedule.employee_num='$sim_empnum'
                                AND day_code IN $sim_days
                                AND curriculum_num = $cur_cn
                                AND ((s_start_at >= '$sim_start' AND s_start_at < '$sim_end')
                                OR (s_end_at > '$sim_start' AND s_end_at <= '$sim_end'))
                                GROUP BY year_level_section;";
          $result_professor_conflict = mysqli_query($conn, $sql_professor_conflict);
          //ROOM CONFLICT
          $sql_room_conflict = "SELECT year_level_section, subject_code
                                from schedule, schedule_days
                                where schedule.schedule_num=schedule_days.schedule_num
                                AND schedule.schedule_num != $cur_schednum
                                AND room_num = '$sim_roomnum'
                                AND day_code IN $sim_days
                                AND curriculum_num = $cur_cn
                                AND ((s_start_at >= '$sim_start' AND s_start_at < '$sim_end')
                                OR (s_end_at > '$sim_start' AND s_end_at <= '$sim_end'))
                                GROUP BY year_level_section;";
          $result_room_conflict = mysqli_query($conn, $sql_room_conflict);
          //ROOM UNAVAILABLE
          $sql_room_unavailable = "SELECT room_num from room where NOT EXISTS
                                  (SELECT room_num
                                  from room
                                  where room_num='$sim_roomnum'
                                  AND '$sim_start' >= avail_start AND '$sim_end' <= avail_end
                                  );";
          if ($sim_roomnum == null){
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
                                  AND schedule_days.day_code IN $sim_days
                                  AND schedule.schedule_num != $cur_schednum
                                  AND year_level_section='$cur_yls'
                                  AND curriculum_num = $cur_cn
                                  AND ((s_start_at >= '$sim_start' AND s_start_at < '$sim_end')
                                  OR (s_end_at > '$sim_start' AND s_end_at <= '$sim_end')
								  OR (s_start_at <= '$sim_start' AND s_end_at >= '$sim_end'))
                                  GROUP BY subject_code;";
          $result_class_conflict = mysqli_query($conn, $sql_class_conflict);

          if (mysqli_num_rows($result_room_conflict) > 0) {
            while($row_room_conflict = mysqli_fetch_assoc($result_room_conflict)) {
              $sim_roomnum = '';
            }
          }

          if (mysqli_num_rows($result_professor_conflict) > 0) {
            while($row_professor_conflict = mysqli_fetch_assoc($result_professor_conflict)) {
              $sim_empnum = '';
            }
          }
          if ($room_unavailable > 0){
              $sim_roomnum = '';
          }


          if (mysqli_num_rows($result_class_conflict) > 0) {
            while($row_class_conflict = mysqli_fetch_assoc($result_class_conflict)) {
              $sim_start = '';
              $sim_end = '';
              $sim_empnum = '';
              $sim_roomnum = '';
            }
          }
        }
      $sql0 = '';
      $result0 = '';
      $sql0 = "UPDATE schedule
              SET s_start_at = '$sim_start',
              s_end_at = '$sim_end',
              employee_num = $sim_empnum,
              room_num = '$sim_roomnum'
              WHERE schedule_num = $cur_schednum
              ;";
      $result0 = mysqli_query($conn,$sql0);

      $sql0 = "DELETE
                FROM schedule_days
                WHERE schedule_num = $cur_schednum;";
      $result0 = mysqli_query($conn,$sql0);

      $values = '';
      foreach ($days as $day){
        $values .= "(".$cur_schednum.",'".$day."'),";
      }
      $values = substr($values, 0, -1);
      $sql0 = "INSERT INTO schedule_days
                VALUES
                $values;";
      $result0 = mysqli_query($conn,$sql0);

    }//END OF SQL3
  }
  header("Location:../update_schedule.php?SUCCESS=USEPREVSCHED&cn=".$cur_cn."&yls=".$cur_yls."");
  exit();
}
else{
  $cur_cn = $_POST['cur_cn'];
  $cur_yls = $_POST['cur_yls'];
  header("Location:../update_schedule.php?error=emptyfields&cn=".$cur_cn."&yls=".$cur_yls."");
  exit();
}
?>
