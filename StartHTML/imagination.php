<?php
include "imaginationclass.php"

$mood = 0;
if ($_POST["moodOption"] == "option1") {
	$mood = 1;
}
if ($_POST["moodOption"] == "option2") {
	$mood = 2;
}
$item = new Item($mood,$_POST["content"],$_POST["location"]);
$mysql = new ItemMysql();
$mysql->insertItem($item);


?>
