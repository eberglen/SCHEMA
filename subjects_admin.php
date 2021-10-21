<?php
  session_start();
  if($_SESSION['level_access'] == 'admin'){
  include 'includes/dbconn.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <title>ScheduLe Management</title>
 <link rel="shortcut icon" href="includes/sbulogo6.ico"/>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 <meta http-equiv="x-ua-compatible" content="ie=edge">
 <!-- Font Awesome -->
 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
 <!-- Bootstrap core CSS -->
 <link href="mdbootstrap/css/bootstrap.min.css" rel="stylesheet">
 <!-- Material Design Bootstrap -->
 <link href="mdbootstrap/css/mdb.min.css" rel="stylesheet">
 <!-- Your custom styles (optional) -->
 <link href="mdbootstrap/css/style.css" rel="stylesheet">
 <script type="text/javascript" src="mdbootstrap/js/jquery-3.4.1.min.js"></script>
 <!-- Bootstrap tooltips -->
 <script type="text/javascript" src="mdbootstrap/js/popper.min.js"></script>
 <!-- Bootstrap core JavaScript -->
 <script type="text/javascript" src="mdbootstrap/js/bootstrap.min.js"></script>
 <!-- MDB core JavaScript -->
 <script type="text/javascript" src="mdbootstrap/js/mdb.min.js"></script>
 <script type="text/javascript">
   function wind_ref(){
     window.location.reload();
   }
 </script>
 <script type="text/javascript">
   function add_subj(){
     var r = confirm("Are you sure you want to add the following?");
     if (r == true) {
       var subj_code = document.getElementById("subj_code").value;
       var subj_desc = document.getElementById("subj_desc").value;
       var dept = document.getElementById("dept").value;
         $.ajax({
           type: 'POST',
           url: 'includes/add_subj.inc.php',
           data: { subj_code: subj_code, subj_desc: subj_desc, dept: dept },
           success:function(html){
             $('#add_subj_status').html(html);
           }
         });
       }
   }
 </script>

<?php include 'includes/headera.inc.php'; ?>
</head>
<body>

<div class="shadow-lg p-3 mb-5 bg-white rounded">
  <ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link" href="home_admin.php">Home</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="sections_admin.php">Sections</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="subjects_admin.php">Subjects</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="departments_admin.php">Departments</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="rooms_admin.php">Rooms</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="faculty_admin.php">Faculty</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="export_admin.php">Export</a>
  </li>
</ul><br>
<div class="container col-md-12 animated fadeIn faster">
  <div class="row">
    <?php
    $sql = "SELECT department.department_id as dept_id, department_desc
            FROM subjects, department
            WHERE department.department_id=subjects.department_id
            GROUP BY dept_id;";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)) {
      $dept_id = $row['dept_id'];
      $dept_desc = $row['department_desc'];
      echo '<div class="col-md-6">
      <h3 align="center">'.$dept_desc.'</h3><br>
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">Subject</th>
            <th scope="col">Subject Description</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody>';
      $sql2 = "SELECT subject_code, subject_desc
              FROM subjects
              WHERE department_id='$dept_id';";
      $result2 = mysqli_query($conn, $sql2);
      while($row2 = mysqli_fetch_assoc($result2)) {
        $subj_code = $row2['subject_code'];
        $subj_desc = $row2['subject_desc'];
        echo '
            <tr>
              <th scope="row">'.$subj_code.'</th>
              <td>'.$subj_desc.'</td>
              <td><form action="includes/delete_subj.inc.php" method="POST" onsubmit="return confirm(&quot Are you sure you want to delete subject? &quot);">
                <input type="hidden" name="subj_code" value="'.$subj_code.'"></input>
                <button type="submit" class="btn btn-danger px-3 waves-effect waves-light btn-sm"><i class="far fa-trash-alt"></i></button>
              </form></td>
            </tr>';
      }
      echo '  </tbody>
      </table>
      </div>';
    }
    ?>
  </div>
  <hr>
  <div class="row">
    <div class="container">
        <h3 align="center">Add Subjects</h3><br>
        <div class="row">
          <div class="col-md-12">Subject Code
            <input type="text" class="form-control" placeholder="ICT01" name="subj_code" id="subj_code">
          </div>
        </div><br>
        <div class="row">
          <div class="col-md-12">Subject Description
            <input type="text" class="form-control" placeholder="Fundamentals of Computer Software and Applications" name="subj_desc" id="subj_desc">
          </div>
        </div><br>
        <div class="row">
          <div class="col-md-12">Department
            <select class="custom-select custom-select-md" name="dept" id="dept">
              <option selected disabled>Select Department</option>
              <?php echo $mysqlidb->dept_list(); ?>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12"><br>
            <button type="submit" class="btn btn-block btn-info" onClick="add_subj();">Add</button>
          </div>
        </div>
        <div name="add_subj_status" id="add_subj_status"><br><br></div>
    </div>
  </div>
</div>
</body>
<?php
}
else if($_SESSION['level_access'] == 'dept_chair'){
  header("Location:./schedule.php");
  exit();
}
else if($_SESSION['level_access'] == 'dean'){
  header("Location:./home_dean.php");
  exit();
}
else{
  header("Location:./index.php");
  exit();
}
?>
