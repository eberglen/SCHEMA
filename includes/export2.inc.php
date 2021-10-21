<?php
if (isset($_POST['curriculum_num'])){
  require 'dbconn.inc.php';
  $cn = $_POST['curriculum_num'];
    //FIRST YEAR
    echo '<br><br><h2>1st Year</h2>
    ';
    $query = "SELECT year_level_section
              FROM schedule
              WHERE year_level_section LIKE '%1%'
              AND curriculum_num = $cn
              GROUP BY year_level_section;";#change department_id
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)){
        $yls = $row['year_level_section'];
        echo '
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">'.$yls.'</th>
              <th scope="col"><strong>Monday-Wednesday-Friday</strong></th>

        ';

        $query2 = "SELECT room_num, subject_code, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, GROUP_CONCAT(day_code ORDER BY day_code desc) as day, TIMESTAMPDIFF(MINUTE,s_start_at,s_end_at) as duration
                  FROM schedule, schedule_days
                  WHERE schedule.schedule_num = schedule_days.schedule_num
                  AND year_level_section = '$yls'
                  AND curriculum_num = $cn
                  AND day_code IN ('M', 'W', 'F')
                  GROUP BY subject_code
                  ORDER BY s_start_at
                  ;";#change department_id
        $end = new DateTime('00:00');
        $result2 = mysqli_query($conn, $query2);
        while($row2 = mysqli_fetch_assoc($result2)){
          $subj_code = $row2['subject_code'];
          $start = $row2['s_start_ats'];
          if ($start > $end){
            $start_time = new DateTime($start);
            $end_time = new DateTime($end);
            $interval = $start_time->diff($end_time);
            $minutes = $interval->h * 60 + $interval->i;
            $minutes = $minutes / 90;
            //echo '<th scope="col" colspan="'.$minutes.'">VACANT<br>'.$end.'-'.$start.'</th>';
          }
          $end = $row2['s_end_at'];
          $day = $row2['day'];
          $room_num = $row2['room_num'];
          $duration = $row2['duration'];
          $colspan = $duration / 90;
          if (strlen($day) == 5){
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.'<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }
          else{
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.' ('.$day.')<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }

        }
        echo '<th scope="col"><strong>Tuesday-Thursday</strong></th>';
        $query2 = "SELECT room_num, subject_code, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, GROUP_CONCAT(day_code ORDER BY day_code ASC) as day, TIMESTAMPDIFF(MINUTE,s_start_at,s_end_at) as duration
                  FROM schedule, schedule_days
                  WHERE schedule.schedule_num = schedule_days.schedule_num
                  AND year_level_section = '$yls'
                  AND curriculum_num = $cn
                  AND day_code IN ('T', 'TH')
                  GROUP BY subject_code
                  ORDER BY s_start_at
                  ;";#change department_id
        $end = new DateTime('00:00');
        $result2 = mysqli_query($conn, $query2);
        while($row2 = mysqli_fetch_assoc($result2)){
          $subj_code = $row2['subject_code'];
          $start = $row2['s_start_ats'];
          if ($start > $end){
            $start_time = new DateTime($start);
            $end_time = new DateTime($end);
            $interval = $end_time->diff($start_time);
            $minutes = $interval->h * 60 + $interval->i;
            //$minutes = $minutes / 90;
            //echo '<th scope="col" colspan="'.$minutes.'">VACANT<br>'.$end.'-'.$start.'</th>';
          }
          $end = $row2['s_end_at'];
          $day = $row2['day'];
          $room_num = $row2['room_num'];
          $duration = $row2['duration'];
          $colspan = $duration / 90;
          if (strlen($day) == 4){
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.'<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }
          else{
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.' ('.$day.')<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }

        }
        echo '<th scope="col"><strong>Saturday</strong></th>';
        $query2 = "SELECT room_num, subject_code, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, GROUP_CONCAT(day_code ORDER BY day_code ASC) as day, TIMESTAMPDIFF(MINUTE,s_start_at,s_end_at) as duration
                  FROM schedule, schedule_days
                  WHERE schedule.schedule_num = schedule_days.schedule_num
                  AND year_level_section = '$yls'
                  AND curriculum_num = $cn
                  AND day_code IN ('S')
                  GROUP BY subject_code
                  ORDER BY s_start_at
                  ;";#change department_id
        $end = new DateTime('00:00');
        $result2 = mysqli_query($conn, $query2);
        while($row2 = mysqli_fetch_assoc($result2)){
          $subj_code = $row2['subject_code'];
          $start = $row2['s_start_ats'];
          if ($start > $end){
            $start_time = new DateTime($start);
            $end_time = new DateTime($end);
            $interval = $end_time->diff($start_time);
            $minutes = $interval->h * 60 + $interval->i;
            //$minutes = $minutes / 90;
            //echo '<th scope="col" colspan="'.$minutes.'">VACANT<br>'.$end.'-'.$start.'</th>';
          }
          $end = $row2['s_end_at'];
          $day = $row2['day'];
          $room_num = $row2['room_num'];
          $duration = $row2['duration'];
          $colspan = $duration / 90;
          if (strlen($day) == 1){
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.'<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }
          else{
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.' ('.$day.')<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }

        }

        echo '
        </tr>
      </thead>
    </table>
        ';
    }
    //END FIRST YEAR
    //SECOND YEAR
    echo '<br><br><h2>2nd Year</h2>
    ';
    $query = "SELECT year_level_section
              FROM schedule
              WHERE year_level_section LIKE '%2%'
              AND curriculum_num = $cn
              GROUP BY year_level_section;";#change department_id
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)){
        $yls = $row['year_level_section'];
        echo '
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">'.$yls.'</th>
              <th scope="col"><strong>Monday-Wednesday-Friday</strong></th>

        ';

        $query2 = "SELECT room_num, subject_code, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, GROUP_CONCAT(day_code ORDER BY day_code desc) as day, TIMESTAMPDIFF(MINUTE,s_start_at,s_end_at) as duration
                  FROM schedule, schedule_days
                  WHERE schedule.schedule_num = schedule_days.schedule_num
                  AND year_level_section = '$yls'
                  AND curriculum_num = $cn
                  AND day_code IN ('M', 'W', 'F')
                  GROUP BY subject_code
                  ORDER BY s_start_at
                  ;";#change department_id
        $end = new DateTime('00:00');
        $result2 = mysqli_query($conn, $query2);
        while($row2 = mysqli_fetch_assoc($result2)){
          $subj_code = $row2['subject_code'];
          $start = $row2['s_start_ats'];
          if ($start > $end){
            $start_time = new DateTime($start);
            $end_time = new DateTime($end);
            $interval = $start_time->diff($end_time);
            $minutes = $interval->h * 60 + $interval->i;
            $minutes = $minutes / 90;
            //echo '<th scope="col" colspan="'.$minutes.'">VACANT<br>'.$end.'-'.$start.'</th>';
          }
          $end = $row2['s_end_at'];
          $day = $row2['day'];
          $room_num = $row2['room_num'];
          $duration = $row2['duration'];
          $colspan = $duration / 90;
          if (strlen($day) == 5){
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.'<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }
          else{
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.' ('.$day.')<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }

        }
        echo '<th scope="col"><strong>Tuesday-Thursday</strong></th>';
        $query2 = "SELECT room_num, subject_code, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, GROUP_CONCAT(day_code ORDER BY day_code ASC) as day, TIMESTAMPDIFF(MINUTE,s_start_at,s_end_at) as duration
                  FROM schedule, schedule_days
                  WHERE schedule.schedule_num = schedule_days.schedule_num
                  AND year_level_section = '$yls'
                  AND curriculum_num = $cn
                  AND day_code IN ('T', 'TH')
                  GROUP BY subject_code
                  ORDER BY s_start_at
                  ;";#change department_id
        $end = new DateTime('00:00');
        $result2 = mysqli_query($conn, $query2);
        while($row2 = mysqli_fetch_assoc($result2)){
          $subj_code = $row2['subject_code'];
          $start = $row2['s_start_ats'];
          if ($start > $end){
            $start_time = new DateTime($start);
            $end_time = new DateTime($end);
            $interval = $end_time->diff($start_time);
            $minutes = $interval->h * 60 + $interval->i;
            //$minutes = $minutes / 90;
            //echo '<th scope="col" colspan="'.$minutes.'">VACANT<br>'.$end.'-'.$start.'</th>';
          }
          $end = $row2['s_end_at'];
          $day = $row2['day'];
          $room_num = $row2['room_num'];
          $duration = $row2['duration'];
          $colspan = $duration / 90;
          if (strlen($day) == 4){
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.'<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }
          else{
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.' ('.$day.')<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }

        }
        echo '<th scope="col"><strong>Saturday</strong></th>';
        $query2 = "SELECT room_num, subject_code, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, GROUP_CONCAT(day_code ORDER BY day_code ASC) as day, TIMESTAMPDIFF(MINUTE,s_start_at,s_end_at) as duration
                  FROM schedule, schedule_days
                  WHERE schedule.schedule_num = schedule_days.schedule_num
                  AND year_level_section = '$yls'
                  AND curriculum_num = $cn
                  AND day_code IN ('S')
                  GROUP BY subject_code
                  ORDER BY s_start_at
                  ;";#change department_id
        $end = new DateTime('00:00');
        $result2 = mysqli_query($conn, $query2);
        while($row2 = mysqli_fetch_assoc($result2)){
          $subj_code = $row2['subject_code'];
          $start = $row2['s_start_ats'];
          if ($start > $end){
            $start_time = new DateTime($start);
            $end_time = new DateTime($end);
            $interval = $end_time->diff($start_time);
            $minutes = $interval->h * 60 + $interval->i;
            //$minutes = $minutes / 90;
            //echo '<th scope="col" colspan="'.$minutes.'">VACANT<br>'.$end.'-'.$start.'</th>';
          }
          $end = $row2['s_end_at'];
          $day = $row2['day'];
          $room_num = $row2['room_num'];
          $duration = $row2['duration'];
          $colspan = $duration / 90;
          if (strlen($day) == 1){
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.'<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }
          else{
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.' ('.$day.')<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }

        }

        echo '
        </tr>
      </thead>
    </table>
        ';
    }
    //END OF SECOND YEAR
    //THIRD YEAR
    echo '<br><br><h2>3rd Year</h2>
    ';
    $query = "SELECT year_level_section
              FROM schedule
              WHERE year_level_section LIKE '%3%'
              AND curriculum_num = $cn
              GROUP BY year_level_section;";#change department_id
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)){
        $yls = $row['year_level_section'];
        echo '
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">'.$yls.'</th>
              <th scope="col"><strong>Monday-Wednesday-Friday</strong></th>

        ';

        $query2 = "SELECT room_num, subject_code, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, GROUP_CONCAT(day_code ORDER BY day_code desc) as day, TIMESTAMPDIFF(MINUTE,s_start_at,s_end_at) as duration
                  FROM schedule, schedule_days
                  WHERE schedule.schedule_num = schedule_days.schedule_num
                  AND year_level_section = '$yls'
                  AND curriculum_num = $cn
                  AND day_code IN ('M', 'W', 'F')
                  GROUP BY subject_code
                  ORDER BY s_start_at
                  ;";#change department_id
        $end = new DateTime('00:00');
        $result2 = mysqli_query($conn, $query2);
        while($row2 = mysqli_fetch_assoc($result2)){
          $subj_code = $row2['subject_code'];
          $start = $row2['s_start_ats'];
          if ($start > $end){
            $start_time = new DateTime($start);
            $end_time = new DateTime($end);
            $interval = $start_time->diff($end_time);
            $minutes = $interval->h * 60 + $interval->i;
            $minutes = $minutes / 90;
            //echo '<th scope="col" colspan="'.$minutes.'">VACANT<br>'.$end.'-'.$start.'</th>';
          }
          $end = $row2['s_end_at'];
          $day = $row2['day'];
          $room_num = $row2['room_num'];
          $duration = $row2['duration'];
          $colspan = $duration / 90;
          if (strlen($day) == 5){
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.'<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }
          else{
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.' ('.$day.')<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }

        }
        echo '<th scope="col"><strong>Tuesday-Thursday</strong></th>';
        $query2 = "SELECT room_num, subject_code, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, GROUP_CONCAT(day_code ORDER BY day_code ASC) as day, TIMESTAMPDIFF(MINUTE,s_start_at,s_end_at) as duration
                  FROM schedule, schedule_days
                  WHERE schedule.schedule_num = schedule_days.schedule_num
                  AND year_level_section = '$yls'
                  AND curriculum_num = $cn
                  AND day_code IN ('T', 'TH')
                  GROUP BY subject_code
                  ORDER BY s_start_at
                  ;";#change department_id
        $end = new DateTime('00:00');
        $result2 = mysqli_query($conn, $query2);
        while($row2 = mysqli_fetch_assoc($result2)){
          $subj_code = $row2['subject_code'];
          $start = $row2['s_start_ats'];
          if ($start > $end){
            $start_time = new DateTime($start);
            $end_time = new DateTime($end);
            $interval = $end_time->diff($start_time);
            $minutes = $interval->h * 60 + $interval->i;
            //$minutes = $minutes / 90;
            //echo '<th scope="col" colspan="'.$minutes.'">VACANT<br>'.$end.'-'.$start.'</th>';
          }
          $end = $row2['s_end_at'];
          $day = $row2['day'];
          $room_num = $row2['room_num'];
          $duration = $row2['duration'];
          $colspan = $duration / 90;
          if (strlen($day) == 4){
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.'<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }
          else{
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.' ('.$day.')<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }

        }
        echo '<th scope="col"><strong>Saturday</strong></th>';
        $query2 = "SELECT room_num, subject_code, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, GROUP_CONCAT(day_code ORDER BY day_code ASC) as day, TIMESTAMPDIFF(MINUTE,s_start_at,s_end_at) as duration
                  FROM schedule, schedule_days
                  WHERE schedule.schedule_num = schedule_days.schedule_num
                  AND year_level_section = '$yls'
                  AND curriculum_num = $cn
                  AND day_code IN ('S')
                  GROUP BY subject_code
                  ORDER BY s_start_at
                  ;";#change department_id
        $end = new DateTime('00:00');
        $result2 = mysqli_query($conn, $query2);
        while($row2 = mysqli_fetch_assoc($result2)){
          $subj_code = $row2['subject_code'];
          $start = $row2['s_start_ats'];
          if ($start > $end){
            $start_time = new DateTime($start);
            $end_time = new DateTime($end);
            $interval = $end_time->diff($start_time);
            $minutes = $interval->h * 60 + $interval->i;
            //$minutes = $minutes / 90;
            //echo '<th scope="col" colspan="'.$minutes.'">VACANT<br>'.$end.'-'.$start.'</th>';
          }
          $end = $row2['s_end_at'];
          $day = $row2['day'];
          $room_num = $row2['room_num'];
          $duration = $row2['duration'];
          $colspan = $duration / 90;
          if (strlen($day) == 1){
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.'<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }
          else{
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.' ('.$day.')<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }

        }

        echo '
        </tr>
      </thead>
    </table>
        ';
    }
    //END OF THIRD YEAR
    //FOURTH YEAR
    echo '<br><br><h2>4th Year</h2>
    ';
    $query = "SELECT year_level_section
              FROM schedule
              WHERE year_level_section LIKE '%4%'
              AND curriculum_num = $cn
              GROUP BY year_level_section;";#change department_id
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)){
        $yls = $row['year_level_section'];
        echo '
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">'.$yls.'</th>
              <th scope="col"><strong>Monday-Wednesday-Friday</strong></th>

        ';

        $query2 = "SELECT room_num, subject_code, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, GROUP_CONCAT(day_code ORDER BY day_code desc) as day, TIMESTAMPDIFF(MINUTE,s_start_at,s_end_at) as duration
                  FROM schedule, schedule_days
                  WHERE schedule.schedule_num = schedule_days.schedule_num
                  AND year_level_section = '$yls'
                  AND curriculum_num = $cn
                  AND day_code IN ('M', 'W', 'F')
                  GROUP BY subject_code
                  ORDER BY s_start_at
                  ;";#change department_id
        $end = new DateTime('00:00');
        $result2 = mysqli_query($conn, $query2);
        while($row2 = mysqli_fetch_assoc($result2)){
          $subj_code = $row2['subject_code'];
          $start = $row2['s_start_ats'];
          if ($start > $end){
            $start_time = new DateTime($start);
            $end_time = new DateTime($end);
            $interval = $start_time->diff($end_time);
            $minutes = $interval->h * 60 + $interval->i;
            $minutes = $minutes / 90;
            //echo '<th scope="col" colspan="'.$minutes.'">VACANT<br>'.$end.'-'.$start.'</th>';
          }
          $end = $row2['s_end_at'];
          $day = $row2['day'];
          $room_num = $row2['room_num'];
          $duration = $row2['duration'];
          $colspan = $duration / 90;
          if (strlen($day) == 5){
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.'<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }
          else{
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.' ('.$day.')<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }

        }
        echo '<th scope="col"><strong>Tuesday-Thursday</strong></th>';
        $query2 = "SELECT room_num, subject_code, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, GROUP_CONCAT(day_code ORDER BY day_code ASC) as day, TIMESTAMPDIFF(MINUTE,s_start_at,s_end_at) as duration
                  FROM schedule, schedule_days
                  WHERE schedule.schedule_num = schedule_days.schedule_num
                  AND year_level_section = '$yls'
                  AND curriculum_num = $cn
                  AND day_code IN ('T', 'TH')
                  GROUP BY subject_code
                  ORDER BY s_start_at
                  ;";#change department_id
        $end = new DateTime('00:00');
        $result2 = mysqli_query($conn, $query2);
        while($row2 = mysqli_fetch_assoc($result2)){
          $subj_code = $row2['subject_code'];
          $start = $row2['s_start_ats'];
          if ($start > $end){
            $start_time = new DateTime($start);
            $end_time = new DateTime($end);
            $interval = $end_time->diff($start_time);
            $minutes = $interval->h * 60 + $interval->i;
            //$minutes = $minutes / 90;
            //echo '<th scope="col" colspan="'.$minutes.'">VACANT<br>'.$end.'-'.$start.'</th>';
          }
          $end = $row2['s_end_at'];
          $day = $row2['day'];
          $room_num = $row2['room_num'];
          $duration = $row2['duration'];
          $colspan = $duration / 90;
          if (strlen($day) == 4){
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.'<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }
          else{
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.' ('.$day.')<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }

        }
        echo '<th scope="col"><strong>Saturday</strong></th>';
        $query2 = "SELECT room_num, subject_code, DATE_FORMAT(s_start_at, '%l:%i %p') as s_start_ats, DATE_FORMAT(s_end_at, '%l:%i %p') as s_end_at, GROUP_CONCAT(day_code ORDER BY day_code ASC) as day, TIMESTAMPDIFF(MINUTE,s_start_at,s_end_at) as duration
                  FROM schedule, schedule_days
                  WHERE schedule.schedule_num = schedule_days.schedule_num
                  AND year_level_section = '$yls'
                  AND curriculum_num = $cn
                  AND day_code IN ('S')
                  GROUP BY subject_code
                  ORDER BY s_start_at
                  ;";#change department_id
        $end = new DateTime('00:00');
        $result2 = mysqli_query($conn, $query2);
        while($row2 = mysqli_fetch_assoc($result2)){
          $subj_code = $row2['subject_code'];
          $start = $row2['s_start_ats'];
          if ($start > $end){
            $start_time = new DateTime($start);
            $end_time = new DateTime($end);
            $interval = $end_time->diff($start_time);
            $minutes = $interval->h * 60 + $interval->i;
            //$minutes = $minutes / 90;
            //echo '<th scope="col" colspan="'.$minutes.'">VACANT<br>'.$end.'-'.$start.'</th>';
          }
          $end = $row2['s_end_at'];
          $day = $row2['day'];
          $room_num = $row2['room_num'];
          $duration = $row2['duration'];
          $colspan = $duration / 90;
          if (strlen($day) == 1){
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.'<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }
          else{
            echo '
            <th scope="col" colspan="'.$colspan.'">'.$subj_code.' ('.$day.')<br>'.$start.'-'.$end.'<br>'.$room_num.'</th>
            ';
          }

        }

        echo '
        </tr>
      </thead>
    </table>
        ';
    }
    //END OF FOURTH YEAR
}

?>
