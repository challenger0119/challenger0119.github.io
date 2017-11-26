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
		$this->id = 0;	//initialized during reading item from db
		$this->mood = $md;
		$this->content = $ctt;
		$this->date = "";	//initialized during reading item from db
		$this->location = $lct;
		//echo $md.$ctt.$lct."\n";

	}
	function convertToInsertDBString(){
		return "(".$this->mood.",\"".$this->content."\",now(),\"".$this->location."\")";
	}
	function convertToItemVarDBString(){
		return "(mood,content,date,location)";
	}
	function showResult(){
		echo  date("Y.m.d h:i:s a")."<br/>";
		if ($this->mood == 1) {
			echo "Cool congratulation"."<br/>";
		}
		if ($this->mood == 2) {
			echo "OK is just OK"."<br/>";
		}
		echo "Your word: ".$this->content."<br/>";
		echo "Your location: ".$this->location."<br/>";
	}
}

/**
* insert item into mysql
*/
class ItemMysql
{
	var $dbConnect;
	var $dbName;
	var $tableName;
	function ItemMysql()
	{
		$this->servername = "localhost";
		$this->username = "webuser";
		$this->password = "";
		$this->dbName = "mysql";
		$this->tableName = "imagination";
		$this->dbConnect = mysqli_connect($this->servername,$this->username,$this->password,$this->dbName);
		if (!$this->dbConnect) {
			die('Could not connect to db'.mysqli_connect_error());
		}
	}
	function insertItem($item){
		$sql = "insert into ".$this->tableName.$item->convertToItemVarDBString()."values".$item->convertToInsertDBString();
		
		if (!mysqli_query($this->dbConnect,$sql)) {
			echo $sql."\n";
			die('Error insert:'.mysqli_error($this->dbConnect));
		};
		$item->showResult();
		mysqli_close($this->dbConnect);
	}
}

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
