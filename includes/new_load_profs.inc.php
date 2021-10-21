<?php
session_start();
include 'dbconn.inc.php';
echo '<option selected disabled value="">Select Professor...</option>';
if ((isset($_POST['day'])) && (isset($_POST['sched_num'])) && (isset($_POST['start'])) && (isset($_POST['end']))){
  $day = $_POST['day'];
  $sched_num = $_POST['sched_num'];
  $start = $_POST['start'];
  $end = $_POST['end'];
  $days = '';
  if (isset($day[0]))
    $days .= $day[0].',';
  if (isset($day[1]))
    $days .= $day[1].',';
  if (isset($day[2]))
    $days .= $day[2].',';
  if (isset($day[3]))
    $days .= $day[3].',';
  if (isset($day[4]))
    $days .= $day[4].',';
  if (isset($day[5]))
    $days .= $day[5].',';
  if ($days != ''){
    $days = substr($days, 0, -1);
    $days = '('.$days.')';
  }
  $query="SELECT curriculum_num, department_id
          FROM schedule, subjects
          where schedule.subject_code = subjects.subject_code
          AND schedule_num=".$sched_num.";";
  $result = mysqli_query($conn, $query);
  while ($row = mysqli_fetch_assoc($result)){
      $cn = $row['curriculum_num'];
      $did = $row['department_id'];
  }


  $taken = '';
  $ctr = 0;
  $result_schednum = '';

  $query="SELECT employee_num, schedule.schedule_num
          from schedule, schedule_days
          WHERE schedule.schedule_num=schedule_days.schedule_num
          AND curriculum_num=$cn
          and day_code IN $days
          AND ((s_start_at >= '$start' AND s_start_at < '$end')
          OR (s_end_at > '$start' AND s_end_at <= '$end'))
          GROUP BY schedule_num;";//CHANGE'
  $result = mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)){
    $taken .= $row['employee_num'];
    $ctr = $ctr + 1;
    $result_schednum .= $row['schedule_num'];
  }
  if ($ctr == 1){
    if ($result_schednum == $sched_num){
      $taken = '';
    }
  }
  $query="SELECT employee.employee_num, first_name, last_name
          from employee, employee_departments
          where employee.employee_num=employee_departments.employee_num
          AND department_id='".$did."';";
  $result=mysqli_query($conn, $query);

  while($row = mysqli_fetch_assoc($result)){
    if (strpos($taken, $row['employee_num']) !== false){
      //echo '<option disabled value="'.$row['employee_num'].'">'.$row['first_name'].' '.$row['last_name'].' (Taken)</option>';
    }
    else {
      echo '<option value="'.$row['employee_num'].'">'.$row['first_name'].' '.$row['last_name'].'</option>';
    }
  }
}
?>
