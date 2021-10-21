<?php
include 'dbconn.inc.php';
if(isset($_POST['cur_num']) && !empty($_POST['os_start']) && !empty($_POST['os_end'])){
  $cur_num = $_POST['cur_num'];
  $os_start = $_POST['os_start'];
  $os_end = $_POST['os_end'];

  if ($os_start > $os_end){
    echo '<div class="alert alert-warning alert-dismissible fade show animated fadeIn" role="alert">
    <strong>Warning: </strong>Start date cannot be greater than end date.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>';
  }
  else{
    $sql = "TRUNCATE TABLE open_sched; INSERT INTO open_sched VALUES (".$cur_num.",'".$os_start."','".$os_end."')";
    $result = mysqli_multi_query($conn, $sql);
    if($result){
      echo '<div class="alert alert-success alert-dismissible fade show animated fadeIn" role="alert">
      <strong>Success! </strong> Scheduling will now <strong>open</strong>.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick="wind_ref()">
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
}
else{
  echo '<div class="alert alert-warning alert-dismissible fade show animated fadeIn" role="alert">
  <strong>Warning: </strong>Empty fields.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  </div>';
}

?>
