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
	include "include/category_config.php";
	$categoryDB->add($_GET["catname"], utf8_encode($_GET["vnese_catname"]), 0);
?>
<script>
   	window.location = 'category.php';
</script>
</body>
</html>