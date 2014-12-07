<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class lazada extends base_crawler {
		public function SetConfig($host, $user, $pass, $db)
		{
			$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->DBSite = new DBCrawler();
			$this->name = "lazada";
			$this->DBSite->SetConfig($host, $user, $pass, $db, $this->name);
			$this->curl_flag = true;
		}

		public function get_whole_product_page($link, $page) {
			/* Array of results*/
			$result = array(); 
			/* Iterate through each page and only stop when there is no product left */
			$linkpage = trim($link."?page=$page");
			if ($page == 1) $linkpage = $link;
			echo "linkpage = ".$linkpage;
			$content = get_content($linkpage, $this->curl_flag);
			$lines = explode("class=\"unit", $content);
			echo "size = ".sizeof($lines);

			/* Go through all the product and add them to the results */
			for ($i = 1; $i < sizeof($lines); $i++) $result[] = $lines[$i]; 

			return $result;
		}
		
		/* Get the image of the product based on the product code get from get_whole_product */
		public function get_image($description) {
			$index = strpos($description, "data-image=\"");
			if ($index !== false) {
				$description = substr($description, $index+strlen("data-image=\""));
				$description = substr($description, 0, strpos($description, "\""));
				echo "<img src =\"".$description."\"/>\n";
				return $description;
			}
			return "";
		}
		
		/* Get the name of the product based on the product code get from get_whole_product */
		public function get_name($content) {
			$index = strpos($content, "itemprop=\"name\">");
			if ($index !== false) {
				$content = substr($content, $index+strlen("itemprop=\"name\">"));
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
			if ($index !== false) {
				$description = substr($description, $index+strlen("href=\""));
				$description = substr($description, 0, strpos($description, "\""));
				$description = "http://www.lazada.com.ph".$description;
				echo "<h3>$description</h3>\n";
				return $description;
			}
			return "";
		}

		/* Get the price of the product based on the product code get from get_whole_product */
		public function get_price($content) {
			/* The case of special price product*/
			$index = strpos($content, "<meta itemprop=\"price\" content=\"");
			if ($index !== false) {
				$content = substr($content, $index+strlen("<meta itemprop=\"price\" content=\"")+strlen("₱ "));
				$content = trim(substr($content, 0, strpos($content, ".")));
				$content = intval(str_replace(",","",$content));
				echo "Price = $content<br/>";
				return $content;
			}
			return "";
		}
		
		/* Get the list of comments of the product using the content of the product detail page */
		public function get_comment($content) {
		}

		/* Get the description of the product using the content of the product detail page */
		public function get_description($content) {
			$index = strpos($content, "<div class=\"prd-description\">");

			if ($index !== false) {
				$content 	= substr($content, $index+strlen("<div class=\"prd-description\">"));
				$content 	= substr($content, 0, strpos($content, "<div style=\"clear: both;\">"));
				echo "Description length = ".strlen($content)."<br/>";
				//echo $content;
				return $content;
			}
			return "";
		}

		/* Get the spec of a product */
		public function get_description2($content)
		{
			$index= strpos($content, "<ul class=\"prd-attributesList ui-listBulleted\">");

			if ($index !== false)
			{
				$content = substr($content, $index);
				$content = substr($content, 0, strpos($content, "</ul>")+strlen("</ul>"));
				echo "Description2 = ".$content;
				return $content;
			}

			return "";
		}

		/* Get the list of image of the product using the content of the product detail page */
		public function get_extra_image($content) {
			$content = substr($content, stripos($content, "<div id=\"magnifier\"></div>"));
			$extra_images = explode("data-zoom-image=\"", $content);
			/* Add image into the results */
			for ($i = 1; $i < sizeof($extra_images); $i ++) {
				$extra_images[$i] = substr($extra_images[$i], 0, strpos($extra_images[$i], "\""));
				//echo "<img src =\"".$extra_images[$i]."\" style=\"max-height: 250px\">";
			}
			
			/* If there are not enough 8 images, add empty string in to fill the place */
			for ($i = sizeof($extra_images); $i < 8; $i++) {
				$extra_images[$i] = "";
			}
			echo "<img src =\"".$extra_images[1]."\" style=\"max-height: 250px\">";
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

			$cat["mobiles"] = "http://www.lazada.com.ph/shop-mobiles/";
			$cat["laptop"] = "http://www.lazada.com.ph/shop-laptops/";
			$cat["tablets"] = "http://www.lazada.com.ph/shop-tablets-2/";
			$cat["camera"] = "http://www.lazada.com.ph/shop-point-shoot/";
			$cat["camera"] = "http://www.lazada.com.ph/shop-dslr-slr/";
			$cat["camera"] = "http://www.lazada.com.ph/shop-bridge/";
			$cat["camera"] = "http://www.lazada.com.ph/shop-instant-camera/";
			$cat["camera"] = "http://www.lazada.com.ph/shop-mirrorless/";
			$cat["camera"] = "http://www.lazada.com.ph/shop-specialty-cameras/";
			$cat["mp3"] = "http://www.lazada.com.ph/shop-mp3-players-ipods/";
			$cat["phone_accessory"] = "http://www.lazada.com.ph/shop-mobile-accessories/";
			$cat["speaker"] = "http://www.lazada.com.ph/shop-home-audio/";
			$cat["tv"] = "http://www.lazada.com.ph/shop-televisons/";
			$cat["disk_player"] = "http://www.lazada.com.ph/shop-blue-ray-dvd-players/";
			$cat["tablet_accessory"] = "http://www.lazada.com.ph/shop-tablet-accessories/";
			$cat["computer_accessory"] = "http://www.lazada.com.ph/shop-computer-accessories/";
			$cat["female_shirt"] = "http://www.lazada.com.ph/shop-womens-tops/";
			$cat["female_pants"] = "http://www.lazada.com.ph/shop-womens-pants/";
			$cat["female_skirt"] = "http://www.lazada.com.ph/shop-womens-skirts/";
			$cat["female_shoes"] = "http://www.lazada.com.ph/shop-womens-shoes/";
			$cat["male_shirt"] = "http://www.lazada.com.ph/shop-mens-t-shirts-polo-shirts/";
			$cat["male_polo"] = "http://www.lazada.vn/ao-polo/";
			$cat["male_pants"] = "http://www.lazada.com.ph/shop-mens-jeans/";
			$cat["male_shorts"] = "http://www.lazada.com.ph/shop-mens-shorts/";
			$cat["male_shoes"] = "http://www.lazada.com.ph/shop-mens-shoes/";
			$cat["male_accessories"] = "http://www.lazada.com.ph/shop-mens-accessories/";

			return $cat;
		}
	}

	set_time_limit(5000);

	// $crawler = new lazada();
	// $result = $crawler->get_whole_product_page("http://www.lazada.com.ph/shop-mobiles/", 1);

	// //Get the name, the image, the price, and the link of each product and add them to the database
	// $link = $crawler->get_link($result[0]);
	// $image = $crawler->get_image($result[0]);
	// $content = get_content($link, true);
	// $comment = "";
	// $price = $crawler->get_price($content);
	// $name = $crawler->get_name($content);
	// $description = $crawler->get_description($content);
	// $description = $crawler->get_description2($content);
	// $extraimage = $crawler->get_extra_image($content);
?>	
</body>
</html>