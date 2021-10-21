<?php
  include 'includes/dbconn.inc.php';

  $query = "SELECT max(curriculum_num) as max, academic_year, semester
            FROM curriculum;";

  $result = mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)){
    $max = $row['max'];
    $school_year = $row['academic_year'];
    $semester = $row['semester'];
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>ScheduLe Management</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Material Design Bootstrap</title>
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
  $(document).ready(function(){
    $('#cur_num').on('change',function(){
      var curriculum_num = $(this).val();
      if(curriculum_num){
        $.ajax({
          type: 'POST',
          url: 'includes/load_sections.inc.php',
          data: 'curriculum_num=' + curriculum_num,
          success:function(html){
            $('#sections').html(html);
          }
        });
        $.ajax({
          type: 'POST',
          url: 'includes/load_sections2.inc.php',
          data: 'curriculum_num=' + curriculum_num,
          success:function(html){
            $('#sections2').html(html);
          }
        });
        $.ajax({
          type: 'POST',
          url: 'includes/load_sections3.inc.php',
          data: 'curriculum_num=' + curriculum_num,
          success:function(html){
            $('#sections3').html(html);
          }
        });
        $.ajax({
          type: 'POST',
          url: 'includes/load_sections4.inc.php',
          data: 'curriculum_num=' + curriculum_num,
          success:function(html){
            $('#sections4').html(html);
          }
        });
      }
    });
  });
  </script>
  <?php include 'includes/header.inc.php' ?>
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
      <li class="nav-item">
        <a class="nav-link" href="faculty.php">Faculty</a>
      </li>
    </ul>
    <br>

    <select class="custom-select mr-sm-2" name="cur_num" id="cur_num">
      <option disabled selected><?php echo $school_year." ".$semester; ?></option>
      <option disabled>Select Academic Year...</option>
      <?php echo $object->getMultipleData("SELECT * FROM curriculum ORDER BY curriculum_num DESC", "curriculum_num", "academic_year", "semester"); ?>
    </select><br><br>
    <div class="row">
      <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-3" name="sections" id="sections">
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
                        <div class="card">
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
                            <button type="submit" class="btn btn-info">Update Schedule</button>
                          </div>
                        </div><br>
                      </form>
                      ';

              }


        }
        ?>
      </div>
      <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-3" name="sections2" id="sections2">
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
                        <div class="card">
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
                            <button type="submit" class="btn btn-info">Update Schedule</button>
                          </div>
                        </div><br>
                      </form>';
              }


        }
        ?>
      </div>
      <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-3" name="sections3" id="sections3">
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
                        <div class="card">
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
                            <button type="submit" class="btn btn-info">Update Schedule</button>
                          </div>
                        </div><br>
                      </form>';
              }


        }
        ?>
      </div>
      <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-3" name="sections4" id="sections4">
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
                        <div class="card">
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
                            <button type="submit" class="btn btn-info">Update Schedule</button>
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
