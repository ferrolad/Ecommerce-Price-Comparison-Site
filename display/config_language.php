<?php

class language {
	var $current_lang;

	function SetConfig($lang)
	{
		$this->current_lang = $lang;
	} 

	function current_language()
	{
		return $this->current_lang;
	}

	function general_term()
	{
		$term = array();

		$term["en"]["currency"] = "₱";
		$term["en"]["mobile"] = "Mobile";
		$term["en"]["tablet"] = "Tablet";
		$term["en"]["computer"] = "Computer";
		$term["en"]["camera"] = "Camera";
		$term["en"]["tv"] = "TV";
		$term["en"]["audio"] = "Audio";
		$term["en"]["accessory"] = "Accessory";
		$term["en"]["women_clothing"] = "Women Clothing";
		$term["en"]["men_clothing"] = "Men Clothing";
		
		$term["en"]["homepage"] = "Homepage";
		$term["en"]["country"] = "Philippines";
		$term["en"]["domain"] = "pricepony.com.ph";
		$term["en"]["ga_account"] = "45634301";

		$term["en"]["more_detail"] = "More Detail";
		$term["en"]["num_sigfig"] = 2;
		$term["en"]["dec_separator"] = '.';
		$term["en"]["thousand_separator"] = ',';

		$term["en"]["blue_signup"] = "Sign up now to receive newsletter from PricePony";
		$term["en"]["orange_signup"] = "SIGN UP!";

		$term["en"]["fb_link"] = "https://www.facebook.com/OizoioiPH";
		$term["en"]["gplus_link"] = "https://plus.google.com/102784543913622869921/posts";

		$term["en"]["contact"] = "Contact Us";
		$term["en"]["privacy_policy"] = "Privacy Policy";
		$term["en"]["term_condition"] = "Terms and Conditions";
		$term["en"]["join_us"] = "Join us on";

		return $term;
	}

	function term_index()
	{
		$term = $this->general_term();

		$term["en"]["title"] = "Price comparison for electronic and fashion products | Pricepony.com.ph";
		$term["en"]["description"] = "Price comparison website for mobile phones, tablets, cameras, laptops, TV, Speakers, Headphones... at top reliable retailers and ecommerce sites in ".$term["en"]["country"];
		$term["en"]["keywords"] = "price comparison, mobile phone price, camera price, laptop price, tablet price, tv price, headphone price, electronic price";
		$term["en"]["new_product"] = "New Products";
		$term["en"]["popular_product"] = "Popular Product";

		return $term;
	}

	function term_search()
	{
		$term = $this->general_term();

		$term["en"]["search_result"] = "Search result for";
		$term["en"]["product_found"] = "Products Found";
		$term["en"]["out_of_stock"] = "Out of Stock";
		$term["en"]["check_price"] = "Check Price";
		$term["en"]["to"] = "to";
		$term["en"]["detail"] = "Detail";
		$term["en"]["price_range"] = "Price Range";
		$term["en"]["above"] = "Above";
		$term["en"]["search"] = "Search";
		$term["en"]["suggested"] = "Suggested Products";

		$term["en"]["price1"] = 1000;
		$term["en"]["price2"] = 5000;
		$term["en"]["price3"] = 10000;
		$term["en"]["price4"] = 30000;

		$term["en"]["sort1"] = "Price: Low to High";
		$term["en"]["sort2"] = "Price: High to Low";
		$term["en"]["sort3"] = "Product Name Alphabetically";
		$term["en"]["sort4"] = "Website Name Alphabetically";

		return $term;
	}

	function term_detail()
	{
		$term = $this->general_term();

		$term["en"]["review"] = "Review";
		$term["en"]["best_price"] = "Best Price";
		$term["en"]["price"] = "Price";
		$term["en"]["price_in_country"] = "Prices in Philippines";
		$term["en"]["suggested"] = "Suggested Products";
		$term["en"]["related"] = "Related items";
		$term["en"]["editorial_review"] = "Editorial Review of";
		$term["en"]["by"] = "By";
		$term["en"]["is"] = "is";
		$term["en"]["at"] = "At";
		$term["en"]["the"] = "The";
		$term["en"]["summary_currency"] = "All prices in the above table are in Philippine peso";
		$term["en"]["summary_latest_price"] = "latest price of";
		$term["en"]["summary_obtain"] = "was obtained on";
		$term["en"]["summary_available"] = "is available on";
		$term["en"]["summary_best_price"] = "best price of";
		$term["en"]["summary_location"] = "The price is valid in all major cities of ".$term["en"]["country"]." including Manila, Quezon City, Caloocan, Davao City, etc... Please check instructions at the specific stores for any deviation.";
		$term["en"]["video_review"] = "Video Review";
		$term["en"]["rating"] = "Rating";

		$term["en"]["tab1"] = "PRICE";
		$term["en"]["tab2"] = "DETAIL";
		$term["en"]["price_tab1"] = "Website";
		$term["en"]["price_tab2"] = "Product";
		$term["en"]["price_tab3"] = "Price";
		$term["en"]["grey_signup"] = "WANT TO RECEIVE NEWSLETTER OF BIG SALE CAMPAIGNS<br/> AND EXCLUSIVE DEALS? SIGN UP WITH PRICEPONY";

		$term["en"]["related_keywords"] = "Related Keywords";
		$term["en"]["categories"] = "Categories";
		$term["en"]["top_brand"] = "Top Brands";
		$term["en"]["information"] = "Information";
		$term["en"]["dont_miss_deal"] = "Don't Miss Out Exclusive Deals!";
		$term["en"]["signup"] = "Sign up for Pricepony newsletter";

		return $term;
	}

	function term_footer()
	{
		$term = $this->general_term();

		$term["en"]["product_categories"] = "Product Categories";

		return $term;
	}

	function term_top()
	{
		$term = $this->general_term();

		$term["en"]["slogan"] = "Compare Prices of More Than 100,000 Products";
		$term["en"]["search_product"] = "Search Product";
		$term["en"]["contactus"] = "Contact";

		return $term;
	}
}

$lang = new language();
$lang->SetConfig("en");
$curlang = $lang->current_language();
//$lang->SetConfig("vi");

?>