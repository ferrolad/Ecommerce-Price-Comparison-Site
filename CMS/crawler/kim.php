<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class kim {
		var $hostname;
		var $username;
		var $password;
		var $db;
		var $DBSite;
		var $curl_flag;

		public function SetConfig($host, $user, $pass, $db)
		{
			$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->DBSite = new DBCrawler();
			$this->DBSite->SetConfig($host, $user, $pass, $db, "kim");
			$this->curl_flag = true;
		}

		public function get_whole_product($link) {
			$page = 1;
			/* Array of results*/
			$result = array(); 
			/* Iterate through each page and only stop when there is no product left */
			while (true) {
				$lines = $this->get_whole_product_page($link, $page);
				/* If the page doesn't contain any product, break out of the loop */
				if (sizeof($lines) == 0) break;
				/* Go through all the product and add them to the results */
				for ($i = 1; $i < sizeof($lines); $i++) $result[] = $lines[$i]; 
				$page++;
			}
			return $result;
		}

		public function get_whole_product_page($link, $page) {
			/* Array of results*/
			$result = array(); 
			/* Iterate through each page and only stop when there is no product left */
			$content = get_content($link, $this->curl_flag);
			$lines = explode("<li class=\"product-category", $content);
			$linkpage = substr($lines[($page-1)%5+1], strpos($lines[($page-1)%5+1], "href=\""));
			$linkpage = substr($linkpage, 0, strpos($linkpage, "\""));
			echo "linkpage = $linkpage <br/>";
			$content = get_content($linkpage, $this->curl_flag);
			$num_pages = explode("page-numbers' href='", $content);

			if ($page > $num_pages)
			{
				
			}

			if ($upperbound > sizeof($lines)) $upperbound = sizeof($lines)-1;
			/* Go through all the product and add them to the results */
			for ($i = $lowerbound+1; $i < $upperbound+1; $i++) $result[] = $lines[$i]; 

			return $result;
		}
		
		/* Get the image of the product based on the product code get from get_whole_product */
		public function get_image($description) {
			$index = strpos($description, "data-src=\"");
			if ($index !== false) {
				$description = substr($description, $index+strlen("data-src=\""));
				$description = substr($description, 0, strpos($description, "\""));
				echo "<img src =\"".$description."\"/>\n";
				return $description;
			}
		}
		
		/* Get the name of the product based on the product code get from get_whole_product */
		public function get_name($content) {
			$index = strpos($content, "<title>");
			if ($index !== false) {
				$content = substr($content, $index+strlen("<title>"));
				$content = substr($content, 0, strpos($content, "</title>"));
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
				$description = "http://www.zalora.com.ph".$description;
				echo "<h3>$description</h3>\n";
				return $description;
			}
			return "";
		}

		/* Get the price of the product based on the product code get from get_whole_product */
		public function get_price($content) {
			/* The case of special price product*/
			$index = strrpos($content, "\"gr:hasCurrencyValue\">");
			if ($index !== false) 
			{
				$content = substr($content, $index+strlen("\"gr:hasCurrencyValue\">"));
				// If the product has discount price 
				$index = strrpos($content, "\"gr:hasCurrencyValue\">");
				if ($index !== false) 
				{
					$content = substr($content, $index+strlen("\"gr:hasCurrencyValue\">"));
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
			$index = strpos($content, "id=\"productDesc\">");

			if ($index !== false) {
				$content 	= substr($content, $index+strlen("id=\"productDesc\">"));
				$content 	= substr($content, 0, strpos($content, "</div>"));
				echo $content;
				return $content;
			}
			return "";
		}

		/* Get the list of image of the product using the content of the product detail page */
		public function get_extra_image($content) {
			$extra_images = explode("data-image-big=\"", $content);
			/* Add image into the results */
			for ($i = 1; $i < sizeof($extra_images); $i ++) {
				$extra_images[$i] = substr($extra_images[$i], 0, strpos($extra_images[$i], "\""));
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

		public function add_categories($cat, $page) {
			$count = 0;
			if ($cat == 'female_high_heel_shoes') {
			 	$count += $this->add_category("female_high_heel_shoes", "http://www.zalora.com.ph/women/shoes/heels/", $page);
			} else if ($cat == 'female_ballet_shoes') {
				$count += $this->add_category("female_ballet_shoes", "http://www.zalora.com.ph/women/shoes/flats/", $page);
			} else if ($cat == 'female_sandals') {
				$count += $this->add_category("female_sandals", "hhttp://www.zalora.com.ph/women/shoes/slippers-flip-flops/", $page);
			} else if ($cat == 'female_dress') {
				$count += $this->add_category("female_dress", "http://www.zalora.com.ph/women/clothing/dresses/", $page);
			} else if ($cat == 'female_shirt') {
				$count += $this->add_category("female_shirt", "http://www.zalora.com.ph/women/clothing/tops/", $page);
			} else if ($cat == 'female_pants') {
				$count += $this->add_category("female_pants", "http://www.zalora.com.ph/women/clothing/pants/", $page);
			} else if ($cat == 'female_bag') {
				$count += $this->add_category("female_bag", "http://www.zalora.com.ph/women/bags/bags-women/?sort=popularity&dir=desc", $page);
			} else if ($cat == 'male_casual_shoes') {
				$count += $this->add_category("male_casual_shoes", "http://www.zalora.com.ph/men/shoes/boat-shoes/", $page);
			} else if ($cat == 'male_slip_on_shoes') {
				$count += $this->add_category("male_slip_on_shoes", "http://www.zalora.com.ph/men/shoes/loafers/", $page);
			} else if ($cat == 'male_canvas_shoes') {
				$count += $this->add_category("male_canvas_shoes", "http://www.zalora.com.ph/men/shoes/sneakers/", $page);
			} else if ($cat == 'male_shirt') {
				$count += $this->add_category("male_shirt", "http://www.zalora.com.ph/men/clothing/polos/?sort=popularity&dir=desc", $page);
			} else if ($cat == 'male_tshirt') {
				$count += $this->add_category("male_tshirt", "http://www.zalora.com.ph/men/clothing/t-shirts/?sort=popularity&dir=desc", $page);
			} else if ($cat == 'male_jeans') {
				$count += $this->add_category("male_jeans", "http://www.zalora.com.ph/men/clothing/jeans/?sort=popularity&dir=desc", $page);
			} else if ($cat == 'male_accessories') {
				$count += $this->add_category("male_accessories", "http://www.zalora.com.ph/men/accessories/watches/?sort=popularity&dir=desc", $page);
			} 
			return $count;
		}

		/* Download the image down to local */
		public function get_images($category) {
			$page = 0;
			while (true) {
				$page++;
				$count = $this->get_images_page($category, $page);
				if ($count == 0) break;
			}
		}

		/* Download the image down to local */
		public function get_images_page($category, $page) {
			//Get the list of image from the specific category
			$db = $this->DBSite;
			$count = get_images_page_website($category, $page, "kim", $db);
			return $count;
		}

		/* Update prices of products in that respective category */
		public function update_price($category) {
			$page = 0;
			while (true) {
				$page++;
				$count = $this->update_price_page($category, $page);
				if ($count == 0) break;
			}
		}

		/* Update prices of products in that respective category in respective pages (each page contains 
			20 product) */
		public function update_price_page($category, $page) {
			$db = $this->DBSite;
			$count = update_price_page_website($category, $page, $db, $this, $this->curl_flag);
			return $count;
		}
	}

	set_time_limit(5000);

	// $crawler = new zalora();
	// $result = $crawler->get_whole_product_page("http://www.zalora.com.ph/women/shoes/flats/", 1);

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