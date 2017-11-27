<?php
include "imaginationclass.php"
/*
$mood = 0;
if ($_POST["moodOption"] == "option1") {
	$mood = 1;
}
if ($_POST["moodOption"] == "option2") {
	$mood = 2;
}
*/
#$item = new Item(0,$mood,$_POST["content"],"",$_POST["location"]);
$item = new Item("1","2","test","3 ","Hangzhou");
$mysql = new ItemMysql();
$mysql->insertItem($item);


?>
