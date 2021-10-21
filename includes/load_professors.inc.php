<?php
session_start();
include 'dbconn.inc.php';
include 'header.inc.php';
$sched_days = '';


if(!empty($_POST["schedule_num"])){
  $query = "SELECT *
            FROM schedule, subjects
            WHERE schedule.subject_code = subjects.subject_code
            AND schedule_num=".$_POST['schedule_num'].";";
  $result = mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)){
    $start = $row['s_start_at'];
    $end = $row['s_end_at'];
    $cn = $row['curriculum_num'];
    $did = $row['department_id'];
    $sql = "SELECT day_code
            FROM schedule_days
            WHERE schedule_num=".$_POST['schedule_num'].";";
    $sched_days_result = mysqli_query($conn, $sql);
    while($row_d = mysqli_fetch_assoc($sched_days_result)){
      $sched_days .= '"'.$row_d['day_code'].'",';
    }
    $sched_days = substr($sched_days, 0, -1);
    $sched_days = '('.$sched_days.')';
  }
}

if(!empty($_POST["schedule_num"])){
  echo '<option selected disabled value="">Select Professor...</option>';
  $query = "SELECT *
            FROM schedule, employee
            where schedule.employee_num = employee.employee_num
            AND schedule_num=".$_POST["schedule_num"].";";
  #$result = $db->query($query);
  $result = mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)){
      if (isset($row['last_name'])){
        echo '<option selected value="'.$row['employee_num'].'">'.$row['first_name'].' '.$row['last_name'].'</option>';
      }
      else{

      }
  }
}


  $query="SELECT employee.employee_num, first_name, last_name
          from employee, employee_departments
          where employee.employee_num=employee_departments.employee_num
          AND department_id='$did'
          AND NOT EXISTS
          (select employee_num
          from schedule, schedule_days
          where employee.employee_num=schedule.employee_num
          and schedule.schedule_num=schedule_days.schedule_num
          AND curriculum_num=$cn
          and day_code IN $sched_days
          AND ((s_start_at >= '$start' AND s_start_at < '$end')
          OR (s_end_at > '$start' AND s_end_at <= '$end')));";//CHANGE
  $result=mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)){
    if (isset($row['employee_num'])){

      echo '<option value="'.$row['employee_num'].'">'.$row['first_name'].' '.$row['last_name'].'</option>';
    }
    else {
      echo '<option value="">No Available Professors</option>';
    }
  }
#echo '<option value="">'.$taken.'</option>';
#if (isset($_POST['day']))
  #echo $_POST['day'];

?>
