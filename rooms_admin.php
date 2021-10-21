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
   function add_rooms(){
     var r = confirm("Are you sure you want to add room?");
     if (r == true) {
       var favorite = [];
       var room_num = document.getElementById("room_num").value;
       var type = document.getElementById("type").value;
       var a_start = document.getElementById("a_start").value;
       var a_end = document.getElementById("a_end").value;
       $.each($("input[name='day']:checked"), function(){
           favorite.push($(this).val());
       });
         $.ajax({
           type: 'POST',
           url: 'includes/add_rooms.inc.php',
           data: { day: favorite, room_num: room_num, type: type, a_start: a_start, a_end: a_end },
           success:function(html){
             $('#add_rooms_status').html(html);
           }
         });
       }
   }
 </script>
 <script type="text/javascript">
   function edit_rooms(){
     var r = confirm("Are you sure you want to add room?");
     if (r == true) {
       var favorite = [];
       var room_num = document.getElementById("room_num").value;
       var type = document.getElementById("type").value;
       var a_start = document.getElementById("a_start").value;
       var a_end = document.getElementById("a_end").value;
       $.each($("input[name='days']:checked"), function(){
           favorite.push($(this).val());
       });
         $.ajax({
           type: 'POST',
           url: 'includes/add_rooms.inc.php',
           data: { day: favorite, room_num: room_num, type: type, a_start: a_start, a_end: a_end },
           success:function(html){
             $('#add_rooms_status').html(html);
           }
         });
       }
   }
 </script>
 <script type="text/javascript">
   $(document).ready(function(){
     $('#q').on('input',function(){
       var q = $(this).val();
       if(q){
         $.ajax({
           type: 'POST',
           url: 'includes/search.inc.php',
           data: 'q=' + q,
           success:function(html){
             $('#q_results').html(html);
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
    <a class="nav-link" href="sections_admin.php">Sections</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="subjects_admin.php">Subjects</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="departments_admin.php">Departments</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="rooms_admin.php">Rooms</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="faculty_admin.php">Faculty</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="export_admin.php">Export</a>
  </li>
</ul><br>
<div class="container col-md-12">
  <div class="col-md-6">
    <form class="form-inline active-pink-3 active-pink-4">
    <i class="fas fa-search" aria-hidden="true"></i>
    <input class="form-control form-control-sm ml-3 w-75" name="q" id="q" type="text" placeholder="Search"
      aria-label="Search">
    </form><br><br>
  </div>

  <div class="row" name="q_results" id="q_results">

    <?php
    $sql = 'SELECT room.room_num as room_num, TIME_FORMAT(avail_start, "%h:%i %p") as avail_start, TIME_FORMAT(avail_end, "%h:%i %p") as avail_end, room_class, GROUP_CONCAT(day_code) as day
            FROM room, room_days
            WHERE room.room_num = room_days.room_num
            GROUP BY room.room_num
            ORDER BY room.room_num
            ;';
    $result = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($result);
    $i = 0;
    $num_row = floor($num_rows / 2);
    while($row = mysqli_fetch_assoc($result)) {
      $room_num = $row['room_num'];
      $a_start = $row['avail_start'];
      $a_end = $row['avail_end'];
      $type = $row['room_class'];
      $day = $row['day'];
      if ($i==0 || $i==$num_row){
        echo '<div class="col-md-6">
        <table class="table table-hover table-responsive-md">
          <thead>
            <tr>
              <th scope="col">Room Number</th>
              <th scope="col">Availability</th>
              <th scope="col">Type</th>';
              //<th scope="col">Edit</th>
              echo '
              <th scope="col">Delete</th>
            </tr>
          </thead>
          <tbody>';
      }
      echo '
          <tr>
            <th scope="row">'.$room_num.'</th>
            <td>'.$a_start.'-'.$a_end.' ('.$day.')</td>
            <td>'.$type.'</td>';
            //<td>
              //<a href="" class="btn btn-primary btn-rounded mb-4 btn-sm" data-toggle="modal" data-target="#AV'.$room_num.'E"><i class="far fa-edit"></i></a>
            //</td>
            echo '
            <td><form action="includes/delete_room.inc.php" method="POST" onsubmit="return confirm(&quot Are you sure you want to delete room?&quot);">
              <input type="hidden" name="room_num" value="'.$room_num.'"></input>
              <button type="submit" class="btn btn-danger px-3 waves-effect waves-light btn-sm"><i class="far fa-trash-alt"></i></button>
            </form></td>
          </tr>';

          /*<form action="includes/edit_dept.inc.php" method="POST">
          <div class="modal fade" id="AV'.$room_num.'E" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
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
                  <div class="md-form mb-5" align="center">
                  '.$room_num.' ('.$type.')
                  </div>
                  <div class="md-form mb-4">
                  <div class="row">
                    <div class="col-md-6">Availability Start
                      <input type="time" class="form-control" name="a_start" id="a_start">
                    </div>
                    <div class="col-md-6">Availability End
                      <input type="time" class="form-control" name="a_end" id="a_end">
                    </div>
                  </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                  <button type="submit" class="btn btn-default">Submit</button>
                </div>
              </div>
            </div>
          </div>
          </form>
          ';*/



      $i = $i + 1;
      if ($i==$num_row || $i==$num_rows){
        echo '</tbody>
      </table>
      </div>';
      }

    }
    ?>
  </div>
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
    if ($_GET['success'] == 'delroom'){
      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Success! </strong>Room has been <strong>deleted</strong>.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
      </div>';
    }
  }
  ?>
  <hr>
  <div class="container col-md-6">
      <h3 align="center">Add Rooms</h3><br>
      <div class="row">
        <div class="col-md-6">Room Number
          <input type="text" class="form-control" placeholder="34A" name="room_num" id="room_num">
        </div>
        <div class="col-md-6">Type
          <select class="custom-select custom-select-md" name="type" id="type">
            <option selected disabled value="">Select Type</option>
            <option value="Lecture">Lecture</option>
            <option value="Laboratory">Laboratory</option>
          </select>
        </div>
      </div><br>
      <div class="row">
        <div class="col-md-6">Availability Start
          <input type="time" class="form-control" name="a_start" id="a_start">
        </div>
        <div class="col-md-6">Availability End
          <input type="time" class="form-control" name="a_end" id="a_end">
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" value="&quot;M&quot;" name="day" id="M">
            <label class="custom-control-label" for="M">Monday</label>
          </div>
          <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" value="&quot;T&quot;" name="day" id="T">
            <label class="custom-control-label" for="T">Tuesday</label>
          </div>
        </div>
        <div class="col-md-4">
          <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" value="&quot;W&quot;" name="day" id="W">
            <label class="custom-control-label" for="W">Wednesday</label>
          </div>
          <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" value="&quot;TH&quot;" name="day" id="TH">
            <label class="custom-control-label" for="TH">Thursday</label>
          </div>
        </div>
        <div class="col-md-4">
          <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" value="&quot;F&quot;" name="day" id="F">
            <label class="custom-control-label" for="F">Friday</label>
          </div>
          <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" value="&quot;S&quot;" name="day" id="S">
            <label class="custom-control-label" for="S">Saturday</label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12"><br>
          <button type="submit" class="btn btn-block btn-info" onClick="add_rooms()">Add</button>
        </div>
      </div>
      <div name="add_rooms_status" id="add_rooms_status"><br><br></div>
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
