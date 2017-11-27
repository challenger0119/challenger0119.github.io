<?php
include "imaginationclass.php";
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


?>
