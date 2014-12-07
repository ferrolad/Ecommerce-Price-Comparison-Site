<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class villman extends base_crawler {
		public function SetConfig($host, $user, $pass, $db)
		{
			$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->DBSite = new DBCrawler();
			$this->name = "villman";
			$this->DBSite->SetConfig($host, $user, $pass, $db, $this->name);
			$this->curl_flag = true;
		}

		public function get_whole_product_page($link, $page) {
			/* Array of results*/
			$result = array(); 
			/* Iterate through each page and only stop when there is no product left */
			$linkpage = trim($link."/".(30*($page-1)));
			if ($linkpage == 1) $linkpage = $link;
			echo "linkpage = ".$linkpage;

			$content = get_content($linkpage, $this->curl_flag);
			$lines = explode("<span class=\"num\">", $content);
			echo "size = ".sizeof($lines);

			/* Go through all the product and add them to the results */
			for ($i = 1; $i < sizeof($lines); $i++) $result[] = $lines[$i]; 

			return $result;
		}
		
		/* Get the image of the product based on the product code get from get_whole_product */
		public function get_image($description) {
			return "There is no image";
		}
		
		/* Get the name of the product based on the product code get from get_whole_product */
		public function get_name($content) {
			$index = strpos($content, "<h1 id=\"h1productname\">");
			if ($index !== false) {
				$content = substr($content, $index+strlen("<h1 id=\"h1productname\">"));
				$content = substr($content, 0, strpos($content, "</h1>"));
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
				$description = "http://www.villman.com".$description;
				echo "<h3>$description</h3>\n";
				return $description;
			}
			return "";
		}

		/* Get the price of the product based on the product code get from get_whole_product */
		public function get_price($content) {
			/* The case of special price product*/
			$index = strrpos($content, "<td>P ");
			if ($index !== false) 
			{
				$content = substr($content, $index+strlen("<td>P "));
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
			$index = strpos($content, "<!--INFOLINKS_ON-->");

			if ($index !== false) {
				$content 	= substr($content, $index+strlen("<!--INFOLINKS_ON-->"));
				$content 	= substr($content, 0, strpos($content, "<!--INFOLINKS_OFF-->"));
				echo "Description length = ".strlen($content);
				return $content;
			}
			return "";
		}

		/* Get the list of image of the product using the content of the product detail page */
		public function get_extra_image($content) {
			$extra_images = explode("<div id=\"product_details_right\">", $content);
			
			$extra_images[1] = substr($extra_images[1], strpos($extra_images[1], "</div>"));
			$extra_images[1] = substr($extra_images[1], strpos($extra_images[1], "src=\"")+strlen("src=\""));
			$extra_images[1] = substr($extra_images[1], 0, strpos($extra_images[1], "\""));
			$extra_images[1] = "http://www.villman.com".$extra_images[1];

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

			$cat["laptop"] = "http://www.villman.com/Category/Notebook-PCs";
			$cat["desktop"] = "http://www.villman.com/Category/Desktop-PCs";
			$cat["tablets"] = "http://www.villman.com/Category/Tablet-PCs";
			$cat["mouse"] = "http://www.villman.com/Category/Mouse";
			$cat["keyboard"] = "http://www.villman.com/Category/Keyboards";
			$cat["monitor"] = "http://www.villman.com/Category/LCD-Monitors";
			$cat["tv_lcd"] = "http://www.villman.com/Category/LCD-TVs";
			$cat["tv_crt"] = "http://www.villman.com/Category/CRT-Monitors";
			$cat["computer_accessory"] = "http://www.villman.com/Category/Notebook-Accessories";
			$cat["speaker"] = "http://www.villman.com/Category/Speakers";
			$cat["headphone"] = "http://www.villman.com/Category/Headphones-Headsets";
			$cat["camera"] = "http://www.villman.com/Category/Digital-Cameras";
			$cat["mp3"] = "http://www.villman.com/Category/Digital-Audio-Players";
			$cat["software"] = "http://www.villman.com/Category/Software";
			
			return $cat;
		}
	}

	set_time_limit(5000);

	// $crawler = new villman();
	// $result = $crawler->get_whole_product_page("http://www.villman.com/Category/Notebook-PCs", 1);

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