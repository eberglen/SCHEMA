<?php
  session_start();
 // if($_SESSION['level_access'] == 'admin'){
	  if ('a'=='a'){
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
   function create_user(){
     var r = confirm("Are you sure you want to create user?");
     if (r == true) {
       var dept = document.getElementById("dept").value;
       var uidUsers = document.getElementById("uidUsers").value;
       var pwd = document.getElementById("pwd").value;
       var pwd_rpt = document.getElementById("pwd_rpt").value;
       var position = document.getElementById("position").value;
         $.ajax({
           type: 'POST',
           url: 'includes/create_user.inc.php',
           data: { dept: dept, uidUsers: uidUsers, pwd: pwd, pwd_rpt: pwd_rpt, position: position },
           success:function(html){
             $('#cuser_status').html(html);
           }
         });
       }
   }
 </script>
 <script type="text/javascript">
   function add_dept(){
     var r = confirm("Are you sure you want to add department?");
     if (r == true) {
       var dept_id = document.getElementById("dept_id").value;
       var dept_desc = document.getElementById("dept_desc").value;
         $.ajax({
           type: 'POST',
           url: 'includes/add_dept.inc.php',
           data: { dept_id: dept_id, dept_desc: dept_desc },
           success:function(html){
             $('#dept_status').html(html);
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
    <a class="nav-link" href="subjects_admin.php">Subjects</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="departments_admin.php">Departments</a>
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
  <div class="row">
    <div class="col-md-6">
      <h3 align="center">Create User</h3><br>
      <div class="row">
        <div class="col-md-12">Department
          <select class="custom-select custom-select-md" name="dept" id="dept">
            <option selected disabled value="">Select Department</option>
            <?php echo $mysqlidb->dept_list(); ?>
          </select>
        </div>
      </div><br>
      <div class="row">
        <div class="col-md-6">Username
          <input type="text" class="form-control" placeholder="Username" name="uidUsers" id="uidUsers">
        </div>
        <div class="col-md-6">Position
          <select class="custom-select custom-select-md" name="position" id="position">
            <option selected disabled value="">Select Position</option>
            <option value="admin">Admin</option>
            <option value="dept_chair">Department Chairperson</option>
            <option value="dean">Dean</option>
          </select>
        </div>
      </div><br>
      <div class="row">
        <div class="col-md-6">Password
          <input type="password" class="form-control" placeholder="Password" name="pwd" id="pwd">
        </div>
        <div class="col-md-6">Repeat Password
          <input type="password" class="form-control" placeholder="Repeat Password" name="pwd_rpt" id="pwd_rpt">
        </div>
      </div><br>
      <div class="row">
        <div class="col-md-12" align="center">
          <button type="submit" class="btn btn-info" onClick="create_user()">Create</button>
        </div>
      </div>
      <div name="cuser_status" id="cuser_status"></div><br><br>
      <hr>
      <h3 align="center">Users</h3><br>
      <table class="table table-hover table-responsive-md">
        <thead>
          <tr>
            <th scope="col">Department</th>
            <th scope="col">Position</th>
            <th scope="col">Username</th>
            <th scope="col">Password</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody>

            <?php
            $sql = "SELECT *
                    FROM users
                    ;";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)) {
              $idUsers = $row['idUsers'];
              $uidUsers = $row['uidUsers'];
              $dept_id = $row['department_id'];
              $position = $row['level_access'];


              switch ($position) {
                  case "dept_chair":
                      $position = 'Department Chairperson';
                      break;
                  case "admin":
                      $position = 'Admin';
                      break;
                  case "dean":
                      $position = 'Dean';
                      break;
                  default:
                      $position = 'Error';
              }


              echo '
                  <tr>
                    <th scope="row">'.$dept_id.'</th>
                    <td>'.$position.'</td>
                    <td>'.$uidUsers.'</td>
                    <td><form action="includes/reset_pwd.inc.php" method="POST" onsubmit="return confirm(&quot Are you sure you want to reset password?&quot);">
                      <input type="hidden" name="idUsers" value="'.$idUsers.'"></input>
                      <button type="submit" class="btn btn-default btn-sm">Reset</button>
                    </form></td>
                    <td><form action="includes/delete_user.inc.php" method="POST" onsubmit="return confirm(&quot Are you sure you want to delete user?&quot);">
                      <input type="hidden" name="idUsers" value="'.$idUsers.'"></input>
                      <button type="submit" class="btn btn-danger px-3 waves-effect waves-light btn-sm"><i class="far fa-trash-alt"></i></button>
                    </form></td>
                  </tr>';
            }
            ?>
        </tbody>
      </table>
      <?php
      if (isset($_GET['error'])){
        if ($_GET['error'] == 'sqlerror'){
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>ERROR. </strong>SQL error.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>';
        }
      }
      else if (isset($_GET['success'])){
        if ($_GET['success'] == 'pwdreset'){
          echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Success! </strong>Passowrd has been set to <strong>sbu@2020</strong>.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>';
        }
        else if ($_GET['success'] == 'deluser'){
          echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Success! </strong>User <strong>deleted</strong> successfully.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>';
        }
      }
      ?>
    </div>
    <div class="col-md-6">
      <h3 align="center">Add Deparment</h3><br>
      <div class="row">
        <div class="col-md-4">Department ID
          <input type="text" class="form-control" placeholder="ICT" name="dept_id" id="dept_id">
        </div>
        <div class="col-md-8">Deparment Description
          <input type="text" class="form-control" placeholder="Information and Communication Technology" name="dept_desc" id="dept_desc">
        </div>
      </div><br>
      <div class="row">
        <div class="col-md-12" align="center">
          <button type="submit" class="btn btn-info" onClick="add_dept();">Add</button>
        </div>
      </div>
      <div name="dept_status" id="dept_status"><br><br></div>

      <hr>
        <h3 align="center">Departments</h3><br>
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">Department</th>
              <th scope="col">Department Description</th>
              <th scope="col">Edit</th>
              <th scope="col">Delete</th>
            </tr>
          </thead>
          <tbody>

              <?php
              $sql = "SELECT *
                      FROM department
                      ;";
              $result = mysqli_query($conn, $sql);
              while($row = mysqli_fetch_assoc($result)) {
                $dept_num = $row['dept_num'];
                $dept_id = $row['department_id'];
                $dept_desc = $row['department_desc'];


                echo '
                    <tr>
                      <th scope="row">'.$dept_id.'</th>
                      <td>'.$dept_desc.'</td>
                      <td>
                        <a href="" class="btn btn-primary btn-rounded mb-4 btn-sm" data-toggle="modal" data-target="#'.$dept_id.'"><i class="far fa-edit"></i></a>
                      </td>
                      <td><form action="includes/delete_dept.inc.php" method="POST" onsubmit="return confirm(&quot Are you sure you want to delete department? NOTE: All subjects under this department will also be DELETED.&quot);">
                        <input type="hidden" name="dept_id" value="'.$dept_id.'"></input>
                        <button type="submit" class="btn btn-danger px-3 waves-effect waves-light btn-sm"><i class="far fa-trash-alt"></i></button>
                      </form></td>
                    </tr>

                    <form action="includes/edit_dept.inc.php" method="POST">
                    <div class="modal fade" id="'.$dept_id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                      aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header text-center">
                            <h4 class="modal-title w-100 font-weight-bold">Edit</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body mx-3">
                            <div class="md-form mb-5">
                              Department ID: <input type="text" id="dept_idn" name="dept_idn" class="form-control mb-4" value="'.$dept_id.'">
                            </div>
                            <input type="hidden" id="dept_id" name="dept_id" value="'.$dept_num.'">
                            <div class="md-form mb-4">
                              Department Description: <input type="text" id="dept_desc" name="dept_desc" class="form-control mb-4" value="'.$dept_desc.'">
                            </div>

                          </div>
                          <div class="modal-footer d-flex justify-content-center">
                            <button type="submit" class="btn btn-default">Submit</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    </form>
                    ';
              }
              ?>
          </tbody>
        </table>
        <?php
        if (isset($_GET['error'])){
          if ($_GET['error'] == 'sqlerror'){
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>ERROR. </strong>SQL error.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';
          }
          else if ($_GET['error'] == 'emptyfields'){
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>ERROR. </strong>Empty fields.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';
          }
          else if ($_GET['error'] == 'dept_exists'){
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>ERROR. </strong>Department already exists.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';
          }
        }
        else if (isset($_GET['success'])){
          if ($_GET['success'] == 'deldept'){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success! </strong>Department and subjects under are <strong>deleted</strong> successfully.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';
          }
          else if ($_GET['success'] == 'editdept'){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success! </strong>Department name hase been <strong>updated</strong> successfully.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';
          }
        }
        ?>
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
