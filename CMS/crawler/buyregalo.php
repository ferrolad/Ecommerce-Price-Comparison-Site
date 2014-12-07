<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class buyregalo extends base_crawler {
		public function SetConfig($host, $user, $pass, $db)
		{
			$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->DBSite = new DBCrawler();
			$this->name = "buyregalo";
			$this->DBSite->SetConfig($host, $user, $pass, $db, $this->name);
			$this->curl_flag = true;
		}

		public function get_whole_product_page($link, $page) {
			/* Array of results*/
			$result = array(); 
			/* Iterate through each page and only stop when there is no product left */
			$linkpage = trim($link."?sort=featured&page=".$page);
			if ($linkpage == 1) $linkpage = $link;
			echo "linkpage = ".$linkpage;

			$content = get_content($linkpage, $this->curl_flag);
			$content = substr($content, strpos($content, "<ul class=\"ProductList"));
			$content = substr($content, 0, strpos($content, "</ul>"));
			$lines = explode("<div class=\"ProductImage\">", $content);
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
				$description = trim($description);
				echo "<img src=\"$description\"/>\n";
				return $description;
			}
			return "";
		}
		
		/* Get the name of the product based on the product code get from get_whole_product */
		public function get_name($content) {
			$index = strpos($content, "\"og:title\" content=\"");
			if ($index !== false) {
				$content = substr($content, $index+strlen("\"og:title\" content=\""));
				$content = substr($content, 0, strpos($content, "\""));
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
				echo "<h3>$description</h3>\n";
				return $description;
			}
			return "";
		}

		/* Get the price of the product based on the product code get from get_whole_product */
		public function get_price($content) {
			/* The case of special price product*/
			$index = strrpos($content, "VariationProductPrice\">$");
			if ($index !== false) 
			{
				$content = substr($content, $index+strlen("VariationProductPrice\">$"));
				$content = substr($content, 0, strpos($content, "</em>"));
				// Convert USD to philippine peso
				$content = round(floatval($content)*44.18); 
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
			$index = strpos($content, "<div class=\"ProductDescriptionContainer\">");

			if ($index !== false) {
				$content 	= substr($content, $index+strlen("<div class=\"ProductDescriptionContainer\">"));
				$content 	= substr($content, 0, strpos($content, "</div>"));
				//echo $content;
				echo "Description length = ".strlen($content);
				return $content;
			}
			return "";
		}

		/* Get the list of image of the product using the content of the product detail page */
		public function get_extra_image($content) {
			$content = substr($content, strpos($content, "var productId = ")+strlen("var productId = "));
			$content = substr($content, 0, strpos($content, ";"));
			$content = "http://www.buyregalo.com/productimage.php?product_id=".trim($content);
			$content = get_content($content, $this->curl_flag);

			$extra_images = explode("] = \"http:\\/\\/", $content);

			for ($i = 1; $i < sizeof($extra_images); ++$i)
			{
				$extra_images[$i] = "http://".substr($extra_images[$i], 0, strpos($extra_images[$i], "\"")); 
				$extra_images[$i] = str_replace("\\/", "/", $extra_images[$i]);
				//echo "<img src=\"".$extra_images[$i]."\">";
			}

			echo "<img src=\"".$extra_images[1]."\">";

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

			$cat["laptop"] = "http://www.buyregalo.com/categories/Computers-%26-Office/Laptops-%26-Netbooks/";
			$cat["desktop"] = "http://www.buyregalo.com/categories/Electronics/Desktop-PCs/";
			$cat["computer_accessory_storage"] = "http://www.buyregalo.com/categories/Computers-%26-Office/Storage/";
			$cat["mobiles"] = "http://www.buyregalo.com/categories/Electronics/Mobile-Phones/"; 
			$cat["tablets"] = "http://www.buyregalo.com/categories/Electronics/Tablets/";
			$cat["monitor"] = "http://www.buyregalo.com/categories/Electronics/LCD-Monitors/";
			$cat["camera1"] = "http://www.buyregalo.com/categories/Electronics/Camera/";
			$cat["camera2"] = "http://www.buyregalo.com/categories/Electronics/Digital-SLR-Cameras/";
			$cat["camera3"] = "http://www.buyregalo.com/categories/Electronics/Digital-Cameras/";
			$cat["mp3"] = "http://www.buyregalo.com/categories/Electronics/Mp3/";
			$cat["female_dress"] = "http://www.buyregalo.com/categories/Fashion-and-Accessories/Women%27s-Apparel/Women%27s-Dresses/";
			$cat["female_shirt_jacket"] = "http://www.buyregalo.com/categories/Fashion-and-Accessories/Women%27s-Apparel/Women%27s-Jackets-/";
			$cat["female_jean"] = "http://www.buyregalo.com/categories/Fashion-and-Accessories/Women%27s-Apparel/Women%27s-Jeans/";
			$cat["female_pants"] = "http://www.buyregalo.com/categories/Fashion-and-Accessories/Women%27s-Apparel/Women%27s-Pants/";
			$cat["female_underwear"] = "http://www.buyregalo.com/categories/Fashion-and-Accessories/Women%27s-Apparel/Women%27s-Underwear/";
			
			return $cat;
		}
	}

	set_time_limit(5000);

	// $crawler = new buyregalo();
	// $result = $crawler->get_whole_product_page("http://www.buyregalo.com/categories/Electronics/Camera/", 1);

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