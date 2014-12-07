<?php
require_once("./include/membersite_config.php");
if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
<?php
	include "include/ad_config.php";
	/* Position of the advertisement */
	$pos = $_POST["pos"];
	/* Description of the advertisement */
	$description = "";
	if (isset($_POST["description"])) {
		$description = $_POST["description"];
	}
	/* Link of the advertisement */
	$link = $_POST["link"];
	/* Price of the advertisement */
	$price = 0;
	if (isset($_POST["price"]) && is_numeric($_POST["price"])) {
		$price = $_POST["price"];
	}

	$result = $adsHistoryDB->getNumber();
	if (file_exists("../img/ads/".$pos.".png")) {
		copy("../img/ads/".$pos.".png", "../img/adshistory/".($result+1).".png");
	}
	move_uploaded_file($_FILES["file"]["tmp_name"],"../img/ads/".$pos.".png");
	$oldInformation = $adsDB->get($pos);
	if ($oldInformation) {
		$adsHistoryDB->add($result+1, $oldInformation[1], "/img/adshistory/".($result+1).".png", $oldInformation[3], $pos, $oldInformation[5], $oldInformation[6], $oldInformation[7], $oldInformation[8], date("Y-m-d H:i:s"));
	}

	$adsDB->add($pos, $link, "/img/ads/$pos.png", $_POST["alt"], $pos, $description, $price, 0, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
?>
<script>
   	window.location = 'advertisement.php';
</script>
</body>
</html>