<?php
	include "./CMS/include/database_config.php";
	if (isset($_GET["id"]) && isset($_GET["website"])) {
		$id = strip_tags($_GET["id"]);
		$website = strip_tags($_GET["website"]);
		if (is_numeric(($id))) {
			$db = new PDO("mysql:host=$default_hostname;dbname=$default_db", $default_username, $default_password);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);	

			$sql = "INSERT INTO redirect VALUES ($id, '$website', '".(date("Y-m-d H:i:s"))."')";
			$sql = $db->prepare($sql);
			$sql->execute();
		}
	}
?>