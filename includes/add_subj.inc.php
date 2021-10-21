<?php
include 'dbconn.inc.php';
if (!empty($_POST['subj_code']) && !empty($_POST['subj_desc']) && !empty($_POST['dept'])){
  $subj_code = $_POST['subj_code'];
  $subj_desc = $_POST['subj_desc'];
  $dept = $_POST['dept'];
  $query = "INSERT INTO subjects VALUES ('".$subj_code."','".$subj_desc."','".$dept."');";
  $result = $mysqlidb->sql_insert_update($query);
  if ($result){
    echo '<div class="alert alert-success alert-dismissible fade show animated fadeIn" role="alert">
    <strong>Success! </strong> Record updated successfully.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick="wind_ref()">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>';
  }
  else{
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Warning! </strong> SQL error.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>';
  }
}
else{
  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Warning: </strong>Empty fields.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  </div>';
}

?>
