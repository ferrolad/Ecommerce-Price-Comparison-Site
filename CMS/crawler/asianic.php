<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class asianic extends base_crawler {
		public function SetConfig($host, $user, $pass, $db)
		{
			$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->DBSite = new DBCrawler();
			$this->name = "asianic";
			$this->DBSite->SetConfig($host, $user, $pass, $db, $this->name);
			$this->curl_flag = true;
		}

		public function get_whole_product_page($link, $page) {
			/* Array of results*/
			$result = array(); 
			/* Iterate through each page and only stop when there is no product left */
			$linkpage = trim($link."/ID/".(25*($page-1)));
			if ($linkpage == 1) $linkpage = $link;
			echo "linkpage = ".$linkpage;

			$content = get_content($linkpage, $this->curl_flag);
			$lines = explode("<td style=\"vertical-align: top;\">", $content);
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
				$description = "http://www.asianic.com.ph".$description;
				echo "<img src =\"".$description."\"/>\n";
				return $description;
			}
			return "";
		}
		
		/* Get the name of the product based on the product code get from get_whole_product */
		public function get_name($content) {
			$index = strpos($content, "<h1>");
			if ($index !== false) {
				$content = substr($content, $index+strlen("<h1>"));
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
				$description = "http://www.asianic.com.ph".$description;
				echo "<h3>$description</h3>\n";
				return $description;
			}
			return "";
		}

		/* Get the price of the product based on the product code get from get_whole_product */
		public function get_price($content) {
			/* The case of special price product*/
			$index = strrpos($content, "productlist_priceonline\">P ");
			if ($index !== false) 
			{
				$content = substr($content, $index+strlen("productlist_priceonline\">P "));
				$content = substr($content, 0, strpos($content, "</span>"));
				$content = str_replace(",","",$content);
				echo "Price = $content<br/>";
				return $content;
			}
			else 
			{
				$content = substr($content, strpos($content, "productdetail_price\">"));
				$content = substr($content, strpos($content, "P ")+strlen("P "));
				$content = substr($content, 0, strpos($content, " "));
				$content = str_replace(",", "", $content);
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
			$index = strpos($content, "<h3>Features</h3>");

			if ($index !== false) {
				$content 	= substr($content, $index+strlen("<h3>Features</h3>"));
				$content 	= substr($content, 0, strpos($content, "<div id=\"right_side\">"));
				//echo $content;
				echo "Description length = ".strlen($content);
				return $content;
			}
			return "";
		}

		/* Get the list of image of the product using the content of the product detail page */
		public function get_extra_image($content) {
			$extra_images = explode("<div id=\"productdetail_left\">", $content);
			
			$extra_images[1] = substr($extra_images[1], strpos($extra_images[1], "src=\"")+strlen("src=\""));
			$extra_images[1] = substr($extra_images[1], 0, strpos($extra_images[1], "\""));
			$extra_images[1] = "http://www.asianic.com.ph".$extra_images[1];
			echo $extra_images[1];
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

			$cat["laptop1"] = "http://www.asianic.com.ph/product_list/notebooks";
			$cat["laptop2"] = "http://www.asianic.com.ph/product_list/netbooks";
			$cat["desktop"] = "http://www.asianic.com.ph/product_list/desktops";
			$cat["tablets"] = "http://www.asianic.com.ph/product_list/tabletpcs";
			$cat["mouse"] = "http://www.asianic.com.ph/product_list/mouse";
			$cat["keyboard"] = "http://www.asianic.com.ph/product_list/keyboards";
			$cat["cooler_pad"] = "http://www.asianic.com.ph/product_list/cooling-fans";
			$cat["keyboard"] = "http://www.asianic.com.ph/product_list/keyboards";
			$cat["external_drive"] = "http://www.asianic.com.ph/product_list/external-hdd";
			$cat["monitor"] = "http://www.asianic.com.ph/product_list/lcd-monitors";
			$cat["mobiles1"] = "http://www.asianic.com.ph/product_list/mobile-phones";
			$cat["mobiles2"] = "http://www.asianic.com.ph/product_list/pdas";
			$cat["computer_accessory"] = "http://www.asianic.com.ph/product_list/notebooks-accessories";
			$cat["speaker_system"] = "http://www.asianic.com.ph/product_list/speakers";
			$cat["headphone"] = "http://www.asianic.com.ph/product_list/headsets";
			$cat["camera1"] = "http://www.asianic.com.ph/product_list/digicam";
			$cat["camera2"] = "http://www.asianic.com.ph/product_list/slrcams";
			$cat["mp3"] = "http://www.asianic.com.ph/product_list/mp3players";
			$cat["software"] = "http://www.asianic.com.ph/product_list/os";
			
			return $cat;
		}
	}

	set_time_limit(5000);

	// $crawler = new asianic();
	// $result = $crawler->get_whole_product_page("http://www.asianic.com.ph/product_list/notebooks", 1);

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