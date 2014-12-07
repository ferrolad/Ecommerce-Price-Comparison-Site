<?php
	include "include/customer_config.php";
	include "crawler/xlog.php";

	$email = $_GET["email"];
	$firstname = $_GET["firstname"];
	$lastname = $_GET["lastname"];
	$gender = $_GET["gender"];
	$birthdate = ""; //$_GET["birthdate"];
	$createdate = date("Y-m-d");
	$updatedate = date("Y-m-d");
	$createtime = date("H:i:s");
	$updatetime = date("H:i:s");
	if (($gender == "male" || $gender == "female") && !empty($firstname) && !empty($lastname) && !empty($email)) {
		$customerDB->add (strip_tags($email), strip_tags($firstname), strip_tags($lastname), strip_tags($createdate), strip_tags($createtime), strip_tags($updatedate), strip_tags($updatetime), strip_tags($gender), strip_tags($birthdate));
		//$data = array(1);
		//echo $_GET['callback'] . '('.json_encode($data).')';
		echo $_GET['callback'] . '(' . "{'val' : '1'}" . ')';

	} else {
		//$data = array(0);
		//echo $_GET['callback'] . '('.json_encode($data).')';
		echo $_GET['callback'] . '(' . "{'val' : '0'}" . ')';
	}
?>