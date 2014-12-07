<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class enigma extends base_crawler {
		public function SetConfig($host, $user, $pass, $db)
		{
			$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->DBSite = new DBCrawler();
			$this->name = "enigma";
			$this->DBSite->SetConfig($host, $user, $pass, $db, $this->name);
			$this->curl_flag = true;
		}

		public function get_whole_product_page($link, $page) {
			/* Array of results*/
			$result = array(); 
			/* Iterate through each page and only stop when there is no product left */
			$linkpage = trim($link."&offset=".$page);//floor(($page-1)/6+1));
			echo "linkpage = ".$linkpage;
			$content = get_content($linkpage, $this->curl_flag);
			$content = substr($content, strpos($content, "<table"));
			$content = substr($content, 0, strpos($content, "</table"));
			$lines = explode("<li>", $content);
			echo "size = ".sizeof($lines);

			$lowerbound = 0; //(($page-1)%6)*8; 
			$upperbound = sizeof($lines)-1;//(($page-1)%6+1)*8;
			if ($upperbound > sizeof($lines)) $upperbound = sizeof($lines)-1;
			/* Go through all the product and add them to the results */
			for ($i = $lowerbound+1; $i < $upperbound+1; $i++) $result[] = $lines[$i]; 

			return $result;
		}
		
		/* Get the image of the product based on the product code get from get_whole_product */
		public function get_image($description) {
			$index = strpos($description, "<img class=\"rb\" src=\"");
			if ($index !== false) {
				$description = substr($description, $index+strlen("<img class=\"rb\" src=\""));
				$description = substr($description, 0, strpos($description, "\""));
				$description = "http://enigma-phil.com.ph/".$description;
				echo "<img src =\"".$description."\"/>\n";
				return $description;
			}
		}
		
		/* Get the name of the product based on the product code get from get_whole_product */
		public function get_name($content) {
			$index = strpos($content, "<span class=\"prodname\">");
			if ($index !== false) {
				$content = substr($content, $index+strlen("<span class=\"prodname\">"));
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
				$description = "http://enigma-phil.com.ph/".$description;
				echo "<h3>$description</h3>\n";
				return $description;
			}
			return "";
		}

		/* Get the price of the product based on the product code get from get_whole_product */
		public function get_price($content) {
			/* The case of special price product*/
			$index = strrpos($content, "<strong>");
			if ($index !== false) 
			{
				$content = substr($content, $index+strlen("<strong>"));
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
			$index = strpos($content, "<tbody>");

			if ($index !== false) {
				$content 	= substr($content, $index+strlen("<tbody>"));
				$content 	= substr($content, 0, strpos($content, "</tbody>"));
				echo $content;
				return $content;
			}
			return "";
		}

		/* Get the list of image of the product using the content of the product detail page */
		public function get_extra_image($content) {
			$extra_images = explode("<img class=\"prod_img\" src=\"", $content);
			/* Add image into the results */
			for ($i = 1; $i < sizeof($extra_images); $i ++) {
				$extra_images[$i] = substr($extra_images[$i], 0, strpos($extra_images[$i], "\""));
				$extra_images[$i] = "http://enigma-phil.com.ph/".$extra_images[$i];
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

			$cat["laptop1"] = "http://enigma-phil.com.ph/?page=prodcategory&id=5";
			$cat["laptop2"] = "http://enigma-phil.com.ph/?page=prodcategory&id=4";
			$cat["desktop1"] = "http://enigma-phil.com.ph/?page=prodcategory&id=2";
			$cat["desktop2"] = "http://enigma-phil.com.ph/?page=prodcategory&id=74";
			$cat["desktop3"] = "http://enigma-phil.com.ph/?page=prodcategory&id=76";
			$cat["mobiles"] = "http://enigma-phil.com.ph/?page=prodcategory&id=15";
			$cat["tablets"] = "http://enigma-phil.com.ph/?page=prodcategory&id=16";
			$cat["camera"] = "http://enigma-phil.com.ph/?page=prodcategory&id=17";
			$cat["software1"] = "http://enigma-phil.com.ph/?page=prodcategory&id=24";
			$cat["software2"] = "http://enigma-phil.com.ph/?page=prodcategory&id=25";
			$cat["software3"] = "http://enigma-phil.com.ph/?page=prodcategory&id=26";
			$cat["monitor"] = "http://enigma-phil.com.ph/?page=prodcategory&id=50";
			$cat["tv"] = "http://enigma-phil.com.ph/?page=prodcategory&id=51";

			return $cat;
		}
	}

	set_time_limit(5000);

	// $crawler = new enigma();
	// $result = $crawler->get_whole_product_page("http://enigma-phil.com.ph/?page=prodcategory&id=5", 1);

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