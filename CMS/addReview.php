<?php
	include "include/DBConnection.php";
	include "include/database_config.php";

	$name = $_GET["name"];
	$title = $_GET["title"];
	$content = $_GET["content"];
	$rating = $_GET["rating"];
	$id = $_GET["id"];
	$website = $_GET["website"];
	$createdate = date("Y-m-d");
	if (!empty($name) && !empty($title) && !empty($content) && !empty($rating)) {
		$reviewDB = new DBComment();
		//Provide config setting
		$reviewDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);

		$reviewDB->add(strip_tags($name), strip_tags($title), strip_tags($content), strip_tags($rating), strip_tags($id), strip_tags($website), strip_tags($createdate));
	}
?>