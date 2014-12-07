<?php
include "general_display_function.php";

class index_display {
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $curlang;
	var $lang;
	var $l;

	function SetConfig($host, $user, $pass, $db, $lang)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
		$this->curlang = $lang->current_language();
		$this->lang = $lang;
		$this->l = $lang->term_index();
	}

	function display_head()
	{
		$this->display_title();
		$this->display_meta_properties();
		$this->display_stylesheet();
		$this->display_script();
	}

	function display_title()
	{
		echo "<title>";
		echo $this->l[$this->curlang]["title"];
		echo "</title>";
	}

	function display_meta_properties()
	{
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
		echo "<meta name=\"description\" content=\"".$this->l[$this->curlang]["description"]."\"/>\n";
		echo "<meta name=\"keywords\" content=\"".$this->l[$this->curlang]["keywords"]."\"/>\n";
		echo "<meta name=\"robots\" content=\"index, follow\" />\n";

		echo "<link rel=\"icon\" href=\"/favicon.ico\" type=\"image/x-icon\"/>\n";
		echo "<link rel=\"shortcut icon\" href=\"/favicon.ico\" type=\"image/x-icon\"/>\n"; 
		echo "<meta property=\"og:title\" content=\"".$this->l[$this->curlang]["title"]."\"/>\n";
		echo "<meta property=\"og:description\" content=\"".$this->l[$this->curlang]["description"]."\"/>\n";
	}

	function display_stylesheet()
	{
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/reset.css\" />\n";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/styles.css\" />\n";
		echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"css/thickbox.css\" />\n";
		echo "<!--[if IE]>\n";
		echo "<link href=\"css/ie.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
		echo "<![endif]-->\n";
	}

	function display_script()
	{
		echo "<script src=\"./js/jquery-1.8.2.js\"></script>\n";
		echo "<script type=\"text/javascript\">\n";
		echo "var active_menu_id = 'home';\n";
		echo "function detect_active(event){\n";
		echo "$('.active').attr('class', '');\n";
		echo "$(event.target).attr('class', 'active');\n";
		echo "}\n";
		echo "function remove_active(event){\n";
		echo "$('.active').attr('class', '');\n";
		echo "$('#'+active_menu_id).attr('class', 'active');\n";
		echo "}\n";

		echo "//function submit from search\n";
		echo "function sub(){\n";
		echo "document.forms[\"f_search\"].submit();\n";
		echo "}\n";
		echo "</script>\n";

		echo "<!-- Script to autofit categories height-->\n";
		echo "<script src=\"./js/thickbox.js\"></script>\n";
	}

	/* Helper function that displays products on the slider */
	function display_slider_images() {
		$sliderDB = new DBSlider();
		//Provide config setting
		$sliderDB->SetConfig($this->hostname, $this->username, $this->password, $this->db);

		for ($i = 1; $i <=4; $i++) {
			/* Get the information of the i-th slider from database */
			$result = $sliderDB->get($i);

			if ($result != null) {
				/* Print out the slider images */
				echo "<li>\n";
				echo "<a href=\"".$result[1]."\">";
				echo "<img src=\"".$result[2]."\" height=\"262\" width=\"550\" alt=\"".$result[3]."\">\n";
				echo "</a>\n";
				echo "</li>\n";
			}
		}
	}

	/* Helper function that displays the registration button */
	function display_registration()
	{
		echo "<div class=\"lastNews\">\n";
		echo "<div class=\"lastNewsBlue\"><a class=\"thickbox\" href=\"subscription.html?height=370&width=920&KeepThis=true&TB_iframe=true\">".$this->l[$this->curlang]["blue_signup"]."</a></div>\n";
		echo "<div class=\"lastNewsOrange\"><a class=\"thickbox\" href=\"subscription.html?height=370&width=920&KeepThis=true&TB_iframe=true\">".$this->l[$this->curlang]["orange_signup"]."</a></div>\n";	
		echo "</div>\n";
	}

	/* Helper function thats displays new products on homepage */
	function display_new_product() {
		$newProductsDB = new DBNewProducts();
		//Provide config setting
		$newProductsDB->SetConfig($this->hostname, $this->username, $this->password, $this->db);

		echo "<div class=\"menuProducts\">\n";
		echo "<div class=\"title\" style=\"width:952px\"><h1>".$this->l[$this->curlang]["new_product"]."</h1></div>\n";	
        echo "</div>\n";
		echo "<div class=\"itemsBox\">\n";

		for ($i = 1; $i <= 5; $i++) {
			$result = $newProductsDB->get($i);

			if ($result != null) {
				echo "<div class=\"items\">\n";
				echo "<div class=\"new\"></div>\n";
				echo "<div class=\"imageItems\"><a href=\"".$result[1]."\"><img src=\"".$result[2]."\" alt=\"".$result[3]."\" /></a></div>\n";
				echo "<div class=\"priceItems\">RM ".number_format($result[6], 2, '.', ',')."</div>\n";
				echo "<div class=\"contentItems\"><h2>".$result[5]."</h2></div>\n";
				echo "</div> <!--close items-->\n";
				if ($i != 5) {
					echo "<div class=\"lineGreyItems\"></div>\n";
				}
			}
		}
	}

	/* Helper function thats displays popular and hot deals on homepage */
	function display_popular_and_hot_deals() {
		$hotDealsDB = new DBHotDeals();
		//Provide config setting
		$hotDealsDB->SetConfig($this->hostname, $this->username, $this->password, $this->db);

		echo "<div class=\"menuProducts\">\n";
		echo "<div class=\"title\" style=\"width:952px;\"><h1>".$this->l[$this->curlang]["popular_product"]."</h1></div>\n";	

		$number_of_hot_deals = $hotDealsDB->get_total_hotdeal();
		for ($i = 1; $i <= $number_of_hot_deals; $i++) {
			// Every row
			if ($i % 5 == 1)
			{
				echo "</div>\n";
				echo "<div class=\"itemsBox\">\n";
			}

			$result = $hotDealsDB->get($i);

			if ($result != null) {
				echo "<div class=\"items\">\n";
				echo "<div class=\"new\"></div>\n";
				echo "<div class=\"imageItems\"><a href=\"".$result[1]."\"><img src=\"".$result[2]."\" alt=\"".$result[3]."\" /></a></div>\n";
				echo "<div class=\"priceItems\">RM ".number_format($result[6], 2, '.', ',')."</div>\n";
				echo "<div class=\"contentItems\"><h2>".$result[5]."</h2></div>\n";

				echo "</div> <!--close items-->\n";
				if ($i % 5 != 0) {
					echo "<div class=\"lineGreyItems\"></div>\n";
				}
			}
		}
	}

	/* Helper function thats displays product categories */
	function display_product_categories() {
		display_categories($this->hostname,$this->username,$this->password,$this->db,$this->curlang);
	}

	/* Helper function thats displays advertisement */
	function display_ad($pos) {
		$adsDB = new DBAds();
		//Provide config setting
		$adsDB->SetConfig($this->hostname, $this->username, $this->password, $this->db);

		$result = $adsDB->get($pos);

		/* Display ad on homepage first top right */
		if ($pos == 1) {
			echo "<li style=\"height:153px;margin-bottom:7px;\">\n";
			echo "<a href=\"".$result[1]."\" rel=\"nofollow\">\n";
			echo "<img width=\"209px\" height=\"153px\" src=\"".$result[2]."\" alt=\"".$result[3]."\"/>\n";
			echo "</a>\n";
			echo "</li>\n";
		} 

		/* Display ad on homepage second top right */
		if ($pos == 2) {
			echo "<li style=\"height:157px;\">\n";
			echo "<a href=\"".$result[1]."\" rel=\"nofollow\">\n";
			echo "<img width=\"209px\" height=\"153px\" src=\"".$result[2]."\" alt=\"".$result[3]."\"/>\n";
			echo "</a>\n";
			echo "</li>\n";
		}

		if ($pos == 3 || $pos == 4) {
			echo "<div class=\"AD".($pos-2)."\">\n";
			echo "<a href=\"".$result[1]."\" rel=\"nofollow\"><img src=\"".$result[2]."\" alt=\"".$result[3]."\" width=\"466\" height =\"88\"/>\n";
			echo "</a>\n";
			echo "</div>\n";
		}
	}

	/* Helper function that displays floating banner */
	function display_floating_banner() {
		$adsDB = new DBAds();
		//Provide config setting
		$adsDB->SetConfig($this->hostname, $this->username, $this->password, $this->db);

		$result = $adsDB->get(13);

		/* Display left floating banner */
		if ($result != null) {
			echo "<div class=\"floating-banner-left\">\n";
			echo "<a href=".$result[1]." target='_blank'><img style='cursor:pointer' src='".$result[2]."' alt='' width='150' height='600' /></a>\n";
			echo "</div>\n";
		}

		/* Display right floating banner */
		$result = $adsDB->get(14);
		if ($result != null) {
			echo "<div class=\"floating-banner-right\">\n";
			echo "<a href=".$result[1]." target='_blank'><img style='cursor:pointer' src='".$result[2]."' alt='' width='150' height='600' /></a>\n";
			echo "</div>\n";
		}
	}
}
?>