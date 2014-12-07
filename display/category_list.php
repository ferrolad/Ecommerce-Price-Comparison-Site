<?php

function result_cat() {
	$result_cat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0);
	return $result_cat;
}

function category_list_sorted_priority() {
	$category_list = array();
	$category_list[] = "mobiles";
	$category_list[] = "tablets";
	$category_list[] = "camera";
	$category_list[] = "tv";
	$category_list[] = "computer";
	$category_list[] = "audio";
	$category_list[] = "accessories";
	$category_list[] = "female_fashion";
	$category_list[] = "male_fashion";
	return $category_list;
}

function catcode_list_sorted_priority() {
	$catcode_list = array();
	$catcode_list[] = 1;
	$catcode_list[] = 2;
	$catcode_list[] = 4;
	$catcode_list[] = 5;
	$catcode_list[] = 3;
	$catcode_list[] = 6;
	$catcode_list[] = 7;
	$catcode_list[] = 8;
	$catcode_list[] = 9;
	return $catcode_list;
}

/* Return vietnamese category name */
function get_vnese_catname($cat) {
	if ($cat == 1) {
		return "Điện Thoại Di Động";
	} else if ($cat == 2) {
		return "Máy Tính Bảng";
	} else if ($cat == 3) {
		return "Máy Vi Tính";
	} else if ($cat == 4) {
		return "Máy Ảnh";
	} else if ($cat == 5) {
		return "TV";
	} else if ($cat == 6) {
		return "Âm Thanh";
	} else if ($cat == 7) {
		return "Phụ Kiện Điện Tử";
	} else if ($cat == 8) {
		return "Thời Trang Nữ";
	} else if ($cat == 9) {
		return "Thời Trang Nam";
	}
}

/* Return vietnamese category name */
function get_catname_english($cat) {
	if ($cat == 1) {
		return "Mobile";
	} else if ($cat == 2) {
		return "Tablet";
	} else if ($cat == 3) {
		return "Computer";
	} else if ($cat == 4) {
		return "Camera";
	} else if ($cat == 5) {
		return "TV";
	} else if ($cat == 6) {
		return "Audio";
	} else if ($cat == 7) {
		return "Accessory";
	} else if ($cat == 8) {
		return "Women Clothing";
	} else if ($cat == 9) {
		return "Men Clothing";
	}
}

/* Return vietnamese category name */
function get_catname_english_seo($cat) {
	if ($cat == 1) {
		return "Mobile";
	} else if ($cat == 2) {
		return "Tablets";
	} else if ($cat == 3) {
		return "Computer";
	} else if ($cat == 4) {
		return "Camera";
	} else if ($cat == 5) {
		return "TV";
	} else if ($cat == 6) {
		return "Audio";
	} else if ($cat == 7) {
		return "Accessory";
	} else if ($cat == 8) {
		return "Women Clothing";
	} else if ($cat == 9) {
		return "Men Clothing";
	}
}

/* Return vietnamese category name */
function get_vnese_catname_2($cat) {
	if ($cat == "mobiles") {
		return "Điện Thoại Di Động";
	} else if ($cat == "tablets") {
		return "Máy Tính Bảng";
	} else if ($cat == "computer") {
		return "Máy Vi Tính";
	} else if ($cat == "camera") {
		return "Máy Ảnh";
	} else if ($cat == "tv") {
		return "TV";
	} else if ($cat == "audio") {
		return "Âm Thanh";
	} else if ($cat == "accessories") {
		return "Phụ Kiện Điện Tử";
	} else if ($cat == "female_fashion") {
		return "Thời Trang Nữ";
	} else if ($cat == "male_fashion") {
		return "Thời Trang Nam";
	}
	return "";
}

/* Return vietnamese category name */
function get_catname_2_english($cat) {
	if ($cat == "mobiles") {
		return "Mobile";
	} else if ($cat == "tablets") {
		return "Tablet";
	} else if ($cat == "computer") {
		return "Computer";
	} else if ($cat == "camera") {
		return "Camera";
	} else if ($cat == "tv") {
		return "TV";
	} else if ($cat == "audio") {
		return "Audio";
	} else if ($cat == "accessories") {
		return "Accessory";
	} else if ($cat == "female_fashion") {
		return "Women Clothing";
	} else if ($cat == "male_fashion") {
		return "Men Clothing";
	}
	return "";
}

/* Return category name from category code */
function get_catname_from_code($cat) {
	if ($cat == 1) {
		return "mobile";
	} else if ($cat == 2) {
		return "tablet";
	} else if ($cat == 3) {
		return "computer";
	} else if ($cat == 4) {
		return "camera";
	} else if ($cat == 5) {
		return "tv";
	} else if ($cat == 6) {
		return "audio";
	} else if ($cat == 7) {
		return "accessory";
	} else if ($cat == 8) {
		return "women-clothing";
	} else if ($cat == 9) {
		return "men-clothing";
	}
	return "";
}

/* Return category name from category code */
function get_catname_from_code_2($cat) {
	if ($cat == 1) {
		return "mobiles";
	} else if ($cat == 2) {
		return "tablets";
	} else if ($cat == 3) {
		return "computer";
	} else if ($cat == 4) {
		return "camera";
	} else if ($cat == 5) {
		return "tv";
	} else if ($cat == 6) {
		return "audio";
	} else if ($cat == 7) {
		return "accessories";
	} else if ($cat == 8) {
		return "female_fashion";
	} else if ($cat == 9) {
		return "male_fashion";
	}
	return "";
}

/* Return category name */
function get_catname($cat) {
	$category = array("mobiles", "tablets", "computer", "camera", "tv", "audio", "accessories", "female_fashion", "male_fashion");
	$cat = strtolower(trim($cat));
	if (in_array($cat, $category)) {
		return $cat;
	} else return "";
}

/* Return vietnamese category name */
function get_catcode($cat) {
	if ($cat == "mobiles") {
		return 1;
	} else if ($cat == "tablets") {
		return 2;
	} else if ($cat == "computer") {
		return 3;
	} else if ($cat == "camera") {
		return 4;
	} else if ($cat == "tv") {
		return 5;
	} else if ($cat == "audio") {
		return 6;
	} else if ($cat == "accessories") {
		return 7;
	} else if ($cat == "female_fashion") {
		return 8;
	} else if ($cat == "male_fashion") {
		return 9;
	}
	return 0;
}

/* Remove sign of the vietnamese name of the cateogry */
function cat_remove_sign($cat) {
	$cat = strtolower(trim($cat));
	if ($cat == "") {
		return "";
	} else if ($cat == "mobiles") {
		return "dien-thoai-di-dong";
	} else if ($cat == "tablets") {
		return "may-tinh-bang";
	} else if ($cat == "computer") {
		return "may-vi-tinh";
	} else if ($cat == "camera") {
		return "may-anh";
	} else if ($cat == "tv") {
		return "tv";
	} else if ($cat == "audio") {
		return "am-thanh";
	} else if ($cat == "accessories") {
		return "phu-kien-dien-tu";			
	} else if ($cat == "female_fashion") {
		return "thoi-trang-nu";			
	} else if ($cat == "male_fashion") {
		return "thoi-trang-nam";			
	}
}

/* Remove sign of the vietnamese name of the cateogry */
function cat_remove_sign_english($cat) {
	$cat = strtolower(trim($cat));
	if ($cat == "") {
		return "";
	} else if ($cat == "mobiles") {
		return "mobile";
	} else if ($cat == "tablets") {
		return "tablet";
	} else if ($cat == "computer") {
		return "computer";
	} else if ($cat == "camera") {
		return "camera";
	} else if ($cat == "tv") {
		return "tv";
	} else if ($cat == "audio") {
		return "audio";
	} else if ($cat == "accessories") {
		return "accessory";			
	} else if ($cat == "female_fashion") {
		return "women-clothing";			
	} else if ($cat == "male_fashion") {
		return "men-clothing";			
	}
}

/* Remove sign of the vietnamese name of the subcateogry */
function subcat_remove_sign($subcat) {
	$brand = array("nokia", "apple", "samsung", "lg", "sony", "htc", "motorola", "nikon", "canon", "kodak", "panasonic", "toshiba", "sharp");
	$subcat = strtolower(trim($subcat));
	if ($subcat == "") {
		return "";
	} else if (in_array($subcat, $brand)) {
		return $subcat;
	} else if ($subcat == "other") {
		return "thuong-hieu-khac";
	} else if ($subcat == "laptop") {
		return "may-tinh-xach-tay";
	} else if ($subcat == "desktop") {
		return "may-tinh-de-ban";
	} else if ($subcat == "headphone") {
		return "tai-nghe";
	} else if ($subcat == "speaker") {
		return "thiet-bi-am-thanh";
	} else if ($subcat == "mp3 player") {
		return "may-nghe-nhac";			
	} else if ($subcat == "phone accessories") {
		return "phu-kien-dien-thoai";			
	} else if ($subcat == "tablet accessories") {
		return "phu-kien-may-tinh-bang";			
	} else if ($subcat == "computer accessories") {
		return "phu-kien-may-tinh";			
	} else if ($subcat == "shirt") {
		return "ao";			
	} else if ($subcat == "pants") {
		return "quan";			
	} else if ($subcat == "dress") {
		return "vay";			
	} else if ($subcat == "shoes") {
		return "giay";			
	} else if ($subcat == "accessories") {
		return "phu-kien";			
	} else if ($subcat == "female_underwear_swimsuit") {
		return "do-ngu-do-lot-bikini";			
	} else if ($subcat == "male_underwear_swimsuit") {
		return "do-ngu-do-lot-do-boi";			
	} 

	return $subcat;
}

/* Remove sign of the vietnamese name of the subcateogry */
function subcat_remove_sign_english($subcat) {
	$brand = array("nokia", "apple", "samsung", "lg", "sony", "htc", "motorola", "nikon", "canon", "kodak", "panasonic", "toshiba", "sharp");
	$subcat = strtolower(trim($subcat));
	if ($subcat == "") {
		return "";
	} else if (in_array($subcat, $brand)) {
		return $subcat;
	} else if ($subcat == "other") {
		return "other-brands";
	} else if ($subcat == "laptop") {
		return "laptop";
	} else if ($subcat == "desktop") {
		return "desktop";
	} else if ($subcat == "headphone") {
		return "headphone";
	} else if ($subcat == "speaker") {
		return "speaker";
	} else if ($subcat == "mp3 player") {
		return "mp3-player";			
	} else if ($subcat == "phone accessories") {
		return "phone-accessories";			
	} else if ($subcat == "tablet accessories") {
		return "tablet-accessories";			
	} else if ($subcat == "computer accessories") {
		return "computer-accessories";			
	} else if ($subcat == "shirt") {
		return "shirt";			
	} else if ($subcat == "pants") {
		return "pants";			
	} else if ($subcat == "dress") {
		return "dress";			
	} else if ($subcat == "shoes") {
		return "shoes";			
	} else if ($subcat == "accessories") {
		return "accessories";			
	} else if ($subcat == "female_underwear_swimsuit") {
		return "underwear-swimsuit";			
	} else if ($subcat == "male_underwear_swimsuit") {
		return "underwear";			
	} 

	return $subcat;
}

/* Return a list of vietnamese-name subcat of a category */
function return_subcat($cat) {
	if ($cat == 1) {
		$subcat = array("Nokia","Apple","Samsung","LG","Sony","HTC","Motorola","Thương Hiệu Khác");
		return $subcat;
	} else if ($cat == 2) {
		return null;
	} else if ($cat == 3) {
		$subcat = array("Máy Tính Xách Tay","Máy Tính Để Bàn");
		return $subcat;
	} else if ($cat == 4) {
		$subcat = array("Nikon","Sony","Samsung","Canon","Kodak","Thương Hiệu Khác");
		return $subcat;
	} else if ($cat == 5) {
		$subcat = array("Sony","Samsung","Panasonic","LG","Toshiba","Sharp","Thương Hiệu Khác");
		return $subcat;
	} else if ($cat == 6) {
		$subcat = array("Tai Nghe","Thiết Bị Âm Thanh","Máy Nghe Nhạc");
		return $subcat;
	} else if ($cat == 7) {
		$subcat = array("Phụ Kiện Điện Thoại","Phụ Kiện Máy Tính Bảng","Phụ Kiện Máy Tính");
		return $subcat;
	} else if ($cat == 8) {
		$subcat = array("Áo","Quần","Váy", "Giày", "Phụ Kiện", "Đồ Ngủ - Đồ Lót - Bikini");
		return $subcat;
	} else if ($cat == 9) {
		$subcat = array("Áo","Quần", "Giày", "Phụ Kiện", "Đồ Ngủ - Đồ Lót - Đồ Bơi");
		return $subcat;
	} 
	return null;
}

/* Return a list of vietnamese-name subcat of a category */
function return_subcat_english($cat) {
	if ($cat == 1) {
		$subcat = array("Nokia","Apple","Samsung","LG","Sony","HTC","Motorola","Other Brands");
		return $subcat;
	} else if ($cat == 2) {
		return null;
	} else if ($cat == 3) {
		$subcat = array("Laptop","Desktop");
		return $subcat;
	} else if ($cat == 4) {
		$subcat = array("Nikon","Sony","Samsung","Canon","Kodak","Other Brands");
		return $subcat;
	} else if ($cat == 5) {
		$subcat = array("Sony","Samsung","Panasonic","LG","Toshiba","Sharp","Other Brands");
		return $subcat;
	} else if ($cat == 6) {
		$subcat = array("Headphone","Speaker","Mp3 Player");
		return $subcat;
	} else if ($cat == 7) {
		$subcat = array("Phone Accessories","Tablet Accessories","Computer Accessories");
		return $subcat;
	} else if ($cat == 8) {
		$subcat = array("Shirt","Pants","Dress", "Shoes", "Accessories", "Underwear Swimsuit");
		return $subcat;
	} else if ($cat == 9) {
		$subcat = array("Shirt","Pants", "Shoes", "Accessories", "Underwear");
		return $subcat;
	} 
	return null;
}


/* Display html categories hardcoded */
function display_categories ($hostname, $username, $password, $db, $current_language, $nofollow = false) {
	echo "<li class=\"subMenuLeft\">\n";
	echo "<a href=\"/mobile\" >Mobile</a>\n";
	echo "<ul>\n";
	echo "<li class=\"first_li\">Mobile</li>\n";
	echo "<li><a href=\"/mobile/nokia\" >Nokia</a></li>\n";
	echo "<li><a href=\"/mobile/apple\" >Apple</a></li>\n";
	echo "<li><a href=\"/mobile/samsung\" >Samsung</a></li>\n";
	echo "<li><a href=\"/mobile/lg\" >LG</a></li>\n";
	echo "<li><a href=\"/mobile/sony\" >Sony</a></li>\n";
	echo "<li><a href=\"/mobile/htc\" >HTC</a></li>\n";
	echo "<li><a href=\"/mobile/motorola\" >Motorola</a></li>\n";
	echo "<li><a href=\"/mobile/other-brands\" >Other Brands</a></li>\n";
	echo "</ul>\n";
	echo "</li>\n";
	echo "<li><a href=\"/tablet\" >Tablet</a></li>\n";
	echo "<li class=\"subMenuLeft\">\n";
	echo "<a href=\"/computer\" >Computer</a>\n";
	echo "<ul>\n";
	echo "<li class=\"first_li\">Computer</li>\n";
	echo "<li><a href=\"/computer/laptop\" >Laptop</a></li>\n";
	echo "<li><a href=\"/computer/desktop\" >Desktop</a></li>\n";
	echo "</ul>\n";
	echo "</li>\n";
	echo "<li class=\"subMenuLeft\">\n";
	echo "<a href=\"/camera\" >Camera</a>\n";
	echo "<ul>\n";
	echo "<li class=\"first_li\">Camera</li>\n";
	echo "<li><a href=\"/camera/nikon\" >Nikon</a></li>\n";
	echo "<li><a href=\"/camera/sony\" >Sony</a></li>\n";
	echo "<li><a href=\"/camera/samsung\" >Samsung</a></li>\n";
	echo "<li><a href=\"/camera/canon\" >Canon</a></li>\n";
	echo "<li><a href=\"/camera/kodak\" >Kodak</a></li>\n";
	echo "<li><a href=\"/camera/other-brands\" >Other Brands</a></li>\n";
	echo "</ul>\n";
	echo "</li>\n";
	echo "<li class=\"subMenuLeft\">\n";
	echo "<a href=\"/tv\" >TV</a>\n";
	echo "<ul>\n";
	echo "<li class=\"first_li\">TV</li>\n";
	echo "<li><a href=\"/tv/sony\" >Sony</a></li>\n";
	echo "<li><a href=\"/tv/samsung\" >Samsung</a></li>\n";
	echo "<li><a href=\"/tv/panasonic\" >Panasonic</a></li>\n";
	echo "<li><a href=\"/tv/lg\" >LG</a></li>\n";
	echo "<li><a href=\"/tv/toshiba\" >Toshiba</a></li>\n";
	echo "<li><a href=\"/tv/sharp\" >Sharp</a></li>\n";
	echo "<li><a href=\"/tv/other-brands\" >Other Brands</a></li>\n";
	echo "</ul>\n";
	echo "</li>\n";
	echo "<li class=\"subMenuLeft\">\n";
	echo "<a href=\"/audio\" >Audio</a>\n";
	echo "<ul>\n";
	echo "<li class=\"first_li\">Audio</li>\n";
	echo "<li><a href=\"/audio/headphone\" >Headphone</a></li>\n";
	echo "<li><a href=\"/audio/speaker\" >Speaker</a></li>\n";
	echo "<li><a href=\"/audio/mp3-player\" >Mp3 Player</a></li>\n";
	echo "</ul>\n";
	echo "</li>\n";
	echo "<li class=\"subMenuLeft\">\n";
	echo "<a href=\"/accessory\" >Accessory</a>\n";
	echo "<ul>\n";
	echo "<li class=\"first_li\">Accessory</li>\n";
	echo "<li><a href=\"/accessory/phone-accessories\" >Phone Accessories</a></li>\n";
	echo "<li><a href=\"/accessory/tablet-accessories\" >Tablet Accessories</a></li>\n";
	echo "<li><a href=\"/accessory/computer-accessories\" >Computer Accessories</a></li>\n";
	echo "</ul>\n";
	echo "</li>\n";
	echo "<li class=\"subMenuLeft\">\n";
	echo "<a href=\"/women-clothing\" >Women Clothing</a>\n";
	echo "<ul>\n";
	echo "<li class=\"first_li\">Women Clothing</li>\n";
	echo "<li><a href=\"/women-clothing/shirt\" >Shirt</a></li>\n";
	echo "<li><a href=\"/women-clothing/pants\" >Pants</a></li>\n";
	echo "<li><a href=\"/women-clothing/dress\" >Dress</a></li>\n";
	echo "<li><a href=\"/women-clothing/shoes\" >Shoes</a></li>\n";
	echo "<li><a href=\"/women-clothing/accessories\" >Accessories</a></li>\n";
	echo "<li><a href=\"/women-clothing/underwear-swimsuit\" >Underwear Swimsuit</a></li>\n";
	echo "</ul>\n";
	echo "</li>\n";
	echo "<li class=\"lastMenuLeft\">\n";
	echo "<a href=\"/men-clothing\" >Men Clothing</a>\n";
	echo "<ul>\n";
	echo "<li class=\"first_li\">Men Clothing</li>\n";
	echo "<li><a href=\"/men-clothing/shirt\" >Shirt</a></li>\n";
	echo "<li><a href=\"/men-clothing/pants\" >Pants</a></li>\n";
	echo "<li><a href=\"/men-clothing/shoes\" >Shoes</a></li>\n";
	echo "<li><a href=\"/men-clothing/accessories\" >Accessories</a></li>\n";
	echo "<li><a href=\"/men-clothing/underwear\" >Underwear</a></li>\n";
	echo "</ul>\n";
	echo "</li>\n";
}

/* Return the title of the search page depend on the category */ 
function search_title($cat, $subcat) {
	$cat = strtolower($cat);
	$subcat = strtolower($subcat);

	/* Subcateteogry page */
	if (!empty($subcat)) {
		$cat_english = get_catname_2_english($cat);
		if (!empty($vnese_cat)) {
			return "Compare prices $cat_english $subcat at large ecommerce sites";
		}
	}

	$date = date("Y")." ".intval(date("d"))." ".convert_month(intval(date("m")));

	if (!empty($subcat))
	{
		$subcat[0] = strtoupper($subcat[0]);
	}

	if ($cat == "mobiles") {
		if (!empty($subcat))
		{
			return $subcat." Mobile Phones Price in Philippines: Buy $subcat Mobile Phones online | Price Pony";
		}

		return "Mobile Phones Price in Philippines: Buy Mobile Phones online | Price Pony";
	} else if ($cat == "tablets") {
		return "Tablets Price in Philippines: Buy Tablets online | Price Pony";
	} else if ($cat == "computer") {
		if (!empty($subcat))
		{
			return $subcat." Price in Philippines: Buy ".$subcat." online | Price Pony";
		}

		return "Computers Price in Philippines: Buy Computers online | Price Pony";
	} else if ($cat == "camera") {
		if (!empty($subcat))
		{
			return $subcat." Cameras Price in Philippines: Buy ".$subcat." Cameras online | Price Pony";
		}

		return "Cameras Price in Philippines: Buy Cameras online | Price Pony";
	} else if ($cat == "tv") {
		if (!empty($subcat))
		{
			return $subcat." TVs Price in Philippines: Buy ".$subcat." TVs online | Price Pony";
		}

		return "TVs Price in Philippines: Buy TVs online | Price Pony";
	} else if ($cat == "audio") {
		if (!empty($subcat))
		{
			return $subcat." Price in Philippines: Buy ".$subcat." online | Price Pony";
		}

		return "Audio Price in Philippines: Buy Audio online | Price Pony";
	} else if ($cat == "accessories") {
		if (!empty($subcat))
		{
			return $subcat." Price in Philippines: Buy ".$subcat." online | Price Pony";
		}

		return "Electronic Accessories Price in Philippines: Buy Electronic Accessories online | Price Pony";
	} 
	return "Low prices, original prices, daily deals, where to buy good price products ";	
}

/* Return the description of the search page depend on the category */
function search_description($cat, $subcat) {
	$cat = strtolower($cat);
	$subcat = strtolower($subcat);

	/* Subcateteogry page */
	if (!empty($subcat)) {
		$cat_english = get_catname_2_english($cat);
		if (!empty($vnese_cat)) {
			return "$cat_english $subcat ở đâu giá rẻ nhất? Xem ngay bảng So sánh giá các loại $cat_english $subcat tại các trung tâm điện máy lớn và uy tín của Việt Nam";
		}
	}

	if ($cat == "mobiles") {
		return "Where to buy low price mobiles? Take a look at Pricepony price comparison page of mobiles from Nokia, Apple, Samsung, LG, Sony, HTC, Motorola... at biggest ecommerce sites";
	} else if ($cat == "tablets") {
		return "Tablet - Where to buy low price tablets? Take a look at Pricepony price comparison page of tablets from Apple, Samsung, Asus, Acer,... at biggest ecommerce sites";
	} else if ($cat == "computer") {
		return "Where to buy low price laptops? Take a look at Pricepony price comparison page of laptops, ultrabook from Sony, Apple, Acer, Dell, Lenovo,... at biggest ecommerce sites";
	} else if ($cat == "camera") {
		return "Compare prices of mechanical cameras, point and shoot cameras, DSLR cameras from Sony, Canon, Fujifilm, Nikon, Samsung,... at biggest ecommerce sites";
	} else if ($cat == "tv") {
		return "Where to buy low prices TV? Take a look at Pricepony price comparison page of TV LED, LCD, CRT from Sony, LG, Samsung, Toshiba, Sharp,... at biggest ecommerce sites";
	} else if ($cat == "audio") {
		return "Where to buy low price headphones, speakers, mp3 players? Take a look at price comparison page of headphones, speakers, mp3 players,... at biggest ecommerce sites";
	} else if ($cat == "accessories") {
		return "Where to buy low price phone accessories, tablet accessories, computer accessories? Take a look at price comparison page of phone accessories, tablet accessories, computer accessories at buggest ecommerce sites";
	} 
	return "Low prices, original prices, daily deals, where to buy good price products ";	
}

/* Return the keywords of the search page depend on the category */
function search_keyword($cat, $subcat) {
	$cat = strtolower($cat);
	$subcat = strtolower($subcat);

	/* Subcateteogry page */
	if (!empty($subcat)) {
		$cat_english = get_catname_2_english($cat);
		if (!empty($vnese_cat)) {
			return "price comparison, $cat_english price, $cat_english $subcat price, $cat_english price, $cat_english $subcat, $subcat price";
		}
	}

	if ($cat == "mobiles") {
		return "price comparison, mobile phones' prices, nokia phones' prices, apple phones' prices, samsung phones' prices, lg phones' prices, sony phones' prices, htc phones' prices, motorola phones' prices";
	} else if ($cat == "tablets") {
		return "price comparison, tablets' prices, apple tablets' prices, ipad's prices, samsung tablets' prices, galaxy tab prices, asus tablets' prices, acer tablets' prices";
	} else if ($cat == "computer") {
		return "price comparison, computers' prices, laptops' prices, ultrabooks' prices, sony laptops' prices, acer laptops' prices, macbook price, dell laptops' price, lenovo laptops' prices";
	} else if ($cat == "camera") {
		return "price comparison, cameras' prices, point and shoot cameras' prices, mechanical cameras' prices, DSLR cameras' prices, sony cameras' prices, canon cameras' prices, nikon cameras' prices, samsung cameras' prices, fujifilm cameras' prices";
	} else if ($cat == "tv") {
		return "price comaprison, tv prices, lcd tv prices, led tv prices, sony tv prices, lg tv prices, samsung tv prices, toshiba tv prices, toshiba tv prices, sharp tv prices";
	} else if ($cat == "audio") {
		return "price comaprison, headphone prices, speaker prices, mp3 players' prces, ipod prices";
	} else if ($cat == "accessories") {
		return "price comparison, phone accessories' prices, tablet accessories' prices, laptop accessories' prices";
	} 
	return "Low prices everyday - Look for good price product - Pricepony";	
}

/* Return the title of the detail page depend on the category */ 
function detail_title($name, $cat, $subcat, $price_in_country) {
	//return $name." - Price Philippines | Pricepony.com.ph";
	return $name." ".$price_in_country." | Price Pony";
}
?>