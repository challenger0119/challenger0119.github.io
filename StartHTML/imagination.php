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

function showImaginations(){

	$mysql = new ItemMysql();
	$itemArray = $mysql->getAllItems();
	for ($i=0; $i < count($itemArray); $i++) {
		$item = $itemArray[$i]; 
		echo "<p>";
		echo $item->date."<br/>";
		echo $item->content."<br/>";
		echo $item->location."<br/>";
		echo "</p>";
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){

	$tmp = 0;
	if ($_POST["moodOption"] == "option1") {
		$tmp = 1;
	}	
	if ($_POST["moodOption"] == "option2") {
		$tmp = 3;
	}	
	$item = new WriteItem($tmp,$_POST["content"],$_POST["location"]);
	$mysql = new ItemMysql();
	$mysql->insertItem($item);

}
showImaginations();
?>
</body>
</html>


