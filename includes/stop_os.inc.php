<?php
include 'dbconn.inc.php';

    $sql = "TRUNCATE TABLE open_sched;";
    $result = mysqli_query($conn, $sql);
    if($result){
      echo '<div class="alert alert-success alert-dismissible fade show animated fadeIn" role="alert">
      <strong>Success! </strong> Scheduling is now <strong>closed</strong>.
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

?>
