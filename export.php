<?php
  session_start();
  if($_SESSION['level_access'] == 'dept_chair'){
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
    $(document).ready(function(){
      $('#cur_num').on('change',function(){
        var curriculum_num = $(this).val();
        if(curriculum_num){
          $.ajax({
            type: 'POST',
            url: 'includes/export.inc.php',
            data: 'curriculum_num=' + curriculum_num,
            success:function(html){
              $('#export').html(html);
            }
          });
        }
      });
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#cur_num2').on('change',function(){
        var curriculum_num = $(this).val();
        if(curriculum_num){
          $.ajax({
            type: 'POST',
            url: 'includes/export2.inc.php',
            data: 'curriculum_num=' + curriculum_num,
            success:function(html){
              $('#export').html(html);
            }
          });
        }
      });
    });
  </script>
  <script>
  function fnExcelReport()
 {
  var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
  var textRange; var j=0;
  tab = document.getElementById('exportTable'); // id of table

  for(j = 0 ; j < tab.rows.length ; j++)
  {
      tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
      //tab_text=tab_text+"</tr>";
  }

  tab_text=tab_text+"</table>";
  tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
  tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
  tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

  var ua = window.navigator.userAgent;
  var msie = ua.indexOf("MSIE ");

  if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
  {
      txtArea1.document.open("txt/html","replace");
      txtArea1.document.write(tab_text);
      txtArea1.document.close();
      txtArea1.focus();
      sa=txtArea1.document.execCommand("SaveAs",true,"registered members.xlsx");
  }
  else                 //other browser not tested on IE 11
      sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

  return (sa);
 }
  </script>
  <?php include 'includes/header.inc.php'; ?>
</head>

<body>

<div class="shadow-lg p-3 mb-5 bg-white rounded">
<ul class="nav nav-tabs">
<li class="nav-item">
  <a class="nav-link" href="schedule.php">Schedule</a>
</li>
<li class="nav-item">
  <a class="nav-link active" href="export.php">Export</a>
</li>
</ul><br>
<div class="container col-md-12 animated fadeIn faster">
  <div class="row">
    <div class="col-md-6">
      <select class="custom-select mr-sm-2" name="cur_num" id="cur_num">
        <option disabled selected>Select Academic Year...</option>
        <?php echo $mysqlidb->school_yr(); ?>
      </select>
    </div>
    <!--<div class="col-md-6">
      <select class="custom-select mr-sm-2" name="cur_num2" id="cur_num2">
        <option disabled selected>Select Academic Year...</option>
        <?php //echo $mysqlidb->school_yr(); ?>
      </select>
    </div>
  </div>-->
  <div class="col-md-12" name="export" id="export">
  </div>
  <iframe id="txtArea1" style="display:none"></iframe>
</div>
</div>
</body>
<?php
}
else if($_SESSION['level_access'] == 'admin'){
  header("Location:./home_admin.php");
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
