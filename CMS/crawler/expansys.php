<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class expansys extends base_crawler {
		public function SetConfig($host, $user, $pass, $db)
		{
			$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->DBSite = new DBCrawler();
			$this->name = "expansys";
			$this->DBSite->SetConfig($host, $user, $pass, $db, $this->name);
			$this->curl_flag = true;
		}

		public function get_whole_product_page($link, $page) {
			/* Array of results*/
			$result = array(); 
			/* Iterate through each page and only stop when there is no product left */
			$linkpage = $link."?page=".$page;
			echo "linkpage = ".$linkpage;
			$content = get_content($linkpage, $this->curl_flag);

			if (strpos($content, $page."</a>") === false)
			{
				return $result;
			}

			$lines = explode("class=\"item c", $content);
			echo "size = ".sizeof($lines);

			if (sizeof($lines) == 1)
			{
				$lines = explode("<tr>", $content);
				echo "size = ".sizeof($lines);				
			}

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
			$index = strpos($content, "<h1 itemprop=\"name\">");
			if ($index !== false) {
				$content = substr($content, $index+strlen("<h1 itemprop=\"name\">"));
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
				$description = "http://www.expansys.ph/".$description;
				echo "<h3>$description</h3>\n";
				return $description;
			}
			return "";
		}

		/* Get the price of the product based on the product code get from get_whole_product */
		public function get_price($content) {
			/* The case of special price product*/
			$content = substr($content, strpos($content, "<li class=\"price\">"));
			$content = substr($content, 0, strpos($content, "</li>"));

			$index = strpos($content, "price\">PHP");
			if ($index !== false) 
			{
				$content = substr($content, $index+strlen("price\">PHP"));
				$content = substr($content, 0, strpos($content, "<"));
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
			$index = strpos($content, "<span itemprop=\"description\">");

			if ($index !== false) {
				$content 	= substr($content, $index+strlen("<span itemprop=\"description\">"));
				$content 	= substr($content, 0, strpos($content, "</div>"));
				echo $content;
				return $content;
			}
			return "";
		}

		/* Get the list of image of the product using the content of the product detail page */
		public function get_extra_image($content) {
			$content = substr($content, strpos($content, "<div id=\"image\">"));
			$content = substr($content, strpos($content, "rel='") + strlen("rel='"));
			$content = substr($content, 0, strpos($content, "'"));

			$extra_images = array();
			$extra_images[] = "";
			$extra_images[] = $content;

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

			$cat["mobiles"] = "http://www.expansys.ph/mobile-phones/";
			$cat["tablets"] = "http://www.expansys.ph/tablet-pcs+ipads/";
			$cat["camera"] = "http://www.expansys.ph/cameras/";
			$cat["speaker"] = "http://www.expansys.ph/audio/docking+speakers/";
			$cat["headphone"] = "http://www.expansys.ph/audio/headphones/";
			$cat["phone_accessory"] = "http://www.expansys.ph/mobile-phones/phone-accessories/";
			$cat["tablet_accessory"] = "http://www.expansys.ph/tablet-pcs+ipads/tablet-accessories/";
			$cat["male_accessories_watch"] = "http://www.expansys.ph/watches/";

			return $cat;
		}
	}

	set_time_limit(5000);

	// $crawler = new expansys();
	// $result = $crawler->get_whole_product_page("http://www.expansys.ph/mobile-phones/", 1);

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