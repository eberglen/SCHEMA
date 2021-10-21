<?php
if (!empty($_POST['dept_id']) && !empty($_POST['dept_desc'])){
  require 'dbconn.inc.php';
  $dept_id = $_POST['dept_id'];
  $dept_desc = $_POST['dept_desc'];


  $sql = "SELECT *
          FROM department
          WHERE department_id = '$dept_id'
          OR department_desc = '$dept_desc'
          ;";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>ERROR. </strong>Department already exists.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>';
  }
  else{
    $sql = "INSERT INTO department (department_id, department_desc) VALUES (?,?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>ERROR. </strong>SQL error.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
      </div>';
    }
    else{
      mysqli_stmt_bind_param($stmt, "ss", $dept_id, $dept_desc);
      mysqli_stmt_execute($stmt);
      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Success! </strong>Department added successfully.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick="wind_ref()">
      <span aria-hidden="true">&times;</span>
      </button>
      </div>';
    }
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
