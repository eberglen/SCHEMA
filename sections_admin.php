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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script>
$(document).ready(function(){

 $(document).on('click', '.add', function(){
  var html = '';
  html += '<tr>';
  html += '<td><select name="item_unit[]" class="form-control item_unit"><option value="">Select Subject</option><?php echo $mysqlidb->subject_list(); ?></select></td>';
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
        error += "<p>Select Subject at "+count+" Row</p>";
        return false;
       }
       count = count + 1;
      });
      var form_data = $(this).serialize();
      if(error == '')
      {
       $.ajax({
        url:"includes/insert_section.inc.php",
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
<script type="text/javascript">
  //$('#sched_table').load(document.URL +  ' #sched_table');
  function add_sy(){
    var r = confirm("Are you sure you want to add the following?");
    if (r == true) {
      var sy = document.getElementById("sy").value;
      var sem = document.getElementById("sem").value;
        $.ajax({
          type: 'POST',
          url: 'includes/add_sy.inc.php',
          data: { sy: sy, sem: sem },
          success:function(html){
            $('#add_sy_status').html(html);
          }
        });
      }
  }
</script>
<script type="text/javascript">
  function wind_ref(){
    window.location.reload();
  }
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#sectlist').on('change',function(){
      var curnum = $(this).val();
      if(curnum){
        $.ajax({
          type: 'POST',
          url: 'includes/list_sect.inc.php',
          data: 'curnum=' + curnum,
          success:function(html){
            $('#sectsubj').html(html);
          }
        });
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
  <a class="nav-link active" href="sections_admin.php">Sections</a>
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
  <a class="nav-link" href="faculty_admin.php">Faculty</a>
</li>
<li class="nav-item">
  <a class="nav-link" href="export_admin.php">Export</a>
</li>
</ul>

  <div class="row animated fadeIn faster">
    <div class="col-md-3"><br>
      <h3 align="center">Add School Year/Semester</h3><br>
      <div class="row">
        <div class="col-md-12">School Year
          <input type="text" class="form-control" placeholder="2018-2019" name="sy" id="sy">
        </div>
      </div><br>
      <div class="row">
        <div class="col-md-12">Semester
          <select class="form-control" name="sem" id="sem">
            <option value="" selected disabled>Select Semester</option>
            <option value="1st Semester">1st Semester</option>
            <option value="2nd Semester">2nd Semester</option>
            <option value="Summer">Summer</option>
          </select>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-12">
          <button type="submit" class="btn btn-block btn-info" onClick="add_sy()">Add</button>
        </div>
      </div>
      <div name="add_sy_status" id="add_sy_status"><br><br></div>
      <h3 align="center">School Year/Semester</h3><br>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">School Year/Semester</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT *
                  FROM curriculum
                  ;";
          $result = mysqli_query($conn, $sql);
          while($row = mysqli_fetch_assoc($result)) {
            $cur_num = $row['curriculum_num'];
            $sy = $row['academic_year'];
            $sem = $row['semester'];

            echo '
            <tr>
              <th scope="row">'.$sy.' '.$sem.'</th>
              <td>
              <form action="includes/delete_cur.inc.php" method="POST" onsubmit="return confirm(&quot Are you sure you want to delete school year? NOTE: All sections under this school year and semester will also be DELETED.&quot);">
                <input type="hidden" name="cur_num" value="'.$cur_num.'"></input>
                <button type="submit" class="btn btn-danger px-3 waves-effect waves-light btn-sm"><i class="far fa-trash-alt"></i></button>
              </form>
              </td>
            </tr>
            ';

          }
          ?>

        </tbody>
      </table>
    </div>
    <div class="col-md-6">
      <div class="container"><br>
       <h3 align="center">Add Sections</h3>
       <br />
       <span id="error"></span>
       <form method="post" id="insert_form">
         <div class="row">
           <div class="col-md-4">
             <h5 align="center">Section</h5>
             <input type="text" class="form-control" placeholder="AIT4" name="section" id="section">
           </div>
           <div class="col-md-8">
             <h5 align="center">School Year/Semester</h5>
             <select class="form-control" name="semester" id="semester">
               <?php echo $mysqlidb->school_yr(); ?>
             </select>
           </div>
         </div>
        <div class="table-repsonsive">
         <table class="table table-bordered" id="item_table">
          <tr>
           <th style="width: 75%"><h4 style="text-align: center">Subjects</h4></th>
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
    <div class="col-md-3"><br>
      <h3 align="center">List of Sections</h3><br>
      <select class="custom-select custom-select-md" name="sectlist" id="sectlist">
        <option selected disabled>Select School Year/Semester</option>
        <?php echo $mysqlidb->school_yr(); ?>
      </select>
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
        if ($_GET['success'] == 'delsubj'){
          echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Success! </strong>Subject has been <strong>deleted</strong>.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>';
        }
      }
      ?>
      <div class="bd-example" name="sectsubj" id="sectsubj"></div>
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
