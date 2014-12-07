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
	include "include/slider_config.php";
	/* Position in the slider */
	$pos = $_POST["pos"];
	/* Link associate with it */
	$link = $_POST["link"];
	/* Image alt */
	$alt = $_POST["alt"];

	/* If there is already a picture in the slider at that position, copy the old image to the slider history folder */
	$result = $sliderHistoryDB->getNumber();
	if (file_exists("../img/slider/".$pos.".png")) {
		copy("../img/slider/".$pos.".png", "../img/sliderhistory/".($result+1).".png");
	}

	/* Move the new upload file to the slider image folder */
	move_uploaded_file($_FILES["file"]["tmp_name"],"../img/slider/".$pos.".png");

	/* Add the old image to the sliderhistory table */
	$oldInformation = $sliderDB->get($pos);
	if ($oldInformation != NULL) {
		$sliderHistoryDB->add($result+1, $oldInformation[1], "/img/sliderhistory/".($result+1).".png", $oldInformation[3], $pos, $oldInformation[4], $oldInformation[5],date("Y-m-d H:i:s"));
	}

	/* Add the new image to the slider table */
	$sliderDB->add($pos, $link, "/img/slider/$pos.png", $alt, $pos, 0, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
?>
<script>
    window.location = 'slider.php';
</script>
</body>
</html>