<?php
    if (!isset($_COOKIE["username"])){
        setrawcookie("URL",$_SERVER['REQUEST_URI']);
        header("Location: imaginationlogin.php"); 
    }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Finance</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../lib/imaginationlogin/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../lib/imaginationlogin/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../lib/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../lib/imaginationlogin/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body>
<div>
  <div>
    <section>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Finance Table</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>日期</th>
                  <th>吃饭</th>
                  <th>消费</th>
                  <th>生活</th>
                  <th>转账</th>
                </tr>
                </thead>
                <?php
                	#include "imaginationclass.php";
                	function getDataTable(){
                		$mysql = new Mysql();
						$user = $mysql->getUserWithName($_COOKIE["username"]);
						#$user = $mysql->getUserWithName("miaoqi01");
                		$mysql = new Mysql();
                		$results = $mysql->getFinanceData($user->id);
                	}
                	getDataTable();
                ?>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../lib/jQuery/jquery-3.2.0.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../lib/bootstrap/js/bootstrap.min.js"></script>
<!-- page script -->
<script>
</script>
</body>
</html>
