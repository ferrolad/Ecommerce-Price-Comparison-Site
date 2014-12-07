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
	include "include/newProduct_config.php";
	/* Position of the new product */
	$pos = $_POST["pos"];
	/* Description of the new product */
	$description = $_POST["description"];
	/* Link of the new product */
	$link = $_POST["link"];
	/* Price of the new product */
	$price = $_POST["price"];

	$result = $newProductsHistoryDB->getNumber();
	if (file_exists("../img/newproducts/".$pos.".png")) {
		copy("../img/newproducts/".$pos.".png", "../img/newproductshistory/".($result+1).".png");
	}
	move_uploaded_file($_FILES["file"]["tmp_name"],"../img/newproducts/".$pos.".png");
	$oldInformation = $newProductsDB->get($pos);
	if ($oldInformation) {
		$newProductsHistoryDB->add($result+1, $oldInformation[1], "/img/newproductshistory/".($result+1).".png", $oldInformation[3], $pos, $oldInformation[5], $oldInformation[6], $oldInformation[7], $oldInformation[8], date("Y-m-d H:i:s"));
	}

	$newProductsDB->add($pos, $link, "/img/newproducts/$pos.png", $_POST["alt"], $pos, $description, $price, 0, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
?>
<<script>
   	window.location = 'newProduct.php';
</script>
</body>
</html>