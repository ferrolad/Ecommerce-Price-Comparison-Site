<?php
include './CMS/include/database_config.php';

class review_display {
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $curlang;
	var $lang;
	var $l;
	var $id;
	var $website;
	var $brand;
	var $brand_name;
	var $brand_image;
	var $precalculated_model;
	var $model;
	var $model_name;
	var $category;
	var $link;
	var $name;
	var $name_without_special_characters;
	var $price;
	var $best_price;
	var $best_price_website;
	var $worst_price_website;
	var $comment;
	var $avgrating;
	var $description;
	var $description2;
	var $cat;
	var $subcat;
	var $catname;
	var $vnese_catname;
	var $english_catname;
	var $subcatname;
	var $vnese_subcatname;
	var $english_subcatname;
	var $image;
	var $thumbnails;
	var $suggested;
	var $similarproduct;
	var $namelink;
	var $seo_text;
	var $no_accent_name;
	var $last_updated_price;
	var $editorial_review;
	var $video_review;

	function SetConfig($host, $user, $pass, $db, $id, $website, $lang) {
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
		$this->curlang = $lang->current_language();
		$this->lang = $lang;
		$this->l = $lang->term_detail();
		$this->id = $id;

		// If it is a detail page of a model
		if ($website != "modelproduct")
		{
			$website = translate_website_from_code($website);
		}

		$this->website = $website;

		/* If website code is wrong, return false */
		if (empty($this->website)) {
			return false;
		}

		try {
			// Prepare necessary database
			$allProductDB = new DBAllProduct();
			$allProductDB->SetConfig($host, $user, $pass, $db);

			$brandDB = new DBBrandModel();
			$brandDB->SetConfig($host, $user, $pass, $db);

			$catDB = new DBCategory();
			$catDB->SetConfig($host, $user, $pass, $db);

			$commentDB = new DBComment();
			$commentDB->SetConfig($host, $user, $pass, $db);

			$seotextDB = new DBSeoText();
			$seotextDB->SetConfig($host, $user, $pass, $db);

			$editorialreviewDB = new DBEditorialReview();
			$editorialreviewDB->SetConfig($host, $user, $pass, $db);

			$result = array();
			if ($website == "modelproduct")
			{
				$result = $brandDB->getModelId($id);
				/* Increment the number of click for the product by 1 */
				$brandDB->increment_product_numclick($id);
			}
			else
			{
				$result = $allProductDB->get_product($website, $id);
				/* Increment the number of click for the product by 1 */
				$allProductDB->increment_product_numclick($website, $id);
			}

			if ($result && sizeof($result) > 0) {
				$result = $result[0];
				$this->category = $result["category"];

				if ($website == "modelproduct")
				{
					$this->link = "";
					$this->name = str_replace(";", "/", $result["model"]);
					$this->brand_name = $result["brand_name"];
					if (!empty($this->brand_name))
					{
						$this->name = $this->brand_name." ".$this->name;
					}

					$this->no_accent_name = $this->name;
					$this->last_updated_price = "";
					$this->brand_image = $result["brand_image"];
					$this->precalculated_model = $result["id"];
					$this->model_name = $result["model"];
					$this->editorial_review = $editorialreviewDB->get(1, "", $this->id, 1);
				}
				else
				{
					$this->link = $result["link"];
					$this->name = $result["name"];
					$this->no_accent_name = $result["no_accent_name"];
					$this->price = $result["price"];
					if ($this->price < 0 || !is_numeric($this->price)) {
						return false;
					}
					$this->last_updated_price = $result["update_price_date"];
					$this->brand_image = "/img/website/".$this->website.".png";
					$this->precalculated_model = $result["model"];
					$this->editorial_review = $editorialreviewDB->get(0, $this->website, $this->id, 1);

					// If the product is already included in a model, return a 301 redirect signal
					if (!empty($this->precalculated_model))
					{
						$model_name = $brandDB->getModelId($this->precalculated_model);
						$redirectUrl = create_product_link($model_name[0]["brand_name"]." ".$model_name[0]["model"], "", $model_name[0]["id"], $result["cat1"], $result["subcat1"]);
						return "RedirectTo:".$redirectUrl;
					}
					$this->description2 = trim($result["description2"]);
					$this->comment = trim($result["comment"]);
					$this->video_review = trim($result["video1"]);
				}

				$this->name_without_special_characters = remove_special_characters($this->name);
				$this->image = array();
				//$this->thumbnail = array();
				for ($i = 1; $i <= 7; $i +=1) {
					$this->image[] = $result["newimage$i"];
					//$this->thumbnail[] = $result["thumbnail$i"];
				} 

				$this->description = $this->clean_up_description(trim($result["description"]));
				
				$this->cat = $result["cat1"];
				$this->subcat = $result["subcat1"];
				$this->catname = "";
				$this->subcatname = "";	
				$this->brand = $result["brand"];
				$this->model = $result["model"];

				// If precalculated model is empty, try to guess the model name
				if (empty($this->model))
				{
					$keyword_name = create_keyword_name($this->name);
					$keyword = find_model_keyword_mobile($keyword_name);
					$this->model = $keyword;
				}
				
				/* Get the name of the category */
				$result = $catDB->get_catname($this->cat);

				if ($result && sizeof($result) > 0) {
					$result = $result[0];
					$this->catname = $result["category"];
					$this->vnese_catname = utf8_decode($result["vnese_name"]);
					$this->english_catname = $result["english_name"];
				} else return false;

				/* Get the name of the subcategory */
				$result = $catDB->get_subcatname($this->subcat);
				if ($result && sizeof($result) == 1) {
					$result = $result[0];
					$this->subcatname = $result["subcat"];
					$this->vnese_subcatname = utf8_decode($result["vnese_name"]);
					$this->english_subcatname = $result["english_name"];
				}
				
				/* Get the suggested product list */
				$this->suggested = $allProductDB->get_suggested_product($this->name, $this->cat);

				/* Get Seo_text */
				$this->seo_text = $this->get_seo_text($seotextDB);

				/* Find similar product */
				$result = find_similarproduct($this->cat, $this->subcat, $this->no_accent_name, $this->website, $this->brand, $this->precalculated_model, $this->price, $allProductDB);
				if ($result) {
					$this->similarproduct = $result;
				}

				// If the model or product miss some information like description, images, video review, etc..., try to find them in other similar product
				$this->fill_in_empty_information();

				$this->comment = explode("<done>", $this->comment);

				// Calculate average rating
				$this->avgrating = 0;
				for ($i = 0; $i < sizeof($this->comment)-1; ++$i)
				{
					$rating = explode("<separator>", $this->comment[$i]);
					$rating = intval($rating[4]);
					$this->avgrating += $rating;
				}
				$size = sizeof($this->comment);
				if ($size == 1) $size = 2;
				$this->avgrating = floatval($this->avgrating/$size);

				if ($website == "modelproduct")
				{
					$this->best_price = $this->get_best_price();
					/* Format the product price with , and . sign */
					$this->price = "";
				}
				else
				{
					$this->best_price = $this->get_best_price();
					/* Format the product price with , and . sign */
					$this->price = number_format($this->price, 2, '.', ',');
				}
				$this->best_price = number_format($this->best_price, 2, '.', ',');

				/* Construct the url for the page */
				$namelink = create_product_link($this->name, $website, $this->id, $this->cat, $this->subcat);
				$this->namelink = $namelink;
				//print_r($this->similarproduct);
				return $namelink;
			}
		} catch (Exception $ex) {
			echo 'Caught exception: ',  $ex->getMessage(), "\n";
		}
		return false;
	}

	// If the model or product miss some information like description, images, video review, etc..., try to find them in other similar product
	function fill_in_empty_information()
	{
		// If description is empty, try to get it from other similar products
		if (empty($this->description))
		{
			$this->get_description_from_similar_product();
		}

		// If description under CTA is empty, try to get it from other similar products
		if (empty($this->description2))
		{
			$this->get_description2_from_similar_product();
		}

		// If video review is empty, try to get it from other similar products
		if (empty($this->video_review))
		{
			$this->get_video_review_from_similar_product();	
		}

		// If images are empty, try to get them from other similar products
		if (empty($this->image[0]))
		{
			$this->get_images_from_similar_product();	
		}

		// If images are empty, try to get them from other similar products
		if (empty($this->comment))
		{
			$this->get_comment_from_similar_product();	
		}
	}

		/* Helper function that display the keyword, description, title part of the html header*/
	function display_title_keyword_description_header() {
		$name = str_replace("\"", "", $this->name);
		$name_keywords = str_replace("\"", "", implode(", ", explode(" ", $this->name)));
		$title = "Pricepony.com.ph: Customer Reviews: ".$name;

		echo "<title>".$title."</title>\n";
		echo "  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">\n";
		echo "  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n";
		echo "<meta name=\"description\" content=\"Find helpful customer reviews and review ratings for ".$name." at Pricepony.com.ph\">\n";
		echo "  <meta name=\"keywords\" content=\"".$name_keywords.", ".$name.", ".strtolower($this->english_catname).", ".strtolower($this->english_subcatname).", Reviews, Review, Rating, Ratings, Customer Reviews, Review Rating, Review Ratings, Pricepony.com.ph\" />\n";
		echo "  <meta name=\"robots\" content=\"index,follow\" />\n";
		echo "  <meta property=\"og:title\" content=\"".$name."\"/>\n";
		echo "  <meta property=\"og:type\" content=\"product\"/>\n";
		echo "  <meta property=\"og:image\" content=\"".$this->image[0]."\"/>\n";
	}

	function get_video_review_from_similar_product()
	{
		for ($i = 0; $i < sizeof($this->similarproduct); ++$i)
		{
			$video_review = $this->similarproduct[$i]["video1"];
			if (!empty($video_review))
			{
				$this->video_review = $video_review;
				return;
			}
		}	
	}

	function get_description_from_similar_product()
	{
		for ($i = 0; $i < sizeof($this->similarproduct); ++$i)
		{
			$description = $this->similarproduct[$i]["description"];
			if (!empty($description))
			{
				$this->description = $description;
				return;
			}
		}	
	}

	function get_description2_from_similar_product()
	{
		for ($i = 0; $i < sizeof($this->similarproduct); ++$i)
		{
			$description2 = $this->similarproduct[$i]["description2"];
			if (!empty($description2))
			{
				$this->description2 = $description2;
				return;
			}
		}	
	}

	function get_comment_from_similar_product()
	{
		for ($i = 0; $i < sizeof($this->similarproduct); ++$i)
		{
			$comment = $this->similarproduct[$i]["comment"];
			if (!empty($comment))
			{
				$this->comment = $comment;
				return;
			}
		}	
	}

	function get_images_from_similar_product()
	{
		for ($i = 0; $i < sizeof($this->similarproduct); ++$i)
		{
			$image = $this->similarproduct[$i]["newimage1"];
			if (!empty($image))
			{
				$this->image[0] = $this->similarproduct[$i]["newimage1"];
				$this->image[1] = $this->similarproduct[$i]["newimage2"];
				$this->image[2] = $this->similarproduct[$i]["newimage3"];
				$this->image[3] = $this->similarproduct[$i]["newimage4"];
				$this->image[4] = $this->similarproduct[$i]["newimage5"];
				return;
			}
		}	
	}


	/* Helper function that to clean up the product description to display */
	function clean_up_description($description) {
		$description = strip_tags_custom($description);
		$description = str_replace("&nbsp;", "", $description);
		$description = preg_replace("/[\r\n]+\s*/", "\n", $description);
		$description = str_replace("\n", "<br/>", $description);
		$description = preg_replace('/\s+/', ' ',$description);

		/* Remove first <br> */
		if (substr($description, 0, 4) == "<br>") {
			$description = substr($description, 4);
		} else if (substr($description, 0, 5) == "<br/>") {
			$description = substr($description, 5);
		}

		if ($this->website == "nguyenkim") {
			$allowed_tags = '<a><br><b><h3><h4><i><li><ol><strong>' .
			'<tr><td><th><u><ul><em>';
			$description = strip_tags($description, $allowed_tags);
		}

		return $description;
	}

	function get_seo_text($seotextDB) {
		/* Get the review list */
		$seo_text = "";

		$result = $seotextDB->get($this->website, $this->id);

		if ($result) {
			$seo_info = $result[0];
			if ($seo_info["active"] == 1) {
				$seo_text = trim($seo_info["seo_text"]);				
			}
		}

		return $seo_text;
	}

	// Find the best price of the products
	function get_best_price()
	{
		$best_price = $this->price;
		$similarproduct = $this->similarproduct;

		if ($best_price == 0)
		{
			if (sizeof($similarproduct) > 0) 
			{
				$best_price = $similarproduct[0]["price"];
				$this->best_price_website = $similarproduct[0]["website"];
			}
			else
			{
				return "none";
			}
		}

		for ($i = 0; $i < sizeof($similarproduct); $i++) 
		{
			$row = $similarproduct[$i];
			if ($best_price > $row["price"] && $row["price"] > 0)
			{
				$best_price = $row["price"];
				$this->best_price_website = $row["website"];
			}
		}

		return $best_price;
	}
	
	function display_video_review()
	{
		$video_review = $this->video_review;
		if (!empty($video_review))
		{
			echo "<div class=\"block_container\">\n";
			echo "<div class=\"block_title\">".$this->l[$this->curlang]["video_review"]."</div>\n";
			echo "<div class=\"block_text_holder\">\n";
			echo "<iframe width=\"720\" height=\"315\" src=\"".$video_review."\" frameborder=\"0\" allowfullscreen></iframe>\n";
			echo "</div>\n";
			echo "</div>\n";
			echo "</div>\n";
		};
	}

	function display_editorial_review()
	{
		$editorial_review_list = $this->editorial_review;
		for ($i = 0; $i < sizeof($editorial_review_list); ++$i)
		{
			$review = $editorial_review_list[$i];

			echo "<div class=\"block_container\">\n";
			echo "<div class=\"block_title\">".$this->l[$this->curlang]["editorial_review"]." ".$this->name."</div>\n";
			echo "<div class=\"block_subtitle\">".$this->l[$this->curlang]["by"]." ".$review["reviewer"]." ".$this->l[$this->curlang]["at"]." ".format_time_review($review["time"])."<span><img src=\"/img/logo_Giare.png\" width=\"84\" height=\"12\" alt=\"\" /></span></div>\n";
			echo "<div class=\"block_text_holder\">\n";
			echo $review["seo_text"]."\n";
			echo "</div>\n";
			echo "<div class=\"block_rating\">\n";
			echo $this->l[$this->curlang]["rating"].":\n";

			$rating = $review["rating"];

			if (is_numeric($rating) && $rating > 0 && $rating <= 5)
			{
				$this->display_star($rating);
			}
			echo "<span class=\"rating\" style=\"color:#e36e11\"> (".$rating.")</span>";

			echo "</div>\n";
			echo "</div>\n";
		}
	}

	function display_customer_reviews()
	{
		$review_list = $this->comment;

		echo "<div class=\"block_title\">Review Of ".$this->name."</div>\n";
		echo "<div style=\"height:30px\"></div>\n";

		echo "<table>\n";
		echo "<tr>\n";
		echo "<td width=\"30%\"><img src=\"".$this->image[0]."\" style=\"max-height:200px\"/></td>\n";
		echo "<td>".$this->description2."</td>\n";
		echo "</tr>\n";
		echo "</table>\n";

		$this->display_history_bar();

		$contentArray = array();
		for ($i = 0; $i < sizeof($review_list)-1; ++$i)
		{
			$review = $review_list[$i];
			$review_components = explode("<separator>", $review);
			$name = trim($review_components[0]);
			$date = trim($review_components[1]);
			$title = trim($review_components[2]);
			$content = trim($review_components[3]);
			$value = trim($review_components[4]);

			if (array_key_exists($name, $contentArray)) 
			{
				$valueStored = $contentArray[$name];
				if ($valueStored == $value)
				{
					continue;
				}
			}

			echo "<div class=\"block_container\">\n";
			echo "<div class=\"block_subtitle\">By ".$name."\n";
			echo "<span style=\"margin-left:20px\"><img src=\"/img/logo_Giare.png\" width=\"84\" height=\"12\" alt=\"\" /></span></div>\n";
			if (is_numeric($value) && $value > 0 && $value <= 5)
			{
				$this->display_star($value);
			}
			echo "<strong>".$title."</strong> - ".$date;
			echo "<div class=\"block_text_holder\">\n";
			echo $content."\n";
			echo "</div>\n";
			echo "<div class=\"block_rating\">\n";

			echo "</div>\n";
			echo "</div>\n";
			echo "<br/>";
			$contentArray[$name] = $value;
		}
	}

	function display_history_bar() 
	{
		echo "<div style=\"margin-left:10px; margin-top:20px\">";
		$this->display_star($this->avgrating);
		echo "<a href=\"#\"/><span style=\"margin-top:10px\">(".(sizeof($this->comment)-1).")</span></a>";
		echo "<br/>";
		echo "<span style=\"color:#888!important; font-size:15px\">".round($this->avgrating, 1)." out of 5 stars</span>";
		echo "</div>\n";

		echo "<table id=\"histogramTable\" class=\"a-normal a-align-middle a-spacing-base\" style=\"margin-left:8px;margin-top:5px\">\n";
		echo "<tr class=\"a-histogram-row\">\n";

			// Calculate average rating
		$this->avgrating = 0;
		for ($i = 0; $i < sizeof($this->comment)-1; ++$i)
		{
			$rating = explode("<separator>", $this->comment[$i]);
			$rating = intval($rating[4]);
			$this->avgrating += $rating;
		}
		$size = sizeof($this->comment);
		if ($size <= 1) $size = 2;
		$this->avgrating = floatval($this->avgrating/$size);

			// Calculate number of reviews of each star
		$total = 0;
		$numReview = array();
		$numReview[] = 0;$numReview[] = 0;$numReview[] = 0;$numReview[] = 0;$numReview[] = 0;

		for ($i = 0; $i < sizeof($this->comment)-1; ++$i) 
		{
			$rating = explode("<separator>", $this->comment[$i]);
			$rating = intval($rating[4]);
			$numReview[$rating-1]++;
			$total++;
		}
		if ($total == 0) $total = 1;

			// Display history bar
		for ($i = 5; $i >= 1; --$i)
		{
			echo "<td class=\"a-nowrap\" width=\"5%\">\n";
			echo "<a class=\"a-link-normal\" title=\"50% of reviews have 5 stars\" href=\"#\" style=\"color:#00AFF0\">".$i." star</a><span class=\"a-letter-space\"></span>\n";      
			echo "</td>\n";
			echo "<td class=\"a-span10\" width=\"100\">\n";
			echo "<a class=\"a-link-normal\" title=\"".($numReview[$i-1]/$total*100)."% of reviews have ".$i." stars\" href=\"#\"><div class=\"a-meter\"><div class=\"a-meter-bar\" style=\"width: ".($numReview[$i-1]/$total*100)."%;\"></div></div></a>\n";
			echo "</td>\n";
			echo "<td class=\"a-nowrap\">\n";
			echo "<span class=\"a-letter-space\"></span><span>".$numReview[$i-1]."</span>\n";
			echo "</td>\n";
			echo "</tr>\n";
			echo "<tr class=\"a-histogram-row\">\n";
		}

		echo "</tr>\n";
		echo "</table>\n";
	}

	function display_star($value) 
	{
			// If there is no review, give a default value of 4
		if (sizeof($this->comment) == 1)  
		{
			$value = 4;
		} 

			// Display full star
		for ($j = 0; $j < intval($value); ++$j)
		{
			echo "<img src=\"/index_images/star.jpg\" alt=\"\" />";
		}

			// Display half a star
		if ($value > intval($value) && $value < intval($value)+1) 
		{
			echo "<img src=\"/index_images/hstar.jpg\" alt=\"\" />";
			$value = intval($value)+1;
		}

			// Display empty star
		for ($j = intval($value); $j < 5; ++$j)
		{
			echo "<img src=\"/index_images/estar.jpg\" alt=\"\" />";
		}
	}
}