<?php
include "./display/general_display_function.php";

class search_display
{
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $curlang;
	var $lang;
	var $l;
	var $result;
	var $suggested;
	var $current_language;
	var $order;
	var $page;
	var $term;
	var $website;
	var $category;
	var $subcategory;
	var $rating;
	var $lower_price;
	var $upper_price;
	var $outofstock;
	/* Websites contains result */
	var $result_website;
	/* Categories contains result */
	var $result_cat;
	/* Category and subcategory name after remove signs */
	var $cat_remove_sign;
	var $subcat_remove_sign;
	/* Beginning part of Url extension with cat and subcat */
	var $extension;

	function SetConfig($host, $user, $pass, $db, $term, $category, $subcategory, $brand, $lower_price, $upper_price, $website, $rating, $order, $page, $outofstock, $lang)
	{
		/* The search term */
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
		$this->curlang = $lang->current_language();
		$this->lang = $lang;
		$this->l = $lang->term_search();
		$this->page = $page;
		$this->rating = $rating;
		$this->lower_price = $lower_price;
		$this->upper_price = $upper_price;
		$this->outofstock = $outofstock;

		/* Search term */
		$term = trim(str_replace("%","",$term));
		$this->term = $term;

		/* Search category */
		$category = trim(str_replace("%","",$category));
		$this->category = $category;
		$this->cat_remove_sign = cat_remove_sign_english($category);
		
		/* The search subcategory */
		$subcategory = trim(str_replace("%","",$subcategory));
		$this->subcategory = $subcategory;
		$this->subcat_remove_sign = subcat_remove_sign_english($subcategory);

		/* The search website */
		$website = trim(str_replace("%","",$website));
		$this->website = $website;

		// Prepare necessary database
		$allProductDB = new DBAllProduct();
		$allProductDB->SetConfig($host, $user, $pass, $db);

		/* If the search is empty or doesn't contain any meaningful search criteria, return empty result */
		if (empty($term) && empty($category) && empty($subcategory) && empty($website)){
			$this->result = array();
			$this->result_cat = array();
			$this->result_website = array();
		} else {	

			$searchstatsDB = new DBSearchStats();
			$searchstatsDB->SetConfig($host, $user, $pass, $db);

			$website_list = website_list();
			
			/* The search lower price */
            if (!($lower_price <> "" && is_numeric($lower_price))) {
				$this->lower_price = -1;
			}

			/* The search upper price */
            if (!($upper_price <> "" && is_numeric($upper_price))) {
				$this->upper_price = 1000000000;
			}

			$this->result = array();
			/* Iterate through the result to get rids of bad result */
			$result_website = result_website();
			$result_cat = result_cat();

			// Get all the models
			$resultArray = $allProductDB->get_model_product($term, $category, $subcategory, $website, $lower_price, $upper_price, $order, $website_list, $page, $outofstock);
			$this->result = $resultArray;

			// Get all the non-specified-model products
			$resultArray = $allProductDB->get_search_product($term, $category, $subcategory, $website, $lower_price, $upper_price, $order, $website_list, $page, $outofstock);

			for ($i = 0; $i < sizeof($resultArray); $i++) {
				$row = $resultArray[$i];
				/* If the image of the product is invalid, ignore the product */
				$this->result[] = $row;

				/* Calculate resulted websites */
				$result_website[$row["website"]]++;

				/* Calculate resulted category */
				$result_cat[$row["cat1"]]++;
			}

			/* Store the result website */
			$this->result_website = $result_website;
			/* Store the result categories */
			$this->result_cat = $result_cat;
			/* Url extension */
			$extension = "";
			if ($this->cat_remove_sign != "") {
				$extension .= $this->cat_remove_sign."/";
				if ($this->subcat_remove_sign == "") {
					$extension .= "&";
				}
			}
			if ($this->subcat_remove_sign != "") {
				$extension .= $this->subcat_remove_sign."/&";
			}
			if ($extension == "") {
				$extension = "search.php?";
			}
			$this->extension = $extension;
			$searchstatsDB->add($this->term, $this->category, $this->subcategory, $this->website, $this->lower_price, $this->upper_price);
		}

		/* Get the suggested product list */
		$this->suggested = $allProductDB->get_suggested_product($term, 0);
	}

    function display_menu_height()
    {
		$height = 237;
		foreach ($this->result_website as $key=>$value) {
			if ($value > 0) {
				$height += 30;
			}
		}
		echo "\$theDiv.css('height', '".$height."px');";
	}

	/* Helper function that display the keyword, description, title part of the html header*/
    function display_title_keyword_description_header()
    {
		$title = search_title($this->category, $this->subcategory);
		$description = search_description($this->category, $this->subcategory);
		$keyword = search_keyword($this->category, $this->subcategory);

		echo "<title>".$title."</title>\n";
		echo "  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">\n";
		echo "  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n";
		echo "  <meta name=\"description\" content=\"".$description."\" />\n";
		echo "  <meta name=\"keywords\" content=\"".$keyword."\" />\n";

		if ($this->outofstock != "false" || !empty($this->order) || !empty($this->website) || !empty($this->term) || $this->lower_price != -1 || $this->upper_price != 1000000000)
		{
			echo "  <meta name=\"robots\" content=\"noindex,follow\" />\n";	
		}
		else
		{
			echo "  <meta name=\"robots\" content=\"index,follow\" />\n";
		}
		
		echo "  <meta property=\"og:title\" content=\"".$title."\"/>\n";
		echo "  <meta property=\"og:type\" content=\"product\"/>\n";
		echo "  <meta property=\"og:description\" content=\"".$description."\">\n";
	}

	/* Helper function that displays top bar the search page */
    function display_top_bar()
    {
		echo "<div style=\"float:left; margin-top:7px;\">\n";
		echo "<span class=\"text_yellow\" style=\"font-size:14px; line-height:18px\">\n";

		echo $this->l[$this->curlang]["search_result"]." '".$this->term."'</span><br />\n";
		echo "<span class=\"text_yellow\" style=\"font-size:14px; line-height:18px\">\n";
		echo sizeof($this->result)."\n";
		echo "</span>".$this->l[$this->curlang]["product_found"]."</div>\n";
	}

	/* Helper function that display the left ad on the search page */
    function display_ad($pos)
    {
		$adsDB = new DBAds();
			//Provide config setting
		$adsDB->SetConfig($this->hostname, $this->username, $this->password, $this->db);
		$result = $adsDB->get($pos);

		if ($pos == 5) {
			echo "<div id=\"AD_left_search\">\n";
            echo "<a href=\"" . $result[1] . "\" rel=\"nofollow\"><img src=\"" . $result[2] . "\" align=\"left\" alt=\"" . $result[3] . "\" title=\"" . $result[3] . "\"/></a>\n";
			echo "</div>\n";
		}

		if ($pos == 6 || $pos == 7) {
			echo "<div class=\"AD".($pos-5)."\">\n";
            echo "<a href=\"" . $result[1] . "\" rel=\"nofollow\"><img src=\"" . $result[2] . "\" alt=\"" . $result[3] . "\" title=\"" . $result[3] . "\" width=\"468\", height=\"90\"/>\n";
			echo "</a>\n";
			echo "</div>\n";
		}
	}

	/* Helper function thats displays product categories */
    function display_product_categories()
    {
		display_categories($this->hostname,$this->username,$this->password,$this->db, $this->current_language);
	}

	/* Helper function that display products of the search result */
    function display_search_result($page)
    {
		$result = $this->result;

		// Prepare necessary database
		$commentDB = new DBComment();
		$commentDB->SetConfig($this->hostname, $this->username, $this->password, $this->db);

		$brandDB = new DBBrandModel();
		$brandDB->SetConfig($this->hostname, $this->username, $this->password, $this->db);

		$allProductDB = new DBAllProduct();
		$allProductDB->SetConfig($this->hostname, $this->username, $this->password, $this->db);

		$currency = $this->l[$this->curlang]["currency"];
		$numsigfig = $this->l[$this->curlang]["num_sigfig"];
		$decsep = $this->l[$this->curlang]["dec_separator"];
		$thoussep = $this->l[$this->curlang]["thousand_separator"];

		$index = 0;
		for ($i = 0; $i < sizeof($result); $i++) {
			$index++;
			if ($index < (($page-1)*8)) continue;
			if ($index > $page*8) break;

			$row = $result[$i];

			// If there is no website or product link, the row is a model 
			if (!array_key_exists("website", $row) || !array_key_exists("link", $row))
			{
				$row["website"] = "";
				$row["link"] = "";
				$row["name"] = str_replace(";", "/", $row["model"]);

				$model_name = $brandDB->getModelId($row["id"]);
				$row["name"] = $model_name[0]["brand_name"]." ".$row["name"];

				if (empty($row["newimage1"]))
				{
					$row["newimage1"] = $allProductDB->try_find_image_for_model($row["id"]);
				}

				if (empty($row["description"]))
				{
					$row["description"] = $allProductDB->try_find_description_for_model($row["id"]);
				}
			}

			/* Create the right name link */
			$namelink = create_product_link($row["name"], $row["website"], $row["id"], $row["cat1"], $row["subcat1"]);

			if (empty($row["link"]))
			{
				$utmlink = $namelink;
			}
			else
			{
				$utmlink = create_utm_link($row["link"], $row["website"], $row["id"], $row["cat1"], $row["subcat1"], $row["name"]);
			}

			echo "<div class=\"line\"></div>";
			echo "<div class=\"nsp_art\">";
			echo "<div class=\"nsp_img\">";
			/* Link image */
			echo "<a href=\"/".$namelink."\">";
			/* Image*/
            echo "<img src=\"" . $row["newimage1"] . "\" width = \"120\", height = \"120\" alt=\"" . $row["name"] . "\" title=\"".$row["name"]."\"/></a>";
			echo "</div>";			
			echo "<div class=\"nsp_art_text\">";
			/* Link Title + Title */
			$name = $row["name"];
			if (strlen($name) > 55) {
				$name = substr($name, 0, 55);
				$name = substr($name, 0, strrpos($name, " "));
				$name .= "...";
			}
			echo "<h1><a href=\"/".$namelink."\">".$name."</a></h1>";
			
			/* Display average rating for each product */
			$avgrating = get_avg_rating($row["id"], $row["website"], $commentDB);

			echo "<ul id=\"\" class=\"star-rating-small\">\n";
			
			display_star($avgrating, 1);

			echo "</ul>\n";

			/* Display description of each product */
			$description_display = $row["description"];
			/* If the website description usually contain html tag, strip its */
			$description_display = strip_tags_custom($description_display);

			echo "<span style=\"line-height:24px; display:block; overflow:hidden;height:48px\">".$description_display."</span><span class=\"read_more\"><a href=\"/".$namelink."\">".$this->l[$this->curlang]["more_detail"]."</a></span>";
			echo "</div>";
			echo "<div class=\"price_search\">";

			$price = number_format($row["price"], $numsigfig, $decsep, $thoussep);

			if ($price > 0) 
			{
				echo "<a href=\"/".$namelink."\"><div class=\"title_price_search\">".$currency." ".$price."</div>";
			}
			else
			{
				$price_message = $this->l[$this->curlang]["out_of_stock"];
				echo "<a href=\"/".$namelink."\">";
				if ($this->website == "")
				{
						// Get the price range of the model
					$best_price = $brandDB->get_lowest_price_of_model_id($row["id"], 10);
					$worst_price = $brandDB->get_highest_price_of_model_id($row["id"], 10);

					if ($best_price == 0)
					{
						$price_message = $this->l[$this->curlang]["check_price"];
						echo "<div class=\"title_price_search\">".$price_message."</div>";
					}
					else if ($best_price == $worst_price)
					{
						$best_price = number_format($best_price, $numsigfig, $decsep, $thoussep);
						$price_message = $currency." ".$best_price;
						echo "<div class=\"title_price_search\">".$price_message."</div>";
					}
					else
					{
						$best_price = number_format($best_price, $numsigfig, $decsep, $thoussep);
						$worst_price = number_format($worst_price, $numsigfig, $decsep, $thoussep);
						$price_message = $currency." ".$best_price." ".$this->l[$this->curlang]["to"]." ".$currency." ".$worst_price;	
						echo "<div class=\"title_price_search\" style=\"font-size:18px\">".$price_message."</div>";
					}
				}
			}

			echo "</a>";
			echo "<div style=\"width:248px; line-height:22px\">";

			/* Display the website logo next to each product */
			if (empty($row["website"]))
			{
				echo "<span style=\"font-size:13px; font-weight:bold\"><img src= \"".$row["brand_image"]."\" width = \"125\" height = \"50\" ></span><br />";
			}
			else
			{
				echo "<span style=\"font-size:13px; font-weight:bold\"><img src= \"/img/website/".$row["website"].".png\" height = \"50\" ></span><br />";
			}
			echo "<span class=\"text_yellow\"></span>";
			echo "</div>";

			if (empty($row["website"]))
			{
				echo "<div class=\"btnMoreDetails\" style=\"margin-top:5px;width:auto;\"><a href=\"".$utmlink."\" onClick=\"trackOutboundLink(this, 'Outbound Links', '".$row["website"]."'); return false;\">".$this->l[$this->curlang]["detail"]."</a></div>";
			} 
			else 
			{
				echo "<div class=\"btnMoreDetails\" style=\"margin-top:5px;width:auto;\"><a href=\"".$utmlink."\" rel=\"nofollow\" target=\"_blank\" onClick=\"trackOutboundLink(this, 'Outbound Links', '".$row["website"]."'); return false;\">".$this->l[$this->curlang]["detail"]."</a></div>";
			}

			echo "</div>";
			echo "</div>";
		}
	}

	/* Helper function that displays the list of categories on the left of the search page */
    function display_category()
    {
		$cat = $this->result_cat;
		
		$extension = "&term=".$this->term."&website=".$this->website."&rating=".$this->rating."&lower_price=".$this->lower_price."&upper_price=".$this->upper_price."&order=".$this->order."&outofstock=".$this->outofstock;

		foreach ($cat as $key=>$value) {
			if ($value > 0) {

				$catname_language = get_catname_english($key);
				$subcat = return_subcat_english($key);
				$catname = strtolower(remove_sign($catname_language));

				echo "<li class=\"subMenuLeft\">\n";
				echo "<a href=\"/".$catname."/".$extension."\">".$catname_language."</a>\n";
				echo "<div class=\"item_number_search\">".$value."</div>\n";
				echo "<ul>\n";
				echo "<li class=\"first_li\">".$catname_language."</li>\n";

				// Subcategories
				if ($subcat != null) {
					for ($i = 0; $i < sizeof($subcat); $i++) {
						$subcatname_language = strtolower(remove_sign($subcat[$i]));
						echo "<li><a href=\"/".$catname."/".$subcatname_language."/".$extension."\">".$subcat[$i]."</a></li>\n";
					}
				}

				echo "</ul>\n";
				echo "</li>\n";
			}
		}
	}

	/* Helper function that displays the list of websites on the left of the search page */
    function display_website()
    {
		$website = $this->result_website;

		$extension = $this->extension."term=".$this->term."&rating=".$this->rating."&lower_price=".$this->lower_price."&upper_price=".$this->upper_price."&order=".$this->order."&outofstock=".$this->outofstock;

		foreach ($website as $key=>$value) {
			if ($value > 0) {
				echo "<li class=\"webMenuLeft\">\n";
				$link = $extension."&website=".$key;
				echo "<a href=\"/".$link."\">";

				translate_website_vnese($key);

				echo "</a>\n";
				echo "<div class=\"item_number_search\">".$value."</div>\n";
				echo "</li>\n";
			}
		}

		echo "<li class=\"first\"><a>".$this->l[$this->curlang]["price_range"]."</a></li>\n";
	}

	/* Helper function that display the list of rating on the left of the search page */
    function display_rating_list()
    {
		/* Display the listing */
		for ($i = 0; $i < 5; $i++) {
			//if ($rating[$i] > 0) {
			echo "<li class=\"rating_search\">\n";
			echo "<ul id=\"star-rating-small-rating-1\" class=\"star-rating-small\" style=\"float:left;margin-left:8px;\">\n";
			display_star($i+1, 1);
			echo "<a href=\"http://www.vnexpress.net\"/>";
			echo "</ul>";
			echo "<span style=\"color:#00AFF0\">(".($i+1).")</span>\n";
			echo "</li>\n";
		}
	}

	/* Helper function that display price range to the left of the search page */
    function display_price_range()
    {
		$extension = $this->extension."term=".$this->term."&rating=".$this->rating."&website=".$this->website."&order=".$this->order;

		$currency = $this->l[$this->curlang]["currency"];
		$numsigfig = $this->l[$this->curlang]["num_sigfig"];
		$decsep = $this->l[$this->curlang]["dec_separator"];
		$thoussep = $this->l[$this->curlang]["thousand_separator"];

		echo "<li>\n";
		echo "<form action=\"/search.php?\" method=\"GET\">\n";
		echo "<input type =\"hidden\" name=\"term\" value =\"".$this->term."\"/>\n";
		echo "<input type =\"hidden\" name=\"category\" value =\"".$this->category."\"/>\n";
		echo "<input type =\"hidden\" name=\"subcategory\" value =\"".$this->subcategory."\"/>\n";
		echo "<input type =\"hidden\" name=\"rating\" value =\"".$this->rating."\"/>\n";
		echo "<input type =\"hidden\" name=\"website\" value =\"".$this->website."\"/>\n";
		echo "<input name=\"lower_price\" type=\"text\" id=\"From\" size=\"5\" class=\"input_form_filter\" value=\"\" onfocus=\"format_onfocus('#From', 'From')\" onblur=\"format_blur()\" />\n";
		echo "<input name=\"upper_price\" type=\"text\" id=\"To\" size=\"5\" class=\"input_form_filter\" value=\"\" onfocus=\"format_onfocus('#To', 'To')\" onblur=\"format_blur()\" />\n";
		echo "<input type = \"image\" src=\"/img/btn_where_search.png\" alt=\"\" style=\"margin:2px 0px 2px 10px; float:left;\"/>\n";
		echo "</form>\n";
		echo "</li>\n";

		$price1 = number_format($this->l[$this->curlang]["price1"], $numsigfig, $decsep, $thoussep);
		$price2 = number_format($this->l[$this->curlang]["price2"], $numsigfig, $decsep, $thoussep);
		$price3 = number_format($this->l[$this->curlang]["price3"], $numsigfig, $decsep, $thoussep);
		$price4 = number_format($this->l[$this->curlang]["price4"], $numsigfig, $decsep, $thoussep);

		$disprice1 = number_format($this->l[$this->curlang]["price1"], 0, ',', '.');
		$disprice2 = number_format($this->l[$this->curlang]["price2"], 0, ',', '.');
		$disprice3 = number_format($this->l[$this->curlang]["price3"], 0, ',', '.');
		$disprice4 = number_format($this->l[$this->curlang]["price4"], 0, ',', '.');
		echo "<li><a href=\"/".$extension."&upper_price=".$disprice1."\">Under ".$currency." ".$price1."</a></li>\n";
		echo "<li><a href=\"/".$extension."&lower_price=".$disprice1."&upper_price=".$disprice2."\">".$currency." ".$price1." - ".$currency." ".$price2."</a></li>\n";
		echo "<li><a href=\"/".$extension."&lower_price=".$disprice2."&upper_price=".$disprice3."\">".$currency." ".$price2." - ".$currency." ".$price3."</a></li>\n";
		echo "<li><a href=\"/".$extension."&lower_price=".$disprice3."&upper_price=".$disprice4."\">".$currency." ".$price3." - ".$currency." ".$price4."</a></li>\n";
		echo "<li><a href=\"/".$extension."&lower_price=".$disprice4."\">".$this->l[$this->curlang]["above"]." ".$currency." ".$price4."</a></li>\n";
		echo "<li><a href=\"/".$extension."&outofstock=true\">".$this->l[$this->curlang]["out_of_stock"]."</a></li>\n";
	}

	/* Helper function that display page navigation at the bottom of the search page */
    function display_page_navigation()
    {
		$page = 1;
		if (is_numeric($this->page)) {
			$page = $this->page;
		}

		$pages = array();
		$numPages = intval((sizeof($this->result)-1) / 9 + 1);
		if ($numPages > 0) {
			$pages[] = 1;
			if ($numPages >= 2) {
				$pages[] = 2;
				if ($page > 3) $pages[] = $page-1;
				if ($page > 2) $pages[] = $page;
				if ($page < $numPages) {
					if ($page > 1) $pages[] = $page+1;
				}
				if (!in_array($numPages-1, $pages)) $pages[] = $numPages-1;
				if (!in_array($numPages, $pages)) $pages[] = $numPages;
			}
		}
		
		$extension = $this->extension."term=".$this->term."&website=".$this->website."&rating=".$this->rating."&lower_price=".$this->lower_price."&upper_price=".$this->upper_price."&order=".$this->order."&outofstock=".$this->outofstock;

		/* Print the previous page button */
		if ($page == 1) {
			echo "<li class=\"previous-off\"><img src=\"/img/pre_pagination.png\" alt=\"\" /></li>\n";
		} else {
			echo "<li class=\"previous\"><a href=\"/".$extension."&page=".($page-1)."\"><img src=\"/img/pre_pagination.png\" alt=\"\" /></a></li>\n";
		}

		/* Print the page navigation in the middle of two buttons */
		for ($i = 1; $i <= sizeof($pages); $i++) {
			if ($pages[$i-1] != $page) {
				if ($pages[$i-1] != 1 && $pages[$i-2]+1 < $pages[$i-1]) {
					echo "<li><span>...</span></li>\n";
					echo "<li><a href=\"/".$extension."&page=".$pages[$i-1]."\"><div style=\"margin-top:4px;font-weight:bold\">".$pages[$i-1]."</div></a></li>\n";
				} else {
					echo "<li><a href=\"/".$extension."&page=".$pages[$i-1]."\"><div style=\"margin-top:4px;font-weight:bold\">".$pages[$i-1]."</div></a></li>\n";
				}
			} else {
				echo "<li class=\"active\"><div style=\"margin-top:4px;font-weight:bold\">".$page."</div></li>\n";
			}
		}

		/* Print the next page button */
		if ($page >= $numPages) {
			echo "<li class=\"next-off\"><img src=\"/img/next_pagination.png\" alt=\"\" /></li>\n";
		} else {
			echo "<li class=\"next\"><a href=\"/".$extension."&page=".($page+1)."\"><img src=\"/img/next_pagination.png\" alt=\"\" /></a></li>\n";
		}
	}

    function display_select_order()
    {
        $option = array(
            $this->l[$this->curlang]["sort1"],
            $this->l[$this->curlang]["sort2"],
            $this->l[$this->curlang]["sort3"],
            $this->l[$this->curlang]["sort4"]
        );

		echo "<option value = \"".$this->order."\">".$option[$this->order-1]."</option>\n";
		for ($i = 1; $i <=4; $i++) {
			if ($i != $this->order) {
				echo "<option value = \"$i\">".$option[$i-1]."</option>\n";
			}
		}
	}

	function display_registration() 
	{
		echo "<div class=\"pagination\">\n";
		echo "<div class=\"lastNewsSmall\">\n";
		echo "<div class=\"lastNewsBlueSmall\"><a class=\"thickbox\" href=\"/subscription.html?height=370&width=920&KeepThis=true&TB_iframe=true\" style=\"text-transform:none;\">".$this->l[$this->curlang]["blue_signup"]."</a></div>\n";
		echo "<div class=\"lastNewsOrangeSmall\"><a class=\"thickbox\" href=\"/subscription.html?height=370&width=920&KeepThis=true&TB_iframe=true\" style=\"font-size:12px;\">".$this->l[$this->curlang]["orange_signup"]."</a></div>\n";
		echo "</div>\n";
		echo "</div>\n";
	}

	function display_bread_crumb()
	{
		echo "<span class=\"youah\"><a href=\"/\">".$this->l[$this->curlang]["homepage"]."</a></span><span class=\"pathway separator\"><img src=\"/img/icon_arrow_grey.png\" alt=\"\" /></span><span class=\"pathway last\">".$this->l[$this->curlang]["search"]."</span>\n";
	}

	/* Helper function that displays suggested product at the end of the page */
	function display_suggested_product() 
	{
		echo "<div class=\"title\" style=\"width:952px;\"><h1>".$this->l[$this->curlang]["suggested"]."</h1></div>\n";
		echo "<!-- <div class=\"menuProductsCornerRight\"></div> -->\n";
		echo "</div>\n";
		echo "<div class=\"itemsBoxSuggestedSearch\">\n";
		display_suggested_product($this->suggested, "", "", 1, $this->curlang, $this->l);
	}
}
?>