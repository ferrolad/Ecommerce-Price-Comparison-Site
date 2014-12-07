<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class weemall extends base_crawler {
		public function SetConfig($host, $user, $pass, $db)
		{
			$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->DBSite = new DBCrawler();
			$this->name = "weemall";
			$this->DBSite->SetConfig($host, $user, $pass, $db, $this->name);
			$this->curl_flag = true;
		}

		public function get_whole_product_page($link, $page) {
			/* Array of results*/
			$result = array(); 
			/* Iterate through each page and only stop when there is no product left */
			$linkpage = trim($link."?pageindex=".floor(($page-1)/6+1));
			echo "linkpage = ".$linkpage;
			$content = get_content($linkpage, $this->curl_flag);
			$lines = explode("<td class=\"item-box\">", $content);
			echo "size = ".sizeof($lines);

			$lowerbound = (($page-1)%6)*4; 
			$upperbound = (($page-1)%6+1)*4;
			if ($upperbound > sizeof($lines)) $upperbound = sizeof($lines)-1;
			/* Go through all the product and add them to the results */
			for ($i = $lowerbound+1; $i < $upperbound+1; $i++) $result[] = $lines[$i]; 

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
			$index = strpos($content, "<h2 class=\"weemall-productname\">");
			if ($index !== false) {
				$content = substr($content, $index+strlen("<h2 class=\"weemall-productname\">"));
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
			$index = strpos($content, "<span class=\"price-val");
			if ($index !== false) 
			{
				$content = substr($content, $index+strlen("<span class=\"price-val"));
				// If the product has discount price 
				$index = strpos($content, ">");
				if ($index !== false) 
				{
					$content = substr($content, $index+strlen(">"));
				}

				$content = trim($content);
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
			$index = strpos($content, "<div class=\"fulldescription\">");

			if ($index !== false) {
				$content 	= substr($content, $index+strlen("<div class=\"fulldescription\">"));
				$content 	= substr($content, 0, strpos($content, "<div class=\"clear\">"));
				//echo $content;
				return $content;
			}
			return "";
		}

		/* Get the list of image of the product using the content of the product detail page */
		public function get_extra_image($content) {
			$extra_images = explode("<a id='prodimg", $content);

			if (sizeof($extra_images) == 1)
			{
				$content = substr($content, strpos($content, "<div class=\"weemall-picture\">"));
				$content = substr($content, strpos($content, "src=\"")+strlen("src=\""));
				$content = substr($content, 0, strpos($content, "\""));
				$extra_images[] = "";
				$extra_images[] = $content;
			}
			else
			{
				for ($i = 1; $i < sizeof($extra_images); $i++)
				{
					$extra_images[$i] = substr($extra_images[$i], strpos($extra_images[$i], "href=\"")+strlen("href=\""));
					$extra_images[$i] = substr($extra_images[$i], 0, strpos($extra_images[$i], "\""));
				}
			}

			for ($i = 1; $i < 2; $i++)
			//for ($i = 1; $i < sizeof($extra_images); $i++)
			{
				echo "<img src= \"".$extra_images[$i]."\">";
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

			$cat["mobiles"] = "http://www.weemall.com/category/62-cellphones-accessories.aspx";
			$cat["camera"] = "http://www.weemall.com/category/63-camera-photo-video-accessories.aspx";
			$cat["headphone"] = "http://www.weemall.com/category/66-headphones-earphones.aspx";
			$cat["tv"] = "http://www.weemall.com/category/72-tv-video-players.aspx";
			$cat["speaker"] = "http://www.weemall.com/category/68-home-audio-theater.aspx";
			$cat["computer_accessory"] = "http://www.weemall.com/category/152-bags-cases-for-laptop-camera.aspx";
			$cat["female_bag"] = "http://www.weemall.com/category/153-handbags-purses.aspx";
			$cat["male_shirt"] = "http://www.weemall.com/category/356-bottoms.aspx";
			$cat["male_pants"] = "http://www.lazada.vn/dien-thoai-di-dong-moi/";
			$cat["male_accessories"] = "http://www.weemall.com/category/357-accessories.aspx";
			$cat["female_shirt"] = "http://www.weemall.com/category/358-tops.aspx";
			$cat["female_pants"] = "http://www.weemall.com/category/359-bottoms.aspx";
			$cat["female_accessories"] = "http://www.weemall.com/category/360-accessories.aspx";

			return $cat;
		}
	}

	set_time_limit(5000);

	// $crawler = new weemall();
	// $result = $crawler->get_whole_product_page("http://www.weemall.com/category/62-cellphones-accessories.aspx", 5);

	// //Get the name, the image, the price, and the link of each product and add them to the database
	// $link = $crawler->get_link($result[3]);
	// $image = $crawler->get_image($result[3]);
	// $content = get_content($link, true);
	// $comment = "";
	// $price = $crawler->get_price($content);
	// $name = $crawler->get_name($content);
	// $description = $crawler->get_description($content);
	// $extraimage = $crawler->get_extra_image($content);
?>	
</body>
</html>