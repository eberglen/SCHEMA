<?php
  session_start();
  if($_SESSION['level_access'] == 'dean'){
  include 'includes/dbconn.inc.php';
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
 <title>ScheduLe Management</title>
 <link rel="shortcut icon" href="includes/sbulogo6.ico"/>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 <meta http-equiv="x-ua-compatible" content="ie=edge">
 <!-- Font Awesome -->
 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
 <!-- Bootstrap core CSS -->
 <link href="mdbootstrap/css/bootstrap.min.css" rel="stylesheet">
 <!-- Material Design Bootstrap -->
 <link href="mdbootstrap/css/mdb.min.css" rel="stylesheet">
 <!-- Your custom styles (optional) -->
 <link href="mdbootstrap/css/style.css" rel="stylesheet">
 <script type="text/javascript" src="mdbootstrap/js/jquery-3.4.1.min.js"></script>
 <!-- Bootstrap tooltips -->
 <script type="text/javascript" src="mdbootstrap/js/popper.min.js"></script>
 <!-- Bootstrap core JavaScript -->
 <script type="text/javascript" src="mdbootstrap/js/bootstrap.min.js"></script>
 <!-- MDB core JavaScript -->
 <script type="text/javascript" src="mdbootstrap/js/mdb.min.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 <script type="text/javascript">
   function wind_ref(){
     window.location.reload();
   }
 </script>
 <!--Navbar -->
 <nav class="mb-1 navbar navbar-expand-lg navbar-dark blue-gradient">
   <a class="navbar-brand" href="home_dean.php">
     <img src="includes/sbulogo6.png" height="30" class="d-inline-block align-top"
       alt="mdb logo" >Schedule Management System</a>
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-333"
     aria-controls="navbarSupportedContent-333" aria-expanded="false" aria-label="Toggle navigation">
     <span class="navbar-toggler-icon"></span>
   </button>
   <div class="collapse navbar-collapse" id="navbarSupportedContent-333">
     <ul class="navbar-nav ml-auto nav-flex-icons">
       <li class="nav-item dropdown">
         <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-333" data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">
           <i class="fas fa-user"></i>
         </a>
         <div class="dropdown-menu dropdown-menu-right dropdown-default"
           aria-labelledby="navbarDropdownMenuLink-333">
           <a class="dropdown-item" href="account.php">Account</a>
           <a class="dropdown-item" href="includes/logout.inc.php">Logout</a>
         </div>
       </li>
     </ul>
   </div>
 </nav>
 <!--/.Navbar -->

</head>
<body>

<div class="container shadow-lg p-3 mb-5 bg-white rounded">
  <div class="row">
    <div class="col-md-12"><br>
      <?php
      date_default_timezone_set("Asia/Hong_Kong");
      $date_today = date("Y-m-d H:i:s");
      $cn = '';
      $school_year = '';
      $semester = '';

      $query = "SELECT DATE_FORMAT(os_end, '%M %e, %Y %r') as os_end, academic_year, semester, curriculum.curriculum_num as curriculum_num
                FROM open_sched, curriculum
                WHERE open_sched.curriculum_num = curriculum.curriculum_num
                AND os_start < '$date_today'
                AND os_end > '$date_today'
                ;";

      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_assoc($result)){
        $cn = $row['curriculum_num'];
        $school_year = $row['academic_year'];
        $semester = $row['semester'];
        $os_end = $row['os_end'];
      }
      if(!empty($cn)){
        echo '<H1><strong>'.$school_year." ".$semester.' </strong></H1>(Closes: '.$os_end.')';
      }
      else{
        echo '<H1>Scheduling is currently closed.</H1>';
      }

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
              $sql3 = "SELECT *
                      FROM approved
                      WHERE year_level_section = '$yls'
                      AND curriculum_num = $cn
                      ;";
              $result3 = mysqli_query($conn, $sql3);
              if (mysqli_num_rows($result3) > 0){
                echo '
                <th scope="col">
                <p class="text-success">Approved</p>
                </th>
                ';
              }
              else{
                echo '
                <th scope="col">
                <form action="includes/dean_approved.inc.php" method="POST">
                  <input type="hidden" name="yls" value="'.$yls.'">
                  <input type="hidden" name="cur_num" value="'.$cn.'">
                  <button type="submit" class="btn btn-success"><i class="fas fa-check">Approve</i></button>
                </form>
                </th>
                ';
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
              $sql3 = "SELECT *
                      FROM approved
                      WHERE year_level_section = '$yls'
                      AND curriculum_num = $cn
                      ;";
              $result3 = mysqli_query($conn, $sql3);
              if (mysqli_num_rows($result3) > 0){
                echo '
                <th scope="col">
                <p class="text-success">Approved</p>
                </th>
                ';
              }
              else{
                echo '
                <th scope="col">
                <form action="includes/dean_approved.inc.php" method="POST">
                  <input type="hidden" name="yls" value="'.$yls.'">
                  <input type="hidden" name="cur_num" value="'.$cn.'">
                  <button type="submit" class="btn btn-success"><i class="fas fa-check">Approve</i></button>
                </form>
                </th>
                ';
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
              $sql3 = "SELECT *
                      FROM approved
                      WHERE year_level_section = '$yls'
                      AND curriculum_num = $cn
                      ;";
              $result3 = mysqli_query($conn, $sql3);
              if (mysqli_num_rows($result3) > 0){
                echo '
                <th scope="col">
                <p class="text-success">Approved</p>
                </th>
                ';
              }
              else{
                echo '
                <th scope="col">
                <form action="includes/dean_approved.inc.php" method="POST">
                  <input type="hidden" name="yls" value="'.$yls.'">
                  <input type="hidden" name="cur_num" value="'.$cn.'">
                  <button type="submit" class="btn btn-success"><i class="fas fa-check">Approve</i></button>
                </form>
                </th>
                ';
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
              $sql3 = "SELECT *
                      FROM approved
                      WHERE year_level_section = '$yls'
                      AND curriculum_num = $cn
                      ;";
              $result3 = mysqli_query($conn, $sql3);
              if (mysqli_num_rows($result3) > 0){
                echo '
                <th scope="col">
                <p class="text-success">Approved</p>
                </th>
                ';
              }
              else{
                echo '
                <th scope="col">
                <form action="includes/dean_approved.inc.php" method="POST">
                  <input type="hidden" name="yls" value="'.$yls.'">
                  <input type="hidden" name="cur_num" value="'.$cn.'">
                  <button type="submit" class="btn btn-success"><i class="fas fa-check">Approve</i></button>
                </form>
                </th>
                ';
              }
              echo '
              </tr>
            </thead>
          </table>
              ';
          }
          //END OF FOURTH YEAR

      ?>

      <div class="col-md-12" name="sections" id="sections"></div>
    </div>
  </div>
</div>
</body>
<?php
}
else if($_SESSION['level_access'] == 'dept_chair'){
  header("Location:./schedule.php");
  exit();
}
else if($_SESSION['level_access'] == 'admin'){
  header("Location:./home_admin.php");
  exit();
}
else{
  header("Location:./index.php");
  exit();
}
?>
