<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class wiredsystems extends base_crawler {
		public function SetConfig($host, $user, $pass, $db)
		{
			$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->DBSite = new DBCrawler();
			$this->name = "wiredsystems";
			$this->DBSite->SetConfig($host, $user, $pass, $db, $this->name);
			$this->curl_flag = true;
		}

		public function get_whole_product_page($link, $page) {
			/* Array of results*/
			$result = array(); 
			/* Iterate through each page and only stop when there is no product left */
			$linkpage = $link."?p=".$page;
			echo "linkpage = ".$linkpage;
			$content = get_content($linkpage, $this->curl_flag);

			if (strpos($content, "<li class=\"current\">") === false && $page != 1) {
				echo "Out of page<br/>";
				return $result;
			}

			$lines = explode("<li class=\"item", $content);
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
				echo "<h3>$description</h3>\n";
				return $description;
			}
			return "";
		}

		/* Get the price of the product based on the product code get from get_whole_product */
		public function get_price($content) {
			/* The case of special price product*/
			$index = strpos($content, "<span class=\"price\">");
			if ($index !== false) 
			{
				$content = substr($content, $index+strlen("<span class=\"price\">")+strlen("₱"));
				$content = substr($content, 0, strpos($content, "."));
				$content = str_replace(",","",$content);
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
			$index = strpos($content, "<table class=\"data-table\" id=\"product-attribute-specs-table\">");

			if ($index !== false) {
				$content 	= substr($content, $index+strlen("<table class=\"data-table\" id=\"product-attribute-specs-table\">"));
				$content 	= substr($content, 0, strpos($content, "</table>"));
				echo $content;
				return $content;
			}
			return "";
		}

		/* Get the list of image of the product using the content of the product detail page */
		public function get_extra_image($content) {
			$extra_images = array();
			$extra_images[] = "";

			$index = strpos($content, "<p class=\"product-image product-image-zoom\">");
			$content = substr($content, $index);

			if ($index === false) 
			{
				$content = substr($content, strpos($content, "<p class=\"product-image\">"));
				$content = substr($content, strpos($content, "src=\"")+strlen("src=\""));
				$content = substr($content, 0, strpos($content, "\""));
				$extra_images[] = $content;
			}
			else 
			{
				$content = substr($content, strpos($content, "src=\"") + strlen("src=\""));
				$image1 = substr($content, 0, strpos($content, "\""));

				$extra_images[] = $image1;
				echo "<img src=\"".$image1."\"/>";

				$index = strpos($content, "<div class=\"more-views\">");
				if ($index !== false) 
				{
					$content = substr($content, $index);
					$content = substr($content, 0, strpos($content, "</div>"));
					$content = explode("src=\"", $content);

					for ($i = 1; $i < sizeof($content); $i++)
					{
						$extra_images[] = str_replace("thumbnail/56x", "image", substr($content[$i], 0, strpos($content[$i], "\"")));
						//echo "<img src=\"".$extra_images[$i+1]."\"/>";
					}
				}
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

		 	$cat["mobiles"] = "http://www.wiredsystems.com/store/cameras-cellphones/cellphones.html";
			$cat["smartphone"] = "http://www.wiredsystems.com/store/cameras-cellphones/smartphones.html";
			$cat["laptop"] = "http://www.wiredsystems.com/store/computers/notebooks.html";
			$cat["netbook"] = "http://www.wiredsystems.com/store/computers/netbooks.html";
			$cat["desktop"] = "http://www.wiredsystems.com/store/computers/desktops.html";
			$cat["tablets"] = "http://www.wiredsystems.com/store/computers/tablets.html";
			$cat["camera"] = "http://www.wiredsystems.com/store/cameras-cellphones/point-shoot-cameras.html";
			$cat["professional_camera"] = "http://www.wiredsystems.com/store/cameras-cellphones/digital-slr-cameras.html";
			$cat["video_recorder"] = "http://www.wiredsystems.com/store/cameras-cellphones/video-cameras.html";
			$cat["tv"] = "http://www.wiredsystems.com/store/electronics/tv-video.html?cat=161";
			$cat["disk_player"] = "http://www.wiredsystems.com/store/electronics/tv-video.html?cat=395";
			$cat["phone_accessory"] = "http://www.wiredsystems.com/store/cameras-cellphones/cellphone-smartphone-accessories.html";
			$cat["tablet_accessory"] = "http://www.wiredsystems.com/store/computers/tablet-accessories.html";
			$cat["computer_accessory"] = "http://www.wiredsystems.com/store/computers/laptop-accessories.html";
			
			return $cat;
		}
	}

	// set_time_limit(5000);

	// $crawler = new wiredsystems();
	// $result = $crawler->get_whole_product_page("http://www.wiredsystems.com/store/cameras-cellphones/smartphones.html", 1);

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