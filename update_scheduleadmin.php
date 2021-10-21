<?php
  session_start();
  if($_SESSION['level_access'] == 'admin'){
  include 'includes/dbconn.inc.php';
  $cn = $_POST['cn'];
  $yls = $_POST['yls'];
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
  <?php include 'includes/headera.inc.php'; ?>
  <script type="text/javascript">
    function wind_ref(){
      window.location.reload();
    }
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#subject').on('change',function(){
        var schedule_num = $(this).val();
        if(schedule_num){
          $.ajax({
            type: 'POST',
            url: 'includes/load_start.inc.php',
            data: 'schedule_num=' + schedule_num,
            success:function(html){
              $('#time_start').html(html);
            }
          });
        }
      });
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#subject').on('change',function(){
        var schedule_num = $(this).val();
        if(schedule_num){
          $.ajax({
            type: 'POST',
            url: 'includes/load_end.inc.php',
            data: 'schedule_num=' + schedule_num,
            success:function(html){
              $('#time_end').html(html);
            }
          });
        }
      });
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#subject').on('change',function(){
        var schedule_num = $(this).val();
        if(schedule_num){
          $.ajax({
            type: 'POST',
            url: 'includes/load_days.inc.php',
            data: 'schedule_num=' + schedule_num,
            success:function(html){
              $('#checkboxes').html(html);
            }
          });
        }
      });
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#subject').on('change',function(){
        var schedule_num = $(this).val();
        if(schedule_num){
          $.ajax({
            type: 'POST',
            url: 'includes/load_rooms.inc.php',
            data: 'schedule_num=' + schedule_num,
            success:function(html){
              $('#rooms').html(html);
            }
          });
        }
      });
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#subject').on('change',function(){
        var schedule_num = $(this).val();
        if(schedule_num){
          $.ajax({
            type: 'POST',
            url: 'includes/load_professors.inc.php',
            data: 'schedule_num=' + schedule_num,
            success:function(html){
              $('#professors').html(html);
            }
          });
        }
      });
    });
  </script>
  <script type="text/javascript">
      $(document).ready(function() {
          $('#checkboxes').on('change',function(){
              var favorite = [];
              var sched_num = document.getElementById("subject").value;
              var start = document.getElementById("time_start").value;
              var end = document.getElementById("time_end").value;
              $.each($("input[name='day']:checked"), function(){
                  favorite.push($(this).val());
              });
              $.ajax({
                type: 'POST',
                url: 'includes/new_load_rooms.inc.php',
                data: { day: favorite, sched_num: sched_num, start: start, end: end },
                success:function(html){
                  $('#rooms').html(html);
                }
              });
              $.ajax({
                type: 'POST',
                url: 'includes/new_load_profs.inc.php',
                data: { day: favorite, sched_num: sched_num, start: start, end: end },
                success:function(html){
                  $('#professors').html(html);
                }
              });
          });
      });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#time_start').on('change',function(){
        var start = $(this).val();
        var favorite = [];
        var sched_num = document.getElementById("subject").value;
        var end = document.getElementById("time_end").value;
        $.each($("input[name='day']:checked"), function(){
            favorite.push($(this).val());
        });
        if(start){
          $.ajax({
            type: 'POST',
            url: 'includes/new_load_rooms.inc.php',
            data: { day: favorite, sched_num: sched_num, start: start, end: end },
            success:function(html){
              $('#rooms').html(html);
            }
          });
          $.ajax({
            type: 'POST',
            url: 'includes/new_load_profs.inc.php',
            data: { day: favorite, sched_num: sched_num, start: start, end: end },
            success:function(html){
              $('#professors').html(html);
            }
          });
        }
      });
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#time_end').on('change',function(){
        var end = $(this).val();
        var favorite = [];
        var sched_num = document.getElementById("subject").value;
        var start = document.getElementById("time_start").value;
        $.each($("input[name='day']:checked"), function(){
            favorite.push($(this).val());
        });
        if(end){
          $.ajax({
            type: 'POST',
            url: 'includes/new_load_rooms.inc.php',
            data: { day: favorite, sched_num: sched_num, start: start, end: end },
            success:function(html){
              $('#rooms').html(html);
            }
          });
          $.ajax({
            type: 'POST',
            url: 'includes/new_load_profs.inc.php',
            data: { day: favorite, sched_num: sched_num, start: start, end: end },
            success:function(html){
              $('#professors').html(html);
            }
          });
        }
      });
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#subject').on('change',function(){
        var schedule_num = $(this).val();
        if(schedule_num){
          $.ajax({
            type: 'POST',
            url: 'includes/button.inc.php',
            data: 'schedule_num=' + schedule_num,
            success:function(html){
              $('#addupdate').html(html);
            }
          });
        }
      });
    });
    </script>
  <script type="text/javascript">
    //$('#sched_table').load(document.URL +  ' #sched_table');
    function submit_add(){
      var favorite = [];
      var sched_num = document.getElementById("subject").value;
      var start = document.getElementById("time_start").value;
      var end = document.getElementById("time_end").value;
      var profs = document.getElementById("professors").value;
      var rooms = document.getElementById("rooms").value;
      $.each($("input[name='day']:checked"), function(){
          favorite.push($(this).val());
      });
        $.ajax({
          type: 'POST',
          url: 'includes/submit_add.inc.php',
          data: { day: favorite, sched_num: sched_num, start: start, end: end, profs: profs, rooms: rooms },
          async: false,
          success:function(html){
            $('#status').html(html);
          }
        });

    }
  </script>

</head>

<body>

<div class="shadow-lg p-3 mb-5 bg-white rounded animated fadeIn" style=height:726px;>
  <div class="row">
    <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-8 overflow-auto" style=height:745px; name="sched_table" id="sched_table">
      <a class="nav-link active" href="home_admin.php">Back</a>
      <?php include 'display_schedule.php'; ?>
    </div>
    <div class="shadow-sm p-3 mb-5 bg-white rounded col-md-4">
        <H2>Set Schedule</H2><br>
        <h4>Subjects</h4>
        <select class="custom-select mr-sm-2" name="subject" id="subject">
          <option selected disabled="">Select Subject...</option>
          <?php
          $query = "SELECT *
                    FROM schedule
                    WHERE curriculum_num=".$cn."
                    AND year_level_section='".$yls."';";
          $data = '';
          $result = mysqli_query($conn, $query);
          while($row = mysqli_fetch_assoc($result)){
            if (($row['s_start_at'] == 0) && ($row['employee_num'] == 0) && ($row['room_num'] == 0)){
              echo '<option value="'.$row['schedule_num'].'">'.$row['subject_code'].' (Blank)</option>';
            }
            else if (($row['s_start_at'] == 0) || ($row['employee_num'] == 0) || ($row['room_num'] == 0)){
              echo '<option style="color:orange" value="'.$row['schedule_num'].'">'.$row['subject_code'].' (Incomplete)</option>';
            }
            else{
              echo '<option style="color:green" value="'.$row['schedule_num'].'">'.$row['subject_code'].' (Complete)</option>';
            }
          }
          ?>
        </select><br><br>

        <div class="row">
          <div class="col-md-6">
            <h4>Time Start</h4>
            <select class="custom-select mr-sm-2" name="time_start" id="time_start">

            </select><br><br>
          </div>
          <div class="col-md-6">
            <h4>Time End</h4>
            <select class="custom-select mr-sm-2" name="time_end" id="time_end">

            </select><br><br>
          </div>
        </div>

        <h4>Day(s)</h4>
        <div name="checkboxes" id="checkboxes">
          <div class="row">
            <div class="col-md-4">
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" value="&quot;M&quot;" name="day" id="M" disabled>
                <label class="custom-control-label" for="M">Monday</label>
              </div>
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" value="&quot;T&quot;" name="day" id="T" disabled>
                <label class="custom-control-label" for="T">Tuesday</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" value="&quot;W&quot;" name="day" id="W" disabled>
                <label class="custom-control-label" for="W">Wednesday</label>
              </div>
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" value="&quot;TH&quot;" name="day" id="TH" disabled>
                <label class="custom-control-label" for="TH">Thursday</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" value="&quot;F&quot;" name="day" id="F" disabled>
                <label class="custom-control-label" for="F">Friday</label>
              </div>
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" value="&quot;S&quot;" name="day" id="S" disabled>
                <label class="custom-control-label" for="S">Saturday</label>
              </div>
            </div>
          </div>
        </div><br>
        <h4>Rooms</h4>
        <select class="custom-select mr-sm-2" name="rooms" id="rooms">

        </select><br><br>
        <h4>Professors</h4>
        <select class="custom-select mr-sm-2" name="professors" id="professors">

        </select><br><br>
        <div id="addupdate" name="addupdate">
          <button type="button" class="btn btn-info btn-block" disabled>Add/Update</button>
        </div>

        <div id="status" name="status">
        </div>

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
