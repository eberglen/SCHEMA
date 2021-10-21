<?php


if (!empty($_POST['item_unit']) && !empty($_POST['fn']) && !empty($_POST['mn']) && !empty($_POST['ln'])){
  include 'dbconn.inc.php';
  $fn = $_POST['fn'];
  $mn = $_POST['mn'];
  $ln = $_POST['ln'];
  $subjects = $_POST['item_unit'];
  $sql = '';
  foreach ($subjects as $value){
    $sql = $sql."(LAST_INSERT_ID(),'".$value."'),";
    #echo $value;
  }
  $sql = substr($sql,0,-1);
  $sql = "INSERT INTO employee (first_name, last_name, middle_name) VALUES ('$fn','$ln','$mn');
  INSERT INTO employee_departments (employee_num, department_id) VALUES ".$sql.";";
  $result = mysqli_multi_query($conn, $sql);
  if ($result){
    echo '<div class="alert alert-success alert-dismissible fade show animated fadeIn" role="alert">
    <strong>Success! </strong> Record updated successfully.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick="wind_ref();">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>';
  }
  else{
    echo '<div class="alert alert-danger alert-dismissible fade show animated fadeIn" role="alert">
    <strong>Warning! </strong> SQL error.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>';
  }
}
else{
  echo '<div class="alert alert-warning alert-dismissible fade show animated fadeIn" role="alert">
  <strong>Warning! </strong> Empty fields.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  </div>';
}
  #echo 'ok';

?>
