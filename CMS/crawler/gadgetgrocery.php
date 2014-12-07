<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class gadgetgrocery extends base_crawler {
		public function SetConfig($host, $user, $pass, $db)
		{
			$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->DBSite = new DBCrawler();
			$this->name = "gadgetgrocery";
			$this->DBSite->SetConfig($host, $user, $pass, $db, $this->name);
			$this->curl_flag = true;
		}

		public function get_whole_product_page($link, $page) {
			/* Array of results*/
			$result = array(); 
			/* Iterate through each page and only stop when there is no product left */
			$linkpage = trim($link."&page=".$page);
			if ($page == 1) $linkpage = $link;
			echo "linkpage = ".$linkpage;
			$content = get_content($linkpage, $this->curl_flag);
			$lines = explode("div class=\"s_item product", $content);
			echo "size = ".sizeof($lines);

			/* Go through all the product and add them to the results */
			for ($i = 1; $i < sizeof($lines); $i++) $result[] = $lines[$i]; 

			return $result;
		}
		
		/* Get the image of the product based on the product code get from get_whole_product */
		public function get_image($description) {
			$index = strpos($description, "src=\"");
			if ($index !== false) {
				$description = substr($description, $index+strlen("src=\""));
				$description = substr($description, 0, strpos($description, "\""));
				echo "<img src =\"".$description."\"/>\n";
				return $description;
			}
			return "";
		}
		
		/* Get the name of the product based on the product code get from get_whole_product */
		public function get_name($content) {
			$index = strpos($content, "<title>");
			if ($index !== false) {
				$content = substr($content, $index+strlen("<title>"));
				$content = substr($content, 0, strpos($content, "<"));
				$content = trim($content);
				echo "<h3>$content</h3>\n";
				return $content;
			}
			return "";
		}
		
		/* Get the link of the product based on the product code get from get_whole_product */
		public function get_link($description) {
			$index = strpos($description, "href=\""); 
			if ($index !== false) 
			{
				$description = substr($description, $index+strlen("href=\""));
				$description = substr($description, 0, strpos($description, "\""));
				$description = htmlspecialchars_decode($description);
				echo "<h3>$description</h3>\n";
				return $description;
			}
			return "";
		}

		/* Get the price of the product based on the product code get from get_whole_product */
		public function get_price($content) {
			/* The case of special price product*/
			$index = strpos($content, "<span itemprop=\"price\">");
			if ($index !== false) 
			{
				$content = substr($content, $index);
				// If the product has discount price 
				$index = strpos($content, "</span>");
				if ($index !== false) 
				{
					$content = substr($content, $index+strlen("</span>"));
					$content = substr($content, 0, strpos($content, "<"));
					$content = trim($content);
					$content = str_replace(",","",$content);
					echo "Price = $content<br/>";
					return $content;
				}
			}
			return "";
		}
		
		/* Get the list of comments of the product using the content of the product detail page */
		public function get_comment($content) {
		}

		/* Get the description of the product using the content of the product detail page */
		public function get_description($content) {
			$index = strpos($content, "<div id=\"product_description\" itemprop=\"description\">");

			if ($index !== false) {
				$content 	= substr($content, $index+strlen("<div id=\"product_description\" itemprop=\"description\">"));
				$content 	= substr($content, 0, strpos($content, "</div>"));
				echo $content;
				return $content;
			}
			return "";
		}

		/* Get the list of image of the product using the content of the product detail page */
		public function get_extra_image($content) {
			$content = substr($content, strpos($content, "<div id=\"product_image_preview_holder\""));
			$content = substr($content, 0, strpos($content, "<div id=\"product_price\""));

			$extra_images = explode("href=\"", $content);
			/* Add image into the results */
			for ($i = 1; $i < sizeof($extra_images); $i ++) {
				$extra_images[$i] = substr($extra_images[$i], 0, strpos($extra_images[$i], "\""));
				$extra_images[$i] = str_replace(" ", "%20", $extra_images[$i]);
				//echo "<img src =\"".$extra_images[$i]."\">";
			}
			/* If there are not enough 8 images, add empty string in to fill the place */
			for ($i = sizeof($extra_images); $i < 8; $i++) {
				$extra_images[$i] = "";
			}
			return $extra_images;
		}

		/* Add all the products in the given category from the given link */
		public function add_category($category, $link, $page) {
			$db = $this->DBSite;
			$count = add_category_website($category, $link, $page, $this, $db, true, $this->curl_flag);
			return $count;
		}

		public function categories() {
			$cat = array();

			$cat["desktop"] = "http://gadgetgrocery.com/index.php?route=product/category&path=20";
			$cat["camera"] = "http://gadgetgrocery.com/index.php?route=product/category&path=33";
			$cat["laptop"] = "http://gadgetgrocery.com/index.php?route=product/category&path=18";
			$cat["mp3"] = "http://gadgetgrocery.com/index.php?route=product/category&path=34";
			$cat["mobiles"] = "http://gadgetgrocery.com/index.php?route=product/category&path=24";
			$cat["tablets"] = "http://gadgetgrocery.com/index.php?route=product/category&path=57";
			$cat["tv"] = "http://gadgetgrocery.com/index.php?route=product/category&path=25";
			
			return $cat;
		}
	}

	set_time_limit(5000);

	// $crawler = new gadgetgrocery();
	// $result = $crawler->get_whole_product_page("http://gadgetgrocery.com/index.php?route=product/category&path=20", 1);

	// //Get the name, the image, the price, and the link of each product and add them to the database
	// $link = $crawler->get_link($result[0]);
	// $image = $crawler->get_image($result[0]);
	// $content = get_content($link, true);
	// $comment = "";
	// $price = $crawler->get_price($content);
	// $name = $crawler->get_name($content);
	// $description = $crawler->get_description($content);
	// $extraimage = $crawler->get_extra_image($content);
?>	
</body>
</html>