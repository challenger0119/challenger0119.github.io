<?php
function alert($string){
	echo "<script language=\"JavaScript\">";
	echo "alert(\"".$string."\");";
 	echo "</script>";
}

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
	var $userid;
	function __construct($id,$md,$ctt,$dt,$lct,$uid)
	{
		$this->id = $id;	//initialized during reading item from db
		$this->mood = $md;
		$this->content = $ctt;
		$this->date = $dt;	//initialized during reading item from db
		$this->location = $lct;
		$this->userid = $uid;
	}
	function convertToInsertDBString(){
		return "(".$this->mood.",\"".$this->content."\",now(),\"".$this->location."\",".$this->userid.")";
	}
	function convertToItemVarDBString(){
		return "(mood,content,date,location,userid)";
	}
}

/**
* Item to be inserted to DB
*/
class WriteItem extends Item
{
	function WriteItem($md,$ctt,$lct,$uid)
	{
		parent::__construct(0,$md,$ctt,"",$lct,$uid);
	}
}
/**
* User 
*/
class User
{
	var $id;
	var $name;
	var $passwd;
	
	function __construct($uid,$nm,$pwd)
	{
		$this->id = $uid;
		$this->name = $nm;
		$this->passwd = $pwd;
	}
	function convertToInsertDBString(){
		return "(\"".$this->name."\",\"".$this->passwd."\")";
	}
	function convertToUserVarDBString(){
		return "(name,passwd)";
	}
}
/**
* User to be inserted to DB;
*/
class WriteUser extends User
{
	
	function WriteUser($nm,$pwd)
	{
			parent::__construct(0,$nm,$pwd);
	}
}

/**
* Finance
*/
class Finance
{
	var $id;
	var $category;
	var $yuan;
	var $date;
	var $location;
	var $userid;
	function __construct($id,$cg,$yan,$dt,$lct,$uid)
	{
		$this->id = $id;
		$this->category = $cg;
		$this->yuan = $yan;
		$this->date = $dt;
		$this->location = $lct;
		$this->userid = $uid;
	}
	function convertToInsertDBString(){
		return "(".$this->category.",\"".$this->yuan."\",now(),\"".$this->location."\",".$this->userid.")";
	}
	function convertToItemVarDBString(){
		return "(category,yuan,date,location,userid)";
	}
}

/**
* Finance writer
*/
class WriteFinance extends Finance
{
	
	function __construct($cg,$yan,$lct,$uid)
	{
		parent::__construct(0,$cg,$yan,"",$lct,$uid);
	}
}

/**
* Mysql operation
*/
class Mysql
{
	var $dbConnect;
	var $dbName;
	var $tableName;
	function __construct()
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

	//login checkuser;
	function checkUserAccount($name,$passwd){
		$sql = "select * from iuser where name='".$name."' and passwd='".$passwd."'";
		$result = mysqli_query($this->dbConnect,$sql);
		if (!$result) {
			error_log($sql,3,'errorlog.txt');
			die('Error checkUserAccount:'.mysqli_error($this->dbConnect));
			return false;
		};
		if ($result->num_rows > 0) {
			return true;
		}else{
			return false;
		}
	}

	//insert imagination
	function insertItem($item){
		$sql = "insert into ".$this->tableName.$item->convertToItemVarDBString()."values".$item->convertToInsertDBString();
		$result = mysqli_query($this->dbConnect,$sql);
		if (!$result) {
			error_log($sql,3,'errorlog.txt');
			die('Error insert:'.mysqli_error($this->dbConnect));
		};
		return $result;
	}

	//show imagination
	function getAllItems($uid){
		$itemArray = array();
		$sql = "select * from imagination where userid=".$uid." order by date desc";
		$rs = mysqli_query($this->dbConnect,$sql);
		if ($rs->num_rows > 0) {
			while ($row = $rs->fetch_assoc()) {
				$newItem = new Item($row["id"],$row["mood"],$row["content"],$row["date"],$row["location"],$row["userid"]);
		array_push($itemArray, $newItem);
			}
		}
		return $itemArray;
	}

	//show finance
	function getFinanceData($uid){
		$financeItem = array();
		$sql = "select * from finance where userid=".$uid." order by date desc";
		$rs = mysqli_query($this->dbConnect,$sql);
		if ($rs->num_rows > 0) {
			while ($row = $rs->fetch_assoc()) {
				$newItem = new Item($row["id"],$row["category"],$row["yuan"],$row["date"],$row["location"],$row["userid"]);
		array_push($financeItem, $newItem);
			}
		}
		return $financeItem;
	}

	//init user
	function getUserWithName($name){
		$sqluser = "select * from iuser where name='".$name."'";
		$uidresult = mysqli_query($this->dbConnect,$sqluser);
		$user = $uidresult->fetch_assoc();
		return new User($user["id"],$user["name"],$user["passwd"]);
	}

	//register create user
	function insertUser($user){
		$sql = "insert into iuser".$user->convertToUserVarDBString()."values".$user->convertToInsertDBString();
		$result = mysqli_query($this->dbConnect,$sql);
		if (!$result) {
			error_log($sql,3,'errorlog.txt');
			die('Error insert:'.mysqli_error($this->dbConnect));
		};
		return $result;
	}

	//register check unique
	function checkUserName($username){
		$sql = "select * from iuser where name='".$username."'";
		$result = mysqli_query($this->dbConnect,$sql);
		if (!$result) {
			error_log($sql,3,'errorlog.txt');
			die('Error select user:'.mysqli_error($this->dbConnect));
			return false;
		}
		if($result->num_rows > 0){
			return true;
		}else{
			return false;
		}
	}
	function __destruct(){
		mysqli_close($this->dbConnect);
	}

}

?>
