<?php
include "imaginationclass.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){	
	$username = $_POST["username"];
	$password = $_POST["password"];
	if ($username && $password) {
		
	}else{
		echo "<script language=\"JavaScript\">";
		echo " alert(\"Wrong Username or Password \");";
 		echo "</script>";
	}
}

?>
