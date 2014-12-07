<?php

class base_crawler {
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $DBSite;
	var $name;
	var $curl_flag;

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
			for ($i = 0; $i < sizeof($lines); $i++) $result[] = $lines[$i]; 
			$page++;
		}
		return $result;
	}

	public function add_categories($cat, $page) {
		$count = add_categories_website($cat, $page, $this->categories(), $this);
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
	public function get_images_page($category, $page, $resultPerPage = 5) {
		//Get the list of image from the specific category
		$db = $this->DBSite;
		$count = get_images_page_website($category, $page, $this->name, $db, $resultPerPage);
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
	public function update_price_page($category, $page, $resultPerPage = 5) {
		$db = $this->DBSite;
		$count = update_price_page_website($category, $page, $db, $this, $this->curl_flag, $resultPerPage);
		return $count;
	}

	/* Merge data from preprocess database to post process (real) database */
	public function merge_preprocess_to_site_database()
	{
		$db = $this->DBSite;
		$count = $db->merge_preprocess_to_site_database_website();
		return $count;
	}

	/* Clear all information from the preprocess table */
	public function clear_preprocess_price_information()
	{
		$db = $this->DBSite;
		$count = $db->clear_preprocess_price_information();
		return $count;
	}

	// These following function should be implemented in subclass
	public function get_whole_product_page($link, $page) { return array(); }

	public function get_image($description) { return ""; }

	public function get_name($content) { return ""; }

	public function get_link($description) { return ""; }

	public function get_price($content) { return ""; }

	public function get_comment($content) { return ""; }

	public function get_description($content) { return ""; }

	public function get_description2($content) { return ""; }

	public function get_extra_image($content) { return array(); }

	public function add_category($category, $link, $page) { return 0; }

	public function categories() { return array(); }
}

?>