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
	function Item($id,$md,$ctt,%dt,$lct)
	{
		$this->id = $id;	//initialized during reading item from db
		$this->mood = $md;
		$this->content = $ctt;
		$this->date = $dt;	//initialized during reading item from db
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
	function getAllItems(){
		$itemArray = array();
		$sql = "select * from imagination";
		$rs = mysqli_query($this->dbConnect,$sql);
		if ($rs->num_rows > 0) {
			while ($row = $rs->fetch_assoc()) {
				$newItem = new Item($row["id"],$row["mood"],$row["content"],$row["date"],$row["location"]);
				array_push($itemArray, $newItem);
			}
		}
		return $itemArray;
	}
}
?>