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
	include "include/hotDeal_config.php";
	/* Position of the new hot deal */
	$pos = $_POST["pos"];
	/* Description */
	$description = $_POST["description"];
	/* Link */
	$link = $_POST["link"];
	/* Price */
	$price = $_POST["price"];

	$result = $hotDealsHistoryDB->getNumber();
	if (file_exists("../img/hotdeals/".$pos.".png")) {
		copy("../img/hotdeals/".$pos.".png", "../img/hotdealshistory/".($result+1).".png");
	}
	move_uploaded_file($_FILES["file"]["tmp_name"],"../img/hotdeals/".$pos.".png");
	$oldInformation = $hotDealsDB->get($pos);
	if ($oldInformation) {
		$hotDealsHistoryDB->add($result+1, $oldInformation[1], "/img/hotdealshistory/".($result+1).".png", $oldInformation[3], $pos, $oldInformation[5], $oldInformation[6], $oldInformation[7], $oldInformation[8], date("Y-m-d H:i:s"));
	}
	$hotDealsDB->add($pos, $link, "/img/hotdeals/$pos.png", $_POST["alt"], $pos, $description, $price, 0, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
?>
<script>
   	window.location = 'hotdeals.php';
</script>
</body>
</html>