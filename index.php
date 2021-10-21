<!doctype html>
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
    <style>
        body{
            margin: 0;
            padding: 0;
        }
        body:before{
            content: '';
            position: fixed;
            width: 100vw;
            height: 100vh;
            background-image: url("includes/landingpage.jpg");
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            -webkit-filter: blur(30px);
            -moz-filter: blur(30px);
            -o-filter: blur(30px);
            -ms-filter: blur(30px);
            filter: blur(30px);
        }
        .contact-form
        {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            width: 400px;
            height: 500px;
            padding: 80px 40px;
            box-sizing: border-box;
            background: rgba(0,0,0,.5);
        }
        .avatar {
            position: absolute;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            top: calc(-80px/2);
            left: calc(50% - 40px);
        }
        .contact-form h2 {
            margin: 0;
            padding: 0 0 20px;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
        }
        .contact-form p
        {
            margin: 0;
            padding: 0;
            color: #fff;
        }
        .contact-form input
        {
            width: 100%;
            margin-bottom: 20px;
        }
        .contact-form input[type="text"],
        .contact-form input[type="password"]
        {
            border: none;
            border-bottom: 1px solid #fff;
            background: transparent;
            outline: none;
            height: 40px;
            color: #fff;
            font-size: 16px;
        }
        .contact-form input[type="submit"] {
            height: 30px;
            color: #fff;
            font-size: 15px;
            background: red;
            cursor: pointer;
            border-radius: 25px;
            border: none;
            outline: none;
            margin-top: 15%;
        }
        .contact-form a
        {
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
        }
        input[type="checkbox"] {
            width: 20%;
        }
    </style>
</head>
<body>
    <div class="contact-form animated fadeIn">
      <h3><p class="text-white">SCHEMA</p></h3><br>
        <form action="includes/login.inc.php" method="POST">
          <div class="md-form mb-0">
              <input type="text" id="username" name="username" class="form-control">
              <label for="name" class=""><p class="text-white"><i class="fas fa-user"></i> Username</p></label>
          </div>
          <div class="md-form mb-0">
              <input type="password" id="password" name="password" class="form-control">
              <label for="name" class=""><p class="text-white"><i class="fas fa-unlock-alt"></i> Password</p></label>
          </div><br>
          <button class="btn btn-block my-4 blue-gradient" type="submit">Sign in</button>
        </form>
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
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>ERROR. </strong>Empty fields.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';
          }
          else if ($_GET['error'] == 'wrongpwd'){
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>ERROR. </strong>Wrong password.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';
          }
          else if ($_GET['error'] == 'nouser'){
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>ERROR. </strong>Username invalid.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';
          }
        }
        ?>
    </div>
</body>
</html>
