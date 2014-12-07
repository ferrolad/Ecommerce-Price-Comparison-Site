<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	echo "<script>\n";
	if (isset($_GET["loc"])) {
		$location = $_GET["loc"];

		/* Insert utm into link */
		if (strpos($location, "?") == false) {
			$location .= "?utm_source=oizoioi.vn&utm_medium=product_detail_page&utm_campaign=oizoioi.vn";
		} else {
			$location .= "&utm_source=oizoioi.vn&utm_medium=product_detail_page&utm_campaign=oizoioi.vn";
		}
		
		if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $location) == true) {
			echo "window.location = '$location';\n";
		} else {
			echo "window.location = '/';\n";
		}
	} else {
		echo "window.location = '/';\n";
	}
	echo "</script>\n";
	?>
</body>
</html>