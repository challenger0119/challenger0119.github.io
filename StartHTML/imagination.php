<?php
/**
* Item
*/
class Item
{
	var $id;
	var $mood;
	var $content;
	var $date;
	var $location;
	function Item($md,$ctt,$lct)
	{
		$id = 0;	//initialized during reading item from db
		$mood = $md;
		$content = $ctt;
		$date = "";	//initialized during reading item from db
		$location = $lct;

	}
	function convertToInsertDBString(){
		return "($mood,$content,now(),$location)";
	}
	function convertToItemVarDBString(){
		return "(mode,content,date,location)";
	}
}

/**
* insert item into mysql
*/
class ItemMysql
{
	define("dbName", "mysql");
	define("tableName", "imagination");

	var $dbConnect;
	function ItemMysql()
	{
		$servername = "localhost";
		$username = "webuser";
		$password = "";
		$dbConnect = mysql_connect($servername,$username,$password);
		if (!$dbConnect) {
			die('Could not connect to db'.mysql_error());
		}
	}
	function insertItem($item){
		mysql_select_db(dbName,$dbConnect);
		$sql = "insert into ".tableName.$item->convertToItemVarDBString()."values".$item->convertToInsertDBString();
		if (!mysql_query($sql,$dbConnect)) {
			die('Error insert:'.mysql_error());
		};
		echo "add one item";
		mysql_close($dbConnect);
	}
}



$item = Item($_POST[moodOption],$_POST[content],$_POST[location]);
$mysql = ItemMysql();
$mysql->insertItem($item);


?>