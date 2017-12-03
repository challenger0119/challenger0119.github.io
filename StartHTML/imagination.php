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

<?php
include "imaginationclass.php";

function showImaginations($mysql,$user){
	$itemArray = $mysql->getAllItems($user->id);
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
}

$mysql = new Mysql();
$user = $mysql->getUserWithName($_COOKIE["username"]);
#$user = $mysql->getUserWithName("miaoqi01");

if ($_SERVER["REQUEST_METHOD"] == "POST"){

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

showImaginations($mysql,$user);
?>
</body>
</html>


