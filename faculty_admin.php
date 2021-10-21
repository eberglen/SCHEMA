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
 <script>
$(document).ready(function(){

$(document).on('click', '.add', function(){
 var html = '';
 html += '<tr>';
 html += '<td><select name="item_unit[]" class="form-control item_unit"><option value="">Select Department</option><?php echo $mysqlidb->dept_list(); ?></select></td>';
 html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove">-</button></td></tr>';
 $('#item_table').append(html);
});

$(document).on('click', '.remove', function(){
 $(this).closest('tr').remove();
});

  $('#insert_form').on('submit', function(event){

     event.preventDefault();
     var error = '';
     var r = confirm("Are you sure you want to add the following?");
     if (r == true) {
     $('.item_unit').each(function(){
      var count = 1;
      if($(this).val() == '')
      {
       error += "<p>Select Department at "+count+" Row</p>";
       return false;
      }
      count = count + 1;
     });
     var form_data = $(this).serialize();
     if(error == '')
     {
      $.ajax({
       url:"includes/add_faculty.inc.php",
       method:"POST",
       data: form_data,
       //success:function(data)
       success:function(html)
       {
         $('#error').html(html);
        //if(data == 'ok')
        //{
         //$('#item_table').find("tr:gt(0)").remove();
         //$('#error').html('<div class="alert alert-success">Section Details Saved</div>');
        //}
       }
      });
     }
     else
     {
      $('#error').html('<div class="alert alert-danger">'+error+'</div>');
     }
   }
 });

});
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
    <a class="nav-link" href="departments_admin.php">Departments</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="rooms_admin.php">Rooms</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="faculty_admin.php">Faculty</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="export_admin.php">Export</a>
  </li>
</ul><br>
<div class="row">
  <div class="container col-md-8 animated fadeIn faster">
    <?php
    $sql = "SELECT employee.employee_num as employee_num, first_name, middle_name, last_name, GROUP_CONCAT(department_desc) as dept_desc
            FROM employee, employee_departments, department
            WHERE employee.employee_num = employee_departments.employee_num
            AND employee_departments.department_id = department.department_id
            GROUP BY employee.employee_num
            ;";
    $result = mysqli_query($conn, $sql);
    echo '<table class="table table-hover table-responsive-md">
            <thead>
              <tr>
                <th scope="col">First Name</th>
                <th scope="col">Middle Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Department</th>
                <th scope="col">Delete</th>
              </tr>
            </thead>
            <tbody>';
    while($row = mysqli_fetch_assoc($result)) {
      $fn = $row['first_name'];
      $mn = $row['middle_name'];
      $ln = $row['last_name'];
      $dept_desc = $row['dept_desc'];
      $en = $row['employee_num'];

      echo '
      <tr>
        <th scope="row">'.$fn.'</th>
        <td>'.$mn.'</td>
        <td>'.$ln.'</td>
        <td>'.$dept_desc.'</td>
        <td><form action="includes/delete_emp.inc.php" method="POST" onsubmit="return confirm(&quot Are you sure you want to delete faculty?&quot);">
          <input type="hidden" name="emp_num" value="'.$en.'"></input>
          <button type="submit" class="btn btn-danger px-3 waves-effect waves-light btn-sm"><i class="far fa-trash-alt"></i></button>
        </form></td>
      </tr>
      ';
    }
    echo '</tbody>
        </table>';
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
      if ($_GET['success'] == 'delfaculty'){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>Faculty has been <strong>deleted</strong>.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>';
      }
    }
    ?>
  </div>
  <div class="container col-md-4">
    <h3 align="center">Add Faculty</h3>
    <br />
    <span id="error"></span>
    <form method="post" id="insert_form">
      <div class="row">
        <div class="col-md-4">
          <h5 align="center">First Name</h5>
          <input type="text" class="form-control" placeholder="First Name" name="fn" id="fn">
        </div>
        <div class="col-md-4">
          <h5 align="center">Middle Name</h5>
          <input type="text" class="form-control" placeholder="Middle Name" name="mn" id="mn">
        </div>
        <div class="col-md-4">
          <h5 align="center">Last Name</h5>
          <input type="text" class="form-control" placeholder="Last Name" name="ln" id="ln">
        </div>
      </div>
     <div class="table-repsonsive">
      <table class="table table-bordered" id="item_table">
       <tr>
        <th style="width: 75%"><h4 style="text-align: center">Department</h4></th>
        <th style="width: 25%"><button type="button" name="add" class="btn btn-success btn-sm add">+</button></th>
       </tr>
      </table>
      <div align="center">
       <input type="submit" name="submit" class="btn btn-info" value="Insert">
      </div>
     </div>
    </form>
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
