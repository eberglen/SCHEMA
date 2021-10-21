<?php
  session_start();
  if($_SESSION['level_access'] == 'dept_chair'){
  date_default_timezone_set("Asia/Hong_Kong");
  $date_today = date("Y-m-d H:i:s");
  //include 'includes/dbh.inc.php';
  //include 'includes/query.inc.php';
  include 'includes/dbconn.inc.php';
  //$object = new sqldata;
  $max = '';
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
    $max = $row['curriculum_num'];
    $school_year = $row['academic_year'];
    $semester = $row['semester'];
    $os_end = $row['os_end'];
  }
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
  <?php include 'includes/header.inc.php'; ?>
</head>

<body>

  <div class="shadow-lg p-3 mb-5 bg-white rounded">
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="schedule.php">Schedule</a>

      </li>
      <li class="nav-item">
        <a class="nav-link" href="export.php">Export</a>
      </li>
    </ul>
    <br>
    <div class="animated fadeIn">
      <?php
      if(!empty($max)){
        echo '<H1>'.$school_year." ".$semester.' </H1>(Closes: '.$os_end.')';
      }
      else{
        echo '<H1>Scheduling is currently closed.</H1>';
      }

      ?>
    </div>
    <div class="row">
      <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-3" name="sections" id="sections">
        <h1>First Year</h1>
        <?php
        $cn = $max;
        if(!empty($cn)){
            // Fetch state data based on the specific country

            $query = "SELECT schedule.year_level_section
            					FROM schedule, subjects
            					WHERE schedule.subject_code=subjects.subject_code
                      AND year_level_section LIKE '%1%'
            					AND schedule.curriculum_num=".$cn."
            					AND department_id='".$_SESSION['dept_id']."'
                      GROUP BY year_level_section;";#change department_id
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_assoc($result)){
                $yls = $row['year_level_section'];
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND department_id='".$_SESSION['dept_id']."'
                            AND curriculum_num = $cn;";#change department_id
                  $rowname = 'count';
                  $total = $mysqlidb->sql_query($query,$rowname);
                  if ($total == 0){
                    $total = 1;
                  }
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND curriculum_num = $cn
                            AND department_id='".$_SESSION['dept_id']."'
                            AND s_start_at is NOT NULL;";
                  $done_time = $mysqlidb->sql_query($query,$rowname);
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND curriculum_num = $cn
                            AND department_id='".$_SESSION['dept_id']."'
                            AND employee_num is NOT NULL;";
                  $done_prof = $mysqlidb->sql_query($query,$rowname);
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND curriculum_num = $cn
                            AND department_id='".$_SESSION['dept_id']."'
                            AND room_num is NOT NULL;";
                  $done_room = $mysqlidb->sql_query($query,$rowname);
                  $time_prog = $done_time / $total * 100;
                  $prof_prog = $done_prof / $total * 100;
                  $room_prog = $done_room / $total * 100;

                      echo '
                      <form action="update_schedule.php" method="POST">
                        <div class="card animated fadeIn">
                        <h5 class="card-header">'.$yls.'</h5>
                          <div class="card-body">
                            <h6 class="card-title">Progress</h6>

                            <div class="progress">
                              <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: '.$time_prog.'%" aria-valuenow="'.$time_prog.'" aria-valuemin="0" aria-valuemax="100">Time</div>
                            </div>
                            <div class="progress">
                              <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: '.$prof_prog.'%" aria-valuenow="'.$prof_prog.'" aria-valuemin="0" aria-valuemax="100">Professor</div>
                            </div>
                            <div class="progress">
                              <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: '.$room_prog.'%" aria-valuenow="'.$room_prog.'" aria-valuemin="0" aria-valuemax="100">Room</div>
                            </div>
                              <br>
                              <input type="hidden" name="yls" value="'.$yls.'"></input>
                              <input type="hidden" name="cn" value="'.$cn.'"></input>
                            <button type="submit" name="submit" class="btn btn-info">Update Schedule</button>
                          </div>
                        </div><br>
                      </form>
                      ';

              }


        }
        ?>
      </div>
      <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-3" name="sections2" id="sections2">
        <h1>Second Year</h1>
        <?php
        $cn = $max;
        if(!empty($cn)){
            // Fetch state data based on the specific country

            $query = "SELECT schedule.year_level_section
            					FROM schedule, subjects
            					WHERE schedule.subject_code=subjects.subject_code
                      AND year_level_section LIKE '%2%'
            					AND schedule.curriculum_num=".$cn."
            					AND department_id='".$_SESSION['dept_id']."'
                      GROUP BY year_level_section;";#change department_id
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_assoc($result)){
                $yls = $row['year_level_section'];
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND department_id='".$_SESSION['dept_id']."'
                            AND curriculum_num = $cn;";#change department_id
                  $rowname = 'count';
                  $total = $mysqlidb->sql_query($query,$rowname);
                  if ($total == 0){
                    $total = 1;
                  }
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND curriculum_num = $cn
                            AND department_id='".$_SESSION['dept_id']."'
                            AND s_start_at is NOT NULL;";
                  $done_time = $mysqlidb->sql_query($query,$rowname);
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND curriculum_num = $cn
                            AND department_id='".$_SESSION['dept_id']."'
                            AND employee_num is NOT NULL;";
                  $done_prof = $mysqlidb->sql_query($query,$rowname);
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND curriculum_num = $cn
                            AND department_id='".$_SESSION['dept_id']."'
                            AND room_num is NOT NULL;";
                  $done_room = $mysqlidb->sql_query($query,$rowname);
                  $time_prog = $done_time / $total * 100;
                  $prof_prog = $done_prof / $total * 100;
                  $room_prog = $done_room / $total * 100;

                      echo '
                      <form action="update_schedule.php" method="POST">
                        <div class="card animated fadeInLeft">
                        <h5 class="card-header">'.$yls.'</h5>
                          <div class="card-body">
                            <h6 class="card-title">Progress</h6>

                            <div class="progress">
                              <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: '.$time_prog.'%" aria-valuenow="'.$time_prog.'" aria-valuemin="0" aria-valuemax="100">Time</div>
                            </div>
                            <div class="progress">
                              <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: '.$prof_prog.'%" aria-valuenow="'.$prof_prog.'" aria-valuemin="0" aria-valuemax="100">Professor</div>
                            </div>
                            <div class="progress">
                              <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: '.$room_prog.'%" aria-valuenow="'.$room_prog.'" aria-valuemin="0" aria-valuemax="100">Room</div>
                            </div>
                              <br>
                              <input type="hidden" name="yls" value="'.$yls.'"></input>
                              <input type="hidden" name="cn" value="'.$cn.'"></input>
                            <button type="submit" name="submit" class="btn btn-info">Update Schedule</button>
                          </div>
                        </div><br>
                      </form>';
              }


        }
        ?>
      </div>
      <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-3" name="sections3" id="sections3">
        <h1>Third Year</h1>
        <?php
        $cn = $max;
        if(!empty($cn)){
            // Fetch state data based on the specific country

            $query = "SELECT schedule.year_level_section
            					FROM schedule, subjects
            					WHERE schedule.subject_code=subjects.subject_code
                      AND year_level_section LIKE '%3%'
            					AND schedule.curriculum_num=".$cn."
            					AND department_id='".$_SESSION['dept_id']."'
                      GROUP BY year_level_section;";#change department_id
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_assoc($result)){
                $yls = $row['year_level_section'];
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND department_id='".$_SESSION['dept_id']."'
                            AND curriculum_num = $cn;";#change department_id
                  $rowname = 'count';
                  $total = $mysqlidb->sql_query($query,$rowname);
                  if ($total == 0){
                    $total = 1;
                  }
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND curriculum_num = $cn
                            AND department_id='".$_SESSION['dept_id']."'
                            AND s_start_at is NOT NULL;";
                  $done_time = $mysqlidb->sql_query($query,$rowname);
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND curriculum_num = $cn
                            AND department_id='".$_SESSION['dept_id']."'
                            AND employee_num is NOT NULL;";
                  $done_prof = $mysqlidb->sql_query($query,$rowname);
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND curriculum_num = $cn
                            AND department_id='".$_SESSION['dept_id']."'
                            AND room_num is NOT NULL;";
                  $done_room = $mysqlidb->sql_query($query,$rowname);
                  $time_prog = $done_time / $total * 100;
                  $prof_prog = $done_prof / $total * 100;
                  $room_prog = $done_room / $total * 100;

                      echo '
                      <form action="update_schedule.php" method="POST">
                        <div class="card animated fadeInRight">
                        <h5 class="card-header">'.$yls.'</h5>
                          <div class="card-body">
                            <h6 class="card-title">Progress</h6>

                            <div class="progress">
                              <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: '.$time_prog.'%" aria-valuenow="'.$time_prog.'" aria-valuemin="0" aria-valuemax="100">Time</div>
                            </div>
                            <div class="progress">
                              <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: '.$prof_prog.'%" aria-valuenow="'.$prof_prog.'" aria-valuemin="0" aria-valuemax="100">Professor</div>
                            </div>
                            <div class="progress">
                              <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: '.$room_prog.'%" aria-valuenow="'.$room_prog.'" aria-valuemin="0" aria-valuemax="100">Room</div>
                            </div>
                              <br>
                              <input type="hidden" name="yls" value="'.$yls.'"></input>
                              <input type="hidden" name="cn" value="'.$cn.'"></input>
                            <button type="submit" name="submit" class="btn btn-info">Update Schedule</button>
                          </div>
                        </div><br>
                      </form>';
              }


        }
        ?>
      </div>
      <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-3" name="sections4" id="sections4">
        <h1>Fourth Year</h1>
        <?php
        $cn = $max;
        if(!empty($cn)){
            // Fetch state data based on the specific country

            $query = "SELECT schedule.year_level_section
            					FROM schedule, subjects
            					WHERE schedule.subject_code=subjects.subject_code
                      AND year_level_section LIKE '%4%'
            					AND schedule.curriculum_num=".$cn."
            					AND department_id='".$_SESSION['dept_id']."'
                      GROUP BY year_level_section;";#change department_id
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_assoc($result)){
                $yls = $row['year_level_section'];
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND department_id='".$_SESSION['dept_id']."'
                            AND curriculum_num = $cn;";#change department_id
                  $rowname = 'count';
                  $total = $mysqlidb->sql_query($query,$rowname);
                  if ($total == 0){
                    $total = 1;
                  }
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND curriculum_num = $cn
                            AND department_id='".$_SESSION['dept_id']."'
                            AND s_start_at is NOT NULL;";
                  $done_time = $mysqlidb->sql_query($query,$rowname);
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND curriculum_num = $cn
                            AND department_id='".$_SESSION['dept_id']."'
                            AND employee_num is NOT NULL;";
                  $done_prof = $mysqlidb->sql_query($query,$rowname);
                  $query = "SELECT COUNT(*) AS 'count'
                            FROM schedule, subjects
                            WHERE schedule.subject_code=subjects.subject_code
                            AND year_level_section = '$yls'
                            AND curriculum_num = $cn
                            AND department_id='".$_SESSION['dept_id']."'
                            AND room_num is NOT NULL;";
                  $done_room = $mysqlidb->sql_query($query,$rowname);
                  $time_prog = $done_time / $total * 100;
                  $prof_prog = $done_prof / $total * 100;
                  $room_prog = $done_room / $total * 100;

                      echo '
                      <form action="update_schedule.php" method="POST">
                        <div class="card animated fadeIn">
                        <h5 class="card-header">'.$yls.'</h5>
                          <div class="card-body">
                            <h6 class="card-title">Progress</h6>
                            <div class="progress">
                              <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: '.$time_prog.'%" aria-valuenow="'.$time_prog.'" aria-valuemin="0" aria-valuemax="100">Time</div>
                            </div>
                            <div class="progress">
                              <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: '.$prof_prog.'%" aria-valuenow="'.$prof_prog.'" aria-valuemin="0" aria-valuemax="100">Professor</div>
                            </div>
                            <div class="progress">
                              <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: '.$room_prog.'%" aria-valuenow="'.$room_prog.'" aria-valuemin="0" aria-valuemax="100">Room</div>
                            </div>
                              <br>
                              <input type="hidden" name="yls" value="'.$yls.'"></input>
                              <input type="hidden" name="cn" value="'.$cn.'"></input>
                            <button type="submit" name="submit" class="btn btn-info">Update Schedule</button>
                          </div>
                        </div><br>
                      </form>';
              }


        }
        ?>
      </div>
    </div>
  </div>
</body>
</html>
<?php
}
else if($_SESSION['level_access'] == 'admin'){
  header("Location:./home_admin.php");
  exit();
}
else if($_SESSION['level_access'] == 'dean'){
  header("Location:./home_dean.php");
  exit();
}
else{
  header("Location:./index.php");
  exit();
}
?>
