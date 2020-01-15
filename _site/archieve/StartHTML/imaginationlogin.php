<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Imagination | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../lib/imaginationlogin/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../lib/imaginationlogin/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../lib/imaginationlogin/AdminLTE.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your imagination</p>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
      <div class="form-group has-feedback">
        <input name="username" class="form-control" placeholder="User name">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input name="password" type="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div>
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
      </div>
    </form>
    <hr/>
    <a href="imaginationregister.php" class="text-center">Register</a>
  </div>
</div>
<!-- jQuery 3 -->
<script src="../lib/jQuery/jquery-3.2.0.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../lib/bootstrap/js/bootstrap.min.js"></script>

<?php
include "imaginationclass.php";
if ($_SERVER["REQUEST_METHOD"] == "POST"){  
  $username = $_POST["username"];
  $password = $_POST["password"];
  if ($username != null && $password != null){
    $mysql = new Mysql();
    if ($mysql->checkUserAccount($username,md5($password))){
      setrawcookie("username",$username,time()+3600*24*7);
      header("Location:".$_COOKIE["URL"]);
    }else{
      error_log("Wrong Password or Username",3,'errorlog.txt');
      alert("Wrong Password or Username");
    }
  }else{
    error_log("Please Input Username or Password",3,'errorlog.txt');
    alert("Please Input Username or Password");
  }
}
?>
</body>
</html>