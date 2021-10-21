<?php
include 'dbconn.inc.php';
$cn = $_POST['curriculum_num'];
$query = "SELECT DATE_FORMAT(os_start, '%M %e, %Y %r') as os_start, DATE_FORMAT(os_end, '%M %e, %Y %r') as os_end, academic_year, semester
          FROM open_sched, curriculum
          WHERE open_sched.curriculum_num = curriculum.curriculum_num;";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0){
  while($row = mysqli_fetch_assoc($result)) {
    $os_start = $row['os_start'];
    $os_end = $row['os_end'];
    $ay = $row['academic_year'];
    $sem = $row['semester'];

    echo '<br>
      <h3><strong>Currently Open:</strong> '.$ay.' '.$sem.' ('.$os_start.' - '.$os_end.') <button type="submit" name="close_os" id="close_os" class="btn btn-danger" onclick="stop_os();">Close</button></h3>
      <div name="close_os_status" id="close_os_status"></div>
    ';
  }
}
else{
  echo '<br>
  <h3><strong>Open Scheduling</strong></h3>
  <div class="row">
  <div class="col-md-5">
  <h5>Start: <input type="datetime-local" class="form-control" name="os_start" id="os_start"></input></div>
  <div class="col-md-5"> End: <input type="datetime-local" class="form-control" name="os_end" id="os_end"></input></div>
  <div class="col-md-2"><button type="button" class="btn btn-success" onclick="open_sched();">Open</button></h5>
  </div>
  </div>
  <br>
  <div name="os_status" id="os_status"></div>';

}

$query = "SELECT department.department_id as department_id, department_desc
          FROM schedule, department, subjects
          WHERE curriculum_num = $cn
          AND schedule.subject_code = subjects.subject_code
          AND subjects.department_id = department.department_id
          GROUP BY department_id;";
$result = mysqli_query($conn, $query);
echo '
<div class="col-md-12">';
while($row = mysqli_fetch_assoc($result)){
    $deptl = $row['department_desc'];
    $dept = $row['department_id'];
      $query = "SELECT COUNT(*) AS 'count'
                FROM schedule, subjects, department
                WHERE schedule.subject_code = subjects.subject_code
                AND department.department_id = subjects.department_id
                AND subjects.department_id = '".$dept."'
                AND curriculum_num = $cn;";#change department_id
      $rowname = 'count';
      $total = $mysqlidb->sql_query($query,$rowname);
      if ($total == 0){
        $total = 1;
      }
      $query = "SELECT COUNT(*) AS 'count'
                FROM schedule, subjects, department
                WHERE schedule.subject_code = subjects.subject_code
                AND department.department_id = subjects.department_id
                AND subjects.department_id = '".$dept."'
                AND curriculum_num = $cn
                AND s_start_at is NOT NULL;";
      $done_time = $mysqlidb->sql_query($query,$rowname);
      $query = "SELECT COUNT(*) AS 'count'
                FROM schedule, subjects, department
                WHERE schedule.subject_code = subjects.subject_code
                AND department.department_id = subjects.department_id
                AND subjects.department_id = '".$dept."'
                AND curriculum_num = $cn
                AND employee_num is NOT NULL;";
      $done_prof = $mysqlidb->sql_query($query,$rowname);
      $query = "SELECT COUNT(*) AS 'count'
                FROM schedule, subjects, department
                WHERE schedule.subject_code = subjects.subject_code
                AND department.department_id = subjects.department_id
                AND subjects.department_id = '".$dept."'
                AND curriculum_num = $cn
                AND room_num is NOT NULL;";
      $done_room = $mysqlidb->sql_query($query,$rowname);
      $done = $done_prof + $done_room + $done_time;
      $total = $total * 3;
      $prog = $done/$total * 100;
          echo '
          <br><h3 class="animated fadeInRight">'.$deptl.'</h3><div class="progress animated fadeInLeft">
            <div class="progress-bar-animated progress-bar progress-bar-striped bg-info" role="progressbar" style="width: '.$prog.'%" aria-valuenow="'.$prog.'" aria-valuemin="0" aria-valuemax="100">Progress</div>
          </div><br>
          ';
  }
echo '</div>';
echo '<div class="row"><div class="col-md-3"><br>
<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col"><h1>1st Year</h1></th>
    </tr>
  </thead>
  <tbody>';

  $sql = "SELECT year_level_section
          FROM schedule
          WHERE curriculum_num = $cn
          AND year_level_section LIKE '%1%'
          GROUP BY year_level_section
          ;";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
    $yls = $row['year_level_section'];
    $sql2 = "SELECT *
            FROM approved
            WHERE curriculum_num = $cn
            AND year_level_section = '$yls'
            ;";
    $result2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result2) > 0){
      echo '
      <tr>
        <th scope="row"><p class="text-success">'.$yls.' Approved</p></th>
      </tr>
      ';
    }
    else{
      echo '
      <tr>
        <th scope="row"><p class="text-warning">'.$yls.' Pending</p></th>
      </tr>
      ';
    }
  }

echo'
  </tbody>
</table>';
echo '</div>';
echo '<div class="col-md-3"><br>
<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col"><h1>2nd Year</h1></th>
    </tr>
  </thead>
  <tbody>';

  $sql = "SELECT year_level_section
          FROM schedule
          WHERE curriculum_num = $cn
          AND year_level_section LIKE '%2%'
          GROUP BY year_level_section
          ;";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
    $yls = $row['year_level_section'];
    $sql2 = "SELECT *
            FROM approved
            WHERE curriculum_num = $cn
            AND year_level_section = '$yls'
            ;";
    $result2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result2) > 0){
      echo '
      <tr>
        <th scope="row"><p class="text-success">'.$yls.' Approved</p></th>
      </tr>
      ';
    }
    else{
      echo '
      <tr>
        <th scope="row"><p class="text-warning">'.$yls.' Pending</p></th>
      </tr>
      ';
    }
  }

echo'
  </tbody>
</table>';
echo '</div>';
echo '<div class="col-md-3"><br>
<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col"><h1>3rd Year</h1></th>
    </tr>
  </thead>
  <tbody>';

  $sql = "SELECT year_level_section
          FROM schedule
          WHERE curriculum_num = $cn
          AND year_level_section LIKE '%3%'
          GROUP BY year_level_section
          ;";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
    $yls = $row['year_level_section'];
    $sql2 = "SELECT *
            FROM approved
            WHERE curriculum_num = $cn
            AND year_level_section = '$yls'
            ;";
    $result2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result2) > 0){
      echo '
      <tr>
        <th scope="row"><p class="text-success">'.$yls.' Approved</p></th>
      </tr>
      ';
    }
    else{
      echo '
      <tr>
        <th scope="row"><p class="text-warning">'.$yls.' Pending</p></th>
      </tr>
      ';
    }
  }

echo'
  </tbody>
</table>';
echo '</div>';
echo '<div class="col-md-3"><br>
<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col"><h1>4th Year</h1></th>
    </tr>
  </thead>
  <tbody>';

  $sql = "SELECT year_level_section
          FROM schedule
          WHERE curriculum_num = $cn
          AND year_level_section LIKE '%4%'
          GROUP BY year_level_section
          ;";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
    $yls = $row['year_level_section'];
    $sql2 = "SELECT *
            FROM approved
            WHERE curriculum_num = $cn
            AND year_level_section = '$yls'
            ;";
    $result2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result2) > 0){
      echo '
      <tr>
        <th scope="row"><p class="text-success">'.$yls.' Approved</p></th>
      </tr>
      ';
    }
    else{
      echo '
      <tr>
        <th scope="row"><p class="text-warning">'.$yls.' Pending</p></th>
      </tr>
      ';
    }
  }

echo'
  </tbody>
</table>';
echo '</div>';
echo '</div>';//row div

?>
