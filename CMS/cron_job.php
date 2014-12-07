<?php
//echo substr(getcwd(), 0, strrpos(getcwd(), "/"));
$prefix = substr(getcwd(), 0, strrpos(getcwd(), "/"));
// require($prefix."/CMS/include/membersite_config.php");
// require($prefix."/CMS/include/category_config.php");
// require($prefix."/CMS/include/database_config.php");
// require($prefix."/display/general_display_function.php");
require($prefix."/include/membersite_config.php");
require($prefix."/include/category_config.php");
require($prefix."/include/database_config.php");
require($prefix."/../display/general_display_function.php");
foreach (glob($prefix."/crawler/*.php") as $filename)
{
	if ($filename != $prefix."/crawler/general_crawler_function.php" && $filename != $prefix."/crawler/base_crawler.php") 
	{
	    include $filename;
	}
}

set_time_limit(50000);

$website_list = website_list();

$date = intval(date("d")) % 3;

for ($i = $date; $i < sizeof($website_list); $i+=3)
{
	$website = $website_list[$i];

	${$website} = new $website();
	${$website}->SetConfig($default_hostname, $default_username, $default_password, $default_db);
	${$website."DB"} = new DBCrawler();
	${$website."DB"}->SetConfig($default_hostname, $default_username, $default_password, $default_db, $website);
	$categories_list = ${$website}->categories();

	foreach ($categories_list as $key => $value) 
	{
		$page = 1;
		while (true)
		{
	 		$result = ${$website}->add_categories($key, $page);
	 		++$page;

	 		// Break when reach the end of the category for the website
	 		if ($result == 0)
	 		{
	 			break;
	 		}

	 		// SLeep occasionally to avoid overburden the machine
	 		sleep(2);
	 	}
	}
}
?>