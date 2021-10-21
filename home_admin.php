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
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 <script type="text/javascript">
   function wind_ref(){
     window.location.reload();
   }
 </script>
 <script type="text/javascript">
   //$('#sched_table').load(document.URL +  ' #sched_table');
   function stop_os(){
     var r = confirm("Are you sure you want to close scheduling?");
     if (r == true) {
         $.ajax({
           type: 'POST',
           url: 'includes/stop_os.inc.php',
           success:function(html){
             $('#close_os_status').html(html);
           }
         });
       }
   }
 </script>
 <script type="text/javascript">
   function open_sched(){
     var os_start = document.getElementById("os_start").value;
     var os_end = document.getElementById("os_end").value;
     var cur_num = document.getElementById("cur_num").value;

       $.ajax({
         type: 'POST',
         url: 'includes/open_sched.inc.php',
         data: { cur_num: cur_num, os_start: os_start, os_end: os_end },
         success:function(html){
           //location.reload();
           $('#os_status').html(html);
         }
       });

   }
 </script>
 <script type="text/javascript">
   $(document).ready(function(){
     $('#cur_num').on('change',function(){
       var curriculum_num = $(this).val();
       if(curriculum_num){
         $.ajax({
           type: 'POST',
           url: 'includes/load_sections.inc.php',
           data: 'curriculum_num=' + curriculum_num,
           success:function(html){
             $('#sections').html(html);
           }
         });
         $.ajax({
           type: 'POST',
           url: 'includes/load_sections2.inc.php',
           data: 'curriculum_num=' + curriculum_num,
           success:function(html){
             $('#sections2').html(html);
           }
         });
         $.ajax({
           type: 'POST',
           url: 'includes/load_sections3.inc.php',
           data: 'curriculum_num=' + curriculum_num,
           success:function(html){
             $('#sections3').html(html);
           }
         });
         $.ajax({
           type: 'POST',
           url: 'includes/load_sections4.inc.php',
           data: 'curriculum_num=' + curriculum_num,
           success:function(html){
             $('#sections4').html(html);
           }
         });
         $.ajax({
           type: 'POST',
           url: 'includes/alldept_progress.inc.php',
           data: 'curriculum_num=' + curriculum_num,
           success:function(html){
             $('#alldept_progress').html(html);
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
    <a class="nav-link active" href="home_admin.php">Home</a>
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
    <a class="nav-link" href="faculty_admin.php">Faculty</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="export_admin.php">Export</a>
  </li>
  </ul>
  <div class="row">
    <div class="col-md-12"><br>
      <select class="custom-select mr-sm-2" name="cur_num" id="cur_num">
        <option disabled selected>Select Academic Year...</option>
        <?php echo $mysqlidb->school_yr(); ?>
      </select>
    <div class="col-md-12" name="alldept_progress" id="alldept_progress">
    </div><br>




    </div>
    <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-3" name="sections" id="sections"></div>
    <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-3" name="sections1" id="sections2"></div>
    <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-3" name="sections2" id="sections3"></div>
    <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-3" name="sections3" id="sections4"></div>

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
