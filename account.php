<?php
  session_start();
  if(isset($_SESSION['username'])){
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
    function change_pwd(){
      var r = confirm("Are you sure you want to change password?");
      if (r == true) {
        var cur_pwd = document.getElementById("cur_pwd").value;
        var new_pwd = document.getElementById("new_pwd").value;
        var rpt_pwd = document.getElementById("rpt_pwd").value;
          $.ajax({
            type: 'POST',
            url: 'includes/change_pwd.inc.php',
            data: { cur_pwd: cur_pwd, new_pwd: new_pwd, rpt_pwd: rpt_pwd },
            success:function(html){
              $('#pword_status').html(html);
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
  <?php
  if($_SESSION['level_access'] == 'admin'){
    include 'includes/headera.inc.php';
  }
  else if($_SESSION['level_access'] == 'dept_chair'){
    include 'includes/header.inc.php';
  }
  else if($_SESSION['level_access'] == 'dean'){
    echo '
    <!--Navbar -->
    <nav class="mb-1 navbar navbar-expand-lg navbar-dark blue-gradient">
      <a class="navbar-brand" href="home_dean.php">
        <img src="includes/sbulogo6.png" height="30" class="d-inline-block align-top"
          alt="mdb logo" >Schedule Management System</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-333"
        aria-controls="navbarSupportedContent-333" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent-333">
        <ul class="navbar-nav ml-auto nav-flex-icons">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-333" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-default"
              aria-labelledby="navbarDropdownMenuLink-333">
              <a class="dropdown-item" href="account.php">Account</a>
              <a class="dropdown-item" href="includes/logout.inc.php">Logout</a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!--/.Navbar -->
    ';
  }
  ?>
</head>

<body><br><br><br><br><br><br><br><br>

<div class="container shadow-lg p-3 mb-5 bg-white rounded">
  <h1 align="center">Account</h1><br>
  <div class="col-md-4">
    <div class="row">
    <h3 align="center">Username: <?php echo $_SESSION['username']; ?></h3>

    </div>

  <br>
  <div class="row">
  <h3 align="center">Password: ******</h3>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#pword">
      Change password
    </button>

    <!-- Modal -->
      <div class="modal fade" id="pword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Change Username</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="md-form mb-5">
                Current Password: <input type="password" id="cur_pwd" name="cur_pwd" class="form-control mb-4">
              </div>
              <div class="md-form mb-5">
                New Password: <input type="password" id="new_pwd" name="new_pwd" class="form-control mb-4">
              </div>
              <div class="md-form mb-5">
                Repeat Password: <input type="password" id="rpt_pwd" name="rpt_pwd" class="form-control mb-4">
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onClick="change_pwd();">Submit</button>
            </div>
            <div name="pword_status" id="pword_status"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
<?php
}
else{
  header("Location:./index.php");
  exit();
}
?>
