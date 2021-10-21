<?php
include 'dbconn.inc.php';

if (!empty($_POST['item_unit']) && !empty($_POST['section']) && !empty($_POST['semester'])){
  $section = $_POST['section'];
  $semester = $_POST['semester'];
  $subjects = $_POST['item_unit'];
  $sql = '';
  foreach ($subjects as $value){
    $sql = $sql."('".$section."','".$value."',".$semester."),";
    #echo $value;
  }
  $sql = substr($sql,0,-1);
  $sql = "INSERT INTO schedule (year_level_section, subject_code, curriculum_num) VALUES ".$sql.";";
  $result = $mysqlidb->sql_insert_update($sql);
  if ($result){
    echo '<div class="alert alert-success alert-dismissible fade show animated fadeIn" role="alert">
    <strong>Success! </strong> Record updated successfully.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
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
