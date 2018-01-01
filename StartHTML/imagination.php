<?php
    if (!isset($_COOKIE["username"])){
    	setrawcookie("URL",$_SERVER['REQUEST_URI']);
        header("Location: imaginationlogin.php"); 
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Your Imaginations</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <!--handle searching specific time triggered by enter key during changing the date input text-->
    <input name="newData" type="submit" value="Specific" style="display: none;" /> 
    <div style="text-align: center;">
      <input name="newData" type="submit" value="Pre" style="float: left;" />
      <input name="datetime" type="text" id="dateLabel" style="text-align: center;border-style: none; "  />
      <input name="newData" type="submit" value="Next" style="float: right;" />
    </div>
</form>
<?php
include "imaginationclass.php";

function showImaginations($mysql,$user,$year,$month){
	$itemArray = $mysql->getAllItemsWithDate($user->id,$year,$month);
	for ($i=0; $i < count($itemArray); $i++) {
		$item = $itemArray[$i];
		$startString = ""; 
		if ($item->mood == 0) {
			$startString = "<p style=\"font-family:yahei;color:gray;font-size:15px\">";
		}
		if ($item->mood == 1) {
			$startString = "<p style=\"font-family:yahei;color:green;font-size:15px\">";
		}
		if ($item->mood == 2) {
			$startString = "<p style=\"font-family:yahei;color:blue;font-size:15px\">";
		}
		if ($item->mood == 3) {
			$startString = "<p style=\"font-family:yahei;color:red;font-size:15px\">";
		}
		
		echo $startString;
		echo $item->date."<br/>";
		echo nl2br($item->content)."<br/>";		//new line translate
		echo "</p>";
		echo $startString;
		echo $item->location."<br/>";
		echo "</p>";

		if ($item->mood == 0) {
			echo "<hr style=\"height:1px;border:none;border-top:1px dashed gray;\" />";
		}
		if ($item->mood == 1) {
			echo "<hr style=\"height:1px;border:none;border-top:1px dashed green;\" />";
		}
		if ($item->mood == 2) {
			echo "<hr style=\"height:1px;border:none;border-top:1px dashed blue;\" />";
		}
		if ($item->mood == 3) {
			echo "<hr style=\"height:1px;border:none;border-top:1px dashed red;\" />";
		}
	}
	if ($month < 10) {
      $month = "0".$month;
    }
    echo "<script>document.getElementById('dateLabel').value = '".$year."-".$month."';</script>";
}

$mysql = new Mysql();
$user = $mysql->getUserWithName($_COOKIE["username"]);
#$user = $mysql->getUserWithName("miaoqi01");

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

		//content input add slash
		$item = new WriteItem($tmp,addslashes($_POST["content"]),$_POST["location"],$user->id);
		#$item = new WriteItem(1,"test new mysql","hangzhou",$user->id);
		$mysql->insertItem($item);
    }
}

showImaginations($mysql,$user,$year,$month);
?>
</body>
</html>


