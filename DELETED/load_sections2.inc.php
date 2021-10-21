<?php
session_start();
include 'dbconn.inc.php';
$cn = $_POST['curriculum_num'];
if(!empty($_POST["curriculum_num"])){
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
                    <h6 class="card-title">PROGRESS</h6>

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
                    <button type="submit" class="btn" style="background-color:#4782CA; color:white">Update Schedule</button>
                  </div>
                </div><br>
              </form>';
      }


}
?>
