<?php
if (isset($_POST['curriculum_num'])){
  include 'dbconn.inc.php';
  $cn = $_POST['curriculum_num'];
  echo '<button class="btn btn-info" id="btnExport" onclick="fnExcelReport();"><i class="fas fa-file-export">Export</i></button>';
  //MONDAY
  $sql = "SELECT s_start_at, academic_year, semester, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, s_start_at
          FROM schedule, schedule_days, curriculum
          WHERE schedule.schedule_num = schedule_days.schedule_num
          AND schedule.curriculum_num = curriculum.curriculum_num
          AND schedule.curriculum_num = $cn
          AND day_code = 'M'
          GROUP BY s_start_at
          ;";
  $result = mysqli_query($conn, $sql);
  echo '<table class="table table-bordered table-responsive-md" id="exportTable">';
  while($row = mysqli_fetch_assoc($result)) {
    $d_start = $row['s_start_ats'];
    $d_end = $row['s_end_at'];
    $ay = $row['academic_year'];
    $sem = $row['semester'];
    echo '


        <tr>
          <th scope="col" colspan="5">'.$sem.' AY: '.$ay.'</th>
        </tr>
        <tr>
          <th scope="col" colspan="5">Monday</th>
        </tr>
        <tr>
          <th scope="col" colspan="5">'.$d_start.' - '.$d_end.'</th>
        </tr>
        <tr>
          <th scope="col">Section</th>
          <th scope="col">Subject</th>
          <th scope="col">Room</th>
          <th scope="col">Professor</th>
          <th scope="col">Remarks</th>
        </tr>


    ';
    $start = $row['s_start_at'];
    $sql2 = "SELECT year_level_section, subject_code, room_num, schedule.employee_num, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_at, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, first_name, middle_name, last_name, schedule.schedule_num as schedule_num
            FROM schedule, schedule_days, employee
            WHERE schedule.schedule_num = schedule_days.schedule_num
            AND schedule.employee_num = employee.employee_num
            AND curriculum_num = $cn
            AND day_code = 'M'
            AND s_start_at = '$start'
            GROUP BY schedule.schedule_num
            ;";
    $result2 = mysqli_query($conn, $sql2);
    while($row2 = mysqli_fetch_assoc($result2)){
      $sched_num = $row2['schedule_num'];
      $sql3 = "SELECT GROUP_CONCAT(day_code) as day
               FROM schedule_days
               WHERE schedule_num = $sched_num;";
       $result3 = mysqli_query($conn, $sql3);
       while($row3 = mysqli_fetch_assoc($result3)){
         $day = $row3['day'];
       }
      $yls = $row2['year_level_section'];
      $subj_code = $row2['subject_code'];
      $room_num = $row2['room_num'];
      $prof = $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];
      $t_start = $row2['s_start_at'];
      $t_end = $row2['s_end_at'];


      echo '
      <tr>
        <th scope="row">'.$yls.'</th>
        <td>'.$subj_code.'</td>
        <td>'.$room_num.'</td>
        <td>'.$prof.'</td>
        <td>'.$t_start.' - '.$t_end.' ('.$day.')</td>
      </tr>
      ';

    }
    echo '

    ';  }
  //END MONDAY
  //TUESDAY
  $sql = "SELECT s_start_at, academic_year, semester, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, s_start_at
          FROM schedule, schedule_days, curriculum
          WHERE schedule.schedule_num = schedule_days.schedule_num
          AND schedule.curriculum_num = curriculum.curriculum_num
          AND schedule.curriculum_num = $cn
          AND day_code = 'T'
          GROUP BY s_start_at
          ;";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
    $d_start = $row['s_start_ats'];
    $d_end = $row['s_end_at'];
    $ay = $row['academic_year'];
    $sem = $row['semester'];
    echo '

        <tr>
          <th scope="col" colspan="5">'.$sem.' AY: '.$ay.'</th>
        </tr>
        <tr>
          <th scope="col" colspan="5">Tuesday</th>
        </tr>
        <tr>
          <th scope="col" colspan="5">'.$d_start.' - '.$d_end.'</th>
        </tr>
        <tr>
          <th scope="col">Section</th>
          <th scope="col">Subject</th>
          <th scope="col">Room</th>
          <th scope="col">Professor</th>
          <th scope="col">Remarks</th>
        </tr>


    ';
    $start = $row['s_start_at'];
    $sql2 = "SELECT year_level_section, subject_code, room_num, schedule.employee_num, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_at, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, first_name, middle_name, last_name, schedule.schedule_num as schedule_num
            FROM schedule, schedule_days, employee
            WHERE schedule.schedule_num = schedule_days.schedule_num
            AND schedule.employee_num = employee.employee_num
            AND curriculum_num = $cn
            AND day_code = 'T'
            AND s_start_at = '$start'
            GROUP BY schedule.schedule_num
            ;";
    $result2 = mysqli_query($conn, $sql2);
    while($row2 = mysqli_fetch_assoc($result2)){
      $sched_num = $row2['schedule_num'];
      $sql3 = "SELECT GROUP_CONCAT(day_code) as day
               FROM schedule_days
               WHERE schedule_num = $sched_num;";
       $result3 = mysqli_query($conn, $sql3);
       while($row3 = mysqli_fetch_assoc($result3)){
         $day = $row3['day'];
       }
      $yls = $row2['year_level_section'];
      $subj_code = $row2['subject_code'];
      $room_num = $row2['room_num'];
      $prof = $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];
      $t_start = $row2['s_start_at'];
      $t_end = $row2['s_end_at'];


      echo '
      <tr>
        <th scope="row">'.$yls.'</th>
        <td>'.$subj_code.'</td>
        <td>'.$room_num.'</td>
        <td>'.$prof.'</td>
        <td>'.$t_start.' - '.$t_end.' ('.$day.')</td>
      </tr>
      ';

    }
    echo '

    ';
  }
  //END TUESDAY
  //WEDNESDAY
  $sql = "SELECT s_start_at, academic_year, semester, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, s_start_at
          FROM schedule, schedule_days, curriculum
          WHERE schedule.schedule_num = schedule_days.schedule_num
          AND schedule.curriculum_num = curriculum.curriculum_num
          AND schedule.curriculum_num = $cn
          AND day_code = 'W'
          GROUP BY s_start_at
          ;";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
    $d_start = $row['s_start_ats'];
    $d_end = $row['s_end_at'];
    $ay = $row['academic_year'];
    $sem = $row['semester'];
    echo '

        <tr>
          <th scope="col" colspan="5">'.$sem.' AY: '.$ay.'</th>
        </tr>
        <tr>
          <th scope="col" colspan="5">Wednesday</th>
        </tr>
        <tr>
          <th scope="col" colspan="5">'.$d_start.' - '.$d_end.'</th>
        </tr>
        <tr>
          <th scope="col">Section</th>
          <th scope="col">Subject</th>
          <th scope="col">Room</th>
          <th scope="col">Professor</th>
          <th scope="col">Remarks</th>
        </tr>


    ';
    $start = $row['s_start_at'];
    $sql2 = "SELECT year_level_section, subject_code, room_num, schedule.employee_num, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_at, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, first_name, middle_name, last_name, schedule.schedule_num as schedule_num
            FROM schedule, schedule_days, employee
            WHERE schedule.schedule_num = schedule_days.schedule_num
            AND schedule.employee_num = employee.employee_num
            AND curriculum_num = $cn
            AND day_code = 'W'
            AND s_start_at = '$start'
            GROUP BY schedule.schedule_num
            ;";
    $result2 = mysqli_query($conn, $sql2);
    while($row2 = mysqli_fetch_assoc($result2)){
      $sched_num = $row2['schedule_num'];
      $sql3 = "SELECT GROUP_CONCAT(day_code) as day
               FROM schedule_days
               WHERE schedule_num = $sched_num;";
       $result3 = mysqli_query($conn, $sql3);
       while($row3 = mysqli_fetch_assoc($result3)){
         $day = $row3['day'];
       }
      $yls = $row2['year_level_section'];
      $subj_code = $row2['subject_code'];
      $room_num = $row2['room_num'];
      $prof = $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];
      $t_start = $row2['s_start_at'];
      $t_end = $row2['s_end_at'];


      echo '
      <tr>
        <th scope="row">'.$yls.'</th>
        <td>'.$subj_code.'</td>
        <td>'.$room_num.'</td>
        <td>'.$prof.'</td>
        <td>'.$t_start.' - '.$t_end.' ('.$day.')</td>
      </tr>
      ';

    }
    echo '

    ';
  }
  //END WEDNESDAY
  //THURSDAY
  $sql = "SELECT s_start_at, academic_year, semester, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, s_start_at
          FROM schedule, schedule_days, curriculum
          WHERE schedule.schedule_num = schedule_days.schedule_num
          AND schedule.curriculum_num = curriculum.curriculum_num
          AND schedule.curriculum_num = $cn
          AND day_code = 'TH'
          GROUP BY s_start_at
          ;";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
    $d_start = $row['s_start_ats'];
    $d_end = $row['s_end_at'];
    $ay = $row['academic_year'];
    $sem = $row['semester'];
    echo '

        <tr>
          <th scope="col" colspan="5">'.$sem.' AY: '.$ay.'</th>
        </tr>
        <tr>
          <th scope="col" colspan="5">Thursday</th>
        </tr>
        <tr>
          <th scope="col" colspan="5">'.$d_start.' - '.$d_end.'</th>
        </tr>
        <tr>
          <th scope="col">Section</th>
          <th scope="col">Subject</th>
          <th scope="col">Room</th>
          <th scope="col">Professor</th>
          <th scope="col">Remarks</th>
        </tr>


    ';
    $start = $row['s_start_at'];
    $sql2 = "SELECT year_level_section, subject_code, room_num, schedule.employee_num, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_at, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, first_name, middle_name, last_name, schedule.schedule_num as schedule_num
            FROM schedule, schedule_days, employee
            WHERE schedule.schedule_num = schedule_days.schedule_num
            AND schedule.employee_num = employee.employee_num
            AND curriculum_num = $cn
            AND day_code = 'TH'
            AND s_start_at = '$start'
            GROUP BY schedule.schedule_num
            ;";
    $result2 = mysqli_query($conn, $sql2);
    while($row2 = mysqli_fetch_assoc($result2)){
      $sched_num = $row2['schedule_num'];
      $sql3 = "SELECT GROUP_CONCAT(day_code) as day
               FROM schedule_days
               WHERE schedule_num = $sched_num;";
       $result3 = mysqli_query($conn, $sql3);
       while($row3 = mysqli_fetch_assoc($result3)){
         $day = $row3['day'];
       }
      $yls = $row2['year_level_section'];
      $subj_code = $row2['subject_code'];
      $room_num = $row2['room_num'];
      $prof = $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];
      $t_start = $row2['s_start_at'];
      $t_end = $row2['s_end_at'];


      echo '
      <tr>
        <th scope="row">'.$yls.'</th>
        <td>'.$subj_code.'</td>
        <td>'.$room_num.'</td>
        <td>'.$prof.'</td>
        <td>'.$t_start.' - '.$t_end.' ('.$day.')</td>
      </tr>
      ';

    }
    echo '

    ';
  }
  //END THURSDAY
  //FRIDAY
  $sql = "SELECT s_start_at, academic_year, semester, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, s_start_at
          FROM schedule, schedule_days, curriculum
          WHERE schedule.schedule_num = schedule_days.schedule_num
          AND schedule.curriculum_num = curriculum.curriculum_num
          AND schedule.curriculum_num = $cn
          AND day_code = 'F'
          GROUP BY s_start_at
          ;";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
    $d_start = $row['s_start_ats'];
    $d_end = $row['s_end_at'];
    $ay = $row['academic_year'];
    $sem = $row['semester'];
    echo '

        <tr>
          <th scope="col" colspan="5">'.$sem.' AY: '.$ay.'</th>
        </tr>
        <tr>
          <th scope="col" colspan="5">Friday</th>
        </tr>
        <tr>
          <th scope="col" colspan="5">'.$d_start.' - '.$d_end.'</th>
        </tr>
        <tr>
          <th scope="col">Section</th>
          <th scope="col">Subject</th>
          <th scope="col">Room</th>
          <th scope="col">Professor</th>
          <th scope="col">Remarks</th>
        </tr>


    ';
    $start = $row['s_start_at'];
    $sql2 = "SELECT year_level_section, subject_code, room_num, schedule.employee_num, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_at, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, first_name, middle_name, last_name, schedule.schedule_num as schedule_num
            FROM schedule, schedule_days, employee
            WHERE schedule.schedule_num = schedule_days.schedule_num
            AND schedule.employee_num = employee.employee_num
            AND curriculum_num = $cn
            AND day_code = 'F'
            AND s_start_at = '$start'
            GROUP BY schedule.schedule_num
            ;";
    $result2 = mysqli_query($conn, $sql2);
    while($row2 = mysqli_fetch_assoc($result2)){
      $sched_num = $row2['schedule_num'];
      $sql3 = "SELECT GROUP_CONCAT(day_code) as day
               FROM schedule_days
               WHERE schedule_num = $sched_num;";
       $result3 = mysqli_query($conn, $sql3);
       while($row3 = mysqli_fetch_assoc($result3)){
         $day = $row3['day'];
       }
      $yls = $row2['year_level_section'];
      $subj_code = $row2['subject_code'];
      $room_num = $row2['room_num'];
      $prof = $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];
      $t_start = $row2['s_start_at'];
      $t_end = $row2['s_end_at'];


      echo '
      <tr>
        <th scope="row">'.$yls.'</th>
        <td>'.$subj_code.'</td>
        <td>'.$room_num.'</td>
        <td>'.$prof.'</td>
        <td>'.$t_start.' - '.$t_end.' ('.$day.')</td>
      </tr>
      ';

    }
    echo '

    ';
  }
  //END FRIDAY
  //SATURDAY
  $sql = "SELECT s_start_at, academic_year, semester, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, s_start_at
          FROM schedule, schedule_days, curriculum
          WHERE schedule.schedule_num = schedule_days.schedule_num
          AND schedule.curriculum_num = curriculum.curriculum_num
          AND schedule.curriculum_num = $cn
          AND day_code = 'S'
          GROUP BY s_start_at
          ;";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
    $d_start = $row['s_start_ats'];
    $d_end = $row['s_end_at'];
    $ay = $row['academic_year'];
    $sem = $row['semester'];
    echo '

        <tr>
          <th scope="col" colspan="5">'.$sem.' AY: '.$ay.'</th>
        </tr>
        <tr>
          <th scope="col" colspan="5">Saturday</th>
        </tr>
        <tr>
          <th scope="col" colspan="5">'.$d_start.' - '.$d_end.'</th>
        </tr>
        <tr>
          <th scope="col">Section</th>
          <th scope="col">Subject</th>
          <th scope="col">Room</th>
          <th scope="col">Professor</th>
          <th scope="col">Remarks</th>
        </tr>


    ';
    $start = $row['s_start_at'];
    $sql2 = "SELECT year_level_section, subject_code, room_num, schedule.employee_num, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_at, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, first_name, middle_name, last_name, schedule.schedule_num as schedule_num
            FROM schedule, schedule_days, employee
            WHERE schedule.schedule_num = schedule_days.schedule_num
            AND schedule.employee_num = employee.employee_num
            AND curriculum_num = $cn
            AND day_code = 'S'
            AND s_start_at = '$start'
            GROUP BY schedule.schedule_num
            ;";
    $result2 = mysqli_query($conn, $sql2);
    while($row2 = mysqli_fetch_assoc($result2)){
      $sched_num = $row2['schedule_num'];
      $sql3 = "SELECT GROUP_CONCAT(day_code) as day
               FROM schedule_days
               WHERE schedule_num = $sched_num;";
       $result3 = mysqli_query($conn, $sql3);
       while($row3 = mysqli_fetch_assoc($result3)){
         $day = $row3['day'];
       }
      $yls = $row2['year_level_section'];
      $subj_code = $row2['subject_code'];
      $room_num = $row2['room_num'];
      $prof = $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];
      $t_start = $row2['s_start_at'];
      $t_end = $row2['s_end_at'];


      echo '
      <tr>
        <th scope="row">'.$yls.'</th>
        <td>'.$subj_code.'</td>
        <td>'.$room_num.'</td>
        <td>'.$prof.'</td>
        <td>'.$t_start.' - '.$t_end.' ('.$day.')</td>
      </tr>
      ';

    }

  }
  //END SATURDAY
  echo '

</table>
  ';
}

?>
