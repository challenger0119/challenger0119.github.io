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
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <!--handle searching specific time triggered by enter key during changing the date input text-->
                <input name="newData" type="submit" value="Specific" style="display: none;" /> 
                <div style="text-align: center;">
                  <input name="newData" type="submit" value="Pre" style="float: left;margin-left: 10px;" />
                  <input name="datetime" type="text" id="dateLabel" style="text-align: center;border-style: none; "  />
                  <input name="newData" type="submit" value="Next" style="float: right;margin-right: 10px;" />
                </div>
            </form>
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
                <div id = "datacontent">
                  <?php
                  include "imaginationclass.php";

                  function showDataTable($mysql,$user,$year,$month){
                    $total = array(0,0,0,0);
                    $itemArray = $mysql->getFinanceDataWithDate($user->id,$year,$month);
                    for ($i=0; $i < count($itemArray); $i++) {
                      $item = $itemArray[$i];
                      echo "<tr>";
                      echo "<td>".$item->date."</td>";
                      $contents = array("-","-","-","-");
                      $contents[intval($item->category)] = $item->yuan;
                      $total[intval($item->category)] += $item->yuan;
                      echo "<td>".$contents[0]."</td>";
                      echo "<td>".$contents[1]."</td>";
                      echo "<td>".$contents[2]."</td>";
                      echo "<td>".$contents[3]."</td>";
                      echo "</tr>";
                    }
                    $totalNum = 0;
                    for ($i=0; $i < count($total); $i++) { 
                      $totalNum += $total[$i];
                    }
                    echo "<tr>";
                    echo "<th>总计：".$totalNum."</th>";
                    echo "<th>".$total[0]."</th>";
                    echo "<th>".$total[1]."</th>";
                    echo "<th>".$total[2]."</th>";
                    echo "<th>".$total[3]."</th>";
                    if ($month < 10) {
                      $month = "0".$month;
                    }
                    echo "<script>document.getElementById('dateLabel').value = '".$year."-".$month."';</script>";
                  }



                  $mysql = new Mysql();
                  $user = $mysql->getUserWithName($_COOKIE["username"]);
                  #$user = $mysql->getUserWithName("miaoqi");

                  $date = date("Y-m");
                  $year = (int)substr($date,0,4);
                  $month = (int)substr($date,5);
                
                  if ($_SERVER["REQUEST_METHOD"] == "POST"){
                    $newData = $_POST["newData"];
                    $curDate = $_POST["datetime"];
                    if (isset($newData)) {
                      $year = (int)substr($curDate,0,4);
                      $month = (int)substr($curDate,5);
                      if ($newData != "Specific") {
                        if ($newData == "Next") {
                          if ($month == 12) {
                            $year += 1;
                            $month = 1;
                          }else{
                            $month += 1;
                          }
                        }
                        
                        if ($newData == "Pre") {
                          if ($month == 1) {
                            $year -= 1;
                            $month = 12;
                          }else{
                            $month -= 1;
                          }
                        }
                      }
                    }else{
                      $tmp = 0;
                      if ($_POST["moodOption"] == "option1") {
                        $tmp = 1;
                      } 
                      if ($_POST["moodOption"] == "option2") {
                        $tmp = 2;
                      }
                      if ($_POST["moodOption"] == "option3") {
                        $tmp = 3;
                      }

                      $yuan = $_POST["content"];
                      if (is_float($yuan) || is_numeric($yuan)) {
                        //content input add slash
                        $item = new WriteFinance($tmp,$yuan,$_POST["location"],$user->id);
                        #$item = new WriteItem(1,"test new mysql","hangzhou",$user->id);
                        $mysql->insertFinance($item);
                      }else{
                        alert("Record must be number, store failed");
                      }
                    }  
                  }
                  showDataTable($mysql,$user,$year,$month);

                ?>
                </div>
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
