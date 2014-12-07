<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class widgetcity extends base_crawler {
		public function SetConfig($host, $user, $pass, $db)
		{
			$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->DBSite = new DBCrawler();
			$this->name = "widgetcity";
			$this->DBSite->SetConfig($host, $user, $pass, $db, $this->name);
			$this->curl_flag = true;
		}

		public function get_whole_product_page($link, $page) {
			/* Array of results*/
			$result = array(); 
			/* Iterate through each page and only stop when there is no product left */
			$linkpage = trim($link."?page=".$page);
			if ($page == 1) $linkpage = $link;
			echo "linkpage = ".$linkpage;
			$content = get_content($linkpage, $this->curl_flag);
			$lines = explode("<li class=\"views-row", $content);
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
			$index = strpos($content, "class=\"title\">");
			if ($index !== false) {
				$content = substr($content, $index+strlen("class=\"title\">"));
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
				$description = "http://www.widgetcity.com.ph".$description;
				echo "<h3>$description</h3>\n";
				return $description;
			}
			return "";
		}

		/* Get the price of the product based on the product code get from get_whole_product */
		public function get_price($content) {
			/* The case of special price product*/
			$index = strpos($content, "price-prefixes\">Price: </span>Php ");
			if ($index !== false) 
			{
				$content = substr($content, $index+strlen("price-prefixes\">Price: </span>Php "));
				// If the product has discount price 
				$content = substr($content, 0, strpos($content, "."));
				$content = trim(str_replace(",","",$content));
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
			$index = strpos($content, "Specification");

			if ($index !== false) {
				$content 	= substr($content, $index+strlen("Specification"));
				$content 	= substr($content, 0, strpos($content, "<!-- /hide terms"));
				
				if (strlen($content) < 10)
				{
					$content .= " Oizoioi.ph Price Comparison";
				}

				echo $content;
				return $content;
			}
			return "Oizoioi.ph Price Comparison";
		}

		/* Get the list of image of the product using the content of the product detail page */
		public function get_extra_image($content) {
			$extra_images = explode("<a rel=\"lightbox[imgs]\" href=\"", $content);
			/* Add image into the results */
			for ($i = 1; $i < sizeof($extra_images); $i ++) {
				$extra_images[$i] = substr($extra_images[$i], 0, strpos($extra_images[$i], "\""));
				$extra_images[$i] = "http://www.widgetcity.com.ph".$extra_images[$i];
				echo "<img src =\"".$extra_images[$i]."\">";
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

			$cat["mobiles"] = "http://www.widgetcity.com.ph/products/mobile-phone";
			$cat["tablets"] = "http://www.widgetcity.com.ph/products/laptops-and-tablets";
			$cat["camera1"] = "http://www.widgetcity.com.ph/products/digital-camera";
			$cat["camera2"] = "http://www.widgetcity.com.ph/products/dslrs";
			$cat["headphone"] = "http://www.widgetcity.com.ph/products/audio-devices";
			$cat["headphone"] = "http://www.widgetcity.com.ph/products/audio-devices";
			$cat["phone_accessory"] = "http://www.widgetcity.com.ph/products/premium-accessories";
			
			
			return $cat;
		}
	}

	set_time_limit(5000);

	// $crawler = new widgetcity();
	// $result = $crawler->get_whole_product_page("http://www.widgetcity.com.ph/products/mobile-phone", 1);

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