<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	$link = "http://www.pricepanda.com.my/computers/razer/laptops/";
	$page = 1;
	while (true)
	{
		$content = get_content($link.$page."/", true);
		$content = explode("<div class=\"productImage\">", $content);

		if (sizeof($content) == 1) break;

		for ($i = 1; $i < sizeof($content); $i++)
		{
			$product = $content[$i];
			$product = substr($product, stripos($product, "alt=\"")+strlen("alt=\""));
			$product = substr($product, 0, stripos($product, "\""));
			echo $product."<br/>";
		}
		$page++;
	}
	?>	
</body>
</html>