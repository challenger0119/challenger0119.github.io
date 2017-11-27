<?php
include "imaginationclass.php";
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
?>
