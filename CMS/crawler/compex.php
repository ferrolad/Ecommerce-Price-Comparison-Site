<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class compex extends base_crawler {
		public function SetConfig($host, $user, $pass, $db)
		{
			$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->DBSite = new DBCrawler();
			$this->name = "compex";
			$this->DBSite->SetConfig($host, $user, $pass, $db, $this->name);
			$this->curl_flag = true;
		}

		public function get_whole_product_page($link, $page) {
			/* Array of results*/
			$result = array(); 
			/* Iterate through each page and only stop when there is no product left */
			$linkpage = $link;
			echo "linkpage = ".$linkpage;
			$content = get_content($linkpage, $this->curl_flag);
			$lines = explode("<td class=\"prdborder1\">", $content);
			echo "size = ".sizeof($lines);

			$lowerbound = (($page-1)%50)*4; 
			$upperbound = (($page-1)%50+1)*4;
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
				$description = "http://compex.com.ph/" . $description;
				echo "<img src =\"".$description."\"/>\n";
				return $description;
			}
		}
		
		/* Get the name of the product based on the product code get from get_whole_product */
		public function get_name($content) {
			$index = strpos($content, "productname");
			if ($index !== false) {
				$content = substr($content, $index);
				$content = substr($content, strpos($content, ">")+strlen(">"));
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
				$description = "http://compex.com.ph/".$description;
				echo "<h3>$description</h3>\n";
				return $description;
			}
			return "";
		}

		/* Get the price of the product based on the product code get from get_whole_product */
		public function get_price($content) {
			/* The case of special price product*/
			$index = strrpos($content, "PRICE: &#8369 ");
			if ($index !== false) 
			{
				$content = substr($content, $index+strlen("PRICE: &#8369 "));
				$content = substr($content, 0, strpos($content, "."));
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
			$index = strpos($content, "<td style=\"color:#000\">");

			if ($index !== false) {
				$content 	= substr($content, $index+strlen("<td style=\"color:#000\">"));
				$content 	= substr($content, 0, strpos($content, "</td>"));
				echo $content;
				return $content;
			}
			return "";
		}

		/* Get the list of image of the product using the content of the product detail page */
		public function get_extra_image($content) {
			$extra_images = explode("<img id=\"mainprod\" src=\"", $content);
			/* Add image into the results */
			for ($i = 1; $i < sizeof($extra_images); $i ++) {
				$extra_images[$i] = substr($extra_images[$i], 0, strpos($extra_images[$i], "\""));
				$extra_images[$i] = "http://compex.com.ph/".$extra_images[$i];
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

			$cat["mobiles1"] = "http://compex.com.ph/products.php?cat_id=70&cols=3";
			$cat["mobiles2"] = "http://compex.com.ph/products.php?cat_id=64&cols=3";
			$cat["mobiles3"] = "http://compex.com.ph/products.php?cat_id=133&cols=3";
			$cat["mobiles4"] = "http://compex.com.ph/products.php?cat_id=93&cols=3";
			$cat["mobiles5"] = "http://compex.com.ph/products.php?cat_id=102&cols=3";
			$cat["mobiles6"] = "http://compex.com.ph/products.php?cat_id=69&cols=3";
			$cat["mobiles7"] = "http://compex.com.ph/products.php?cat_id=67&cols=3";
			$cat["mobiles8"] = "http://compex.com.ph/products.php?cat_id=63&cols=3";
			$cat["mobiles9"] = "http://compex.com.ph/products.php?cat_id=65&cols=3";
			
			$cat["desktop1"] = "http://compex.com.ph/products.php?cat_id=80";
			$cat["desktop2"] = "http://compex.com.ph/products.php?cat_id=79";
			$cat["desktop3"] = "http://compex.com.ph/products.php?cat_id=98";
			$cat["desktop4"] = "http://compex.com.ph/products.php?cat_id=131";

			$cat["laptop1"] = "http://compex.com.ph/products.php?cat_id=71";
			$cat["laptop2"] = "http://compex.com.ph/products.php?cat_id=76";
			$cat["laptop3"] = "http://compex.com.ph/products.php?cat_id=73";
			$cat["laptop4"] = "http://compex.com.ph/products.php?cat_id=72";
			$cat["laptop5"] = "http://compex.com.ph/products.php?cat_id=140";
			$cat["laptop6"] = "http://compex.com.ph/products.php?cat_id=130";

			$cat["camera"] = "http://compex.com.ph/products.php?cat_id=3&cols=2";
			
			return $cat;
		}
	}

	set_time_limit(5000);

	// $crawler = new compex();
	// $result = $crawler->get_whole_product_page("http://compex.com.ph/products.php?cat_id=73", 1);

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