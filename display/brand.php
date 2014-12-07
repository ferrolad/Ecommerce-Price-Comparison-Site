<?php
function replace_to_correct_brand_name($name) {
	if ($name == "conspeed") $name = "connspeed";
	if ($name == "iphone" || $name == "ipad") $name = "apple";
	if ($name == "coolpix") $name = "nikon";
	return $name;
}

/*function check_product_brand($keyword_name, $brand_list)
{
	// One word brand 
	for ($i = 0; $i < sizeof($keyword_name); $i++) {
		$keyword_name[$i] = replace_to_correct_brand_name($keyword_name[$i]);
		if (in_array($keyword_name[$i], $brand_list)) {
			return $keyword_name[$i];
		}
	}

	// Two word brand 
	for ($i = 0; $i < sizeof($keyword_name)-1; $i++) {
		$keyword = $keyword_name[$i]." ".$keyword_name[$i+1];
		if (in_array($keyword, $brand_list)) {
			return $keyword;
		}
	}

	// Three word brand 
	for ($i = 0; $i < sizeof($keyword_name)-1; $i++) {
		$keyword = $keyword_name[$i]." ".$keyword_name[$i+1]
		if (in_array($keyword, $brand_list)) {
			return $keyword;
		}
	}
	return "";
}*/

function check_product_brand($name, $brand_list)
{
	$longest_match_brand = "";
	$longest_match_brand_id = "";

	for ($i = 0; $i < sizeof($brand_list); ++$i)
	{
		$brand = $brand_list[$i];
		$brand_name = $brand["brand"];

		// Create keyword that specify a brand
		$brand_name_keyword = explode(";", $brand_name);

		$check = true;
		for ($j = 0; $j < sizeof($brand_name_keyword); ++$j)
		{
			if (stripos($name, $brand_name_keyword[$j]) === false)
			{
				$check = false;
				break;
			}
		}

		if ($check && strlen($brand_name) > strlen($longest_match_brand))
		{
			$longest_match_brand_id = $brand["id"];
			$longest_match_brand = $brand_name;
		}
	}

	return $longest_match_brand_id;
}

/* Function return true if the word is a brand word and is important (Nokia, Samsung, ....), false otherwises */
function important_brand_word($cat, $subcat) {
	$brand;
	/* Mobiles */
	if ($cat == 1) {
		$brand = array("samsung", "nikon", "fujifilm", "sony", "canon", "pentax", "anaconda", "acer", "asus", "apple", "axioo", "hp", "lenovo", "toshiba", "fujitsu", "dell", "gateway", "ricoh", "panasonic", "brother", "oki", "epson", "fuji xerox", "yamaha", "tech mate", "tcl", "arirang", "boston", "sharp", "mpins", "lg", "de nhat", "denon", "gunners", "monster", "philips", "pioneer", "rapoo", "beehd", "akai", "vson", "vtb", "geniatech", "tvzoneplus", "nokia", "huawei", "q mobile", "q smart", "avio", "blackberry", "mobiistar", "oppo", "connspeed", "fpt", "htc", "otic", "sony ericsson", "malata", "motorola", "mobell", "gionee", "kingcom", "hanel", "alcatel", "aoson", "pop", "haipad", "opad", "coolpad", "coby", "kindle", "jsmax", "pipo", "dopad", "cutepad", "knc", "pi", "popcom", "viewsonic", "apad", "viewpad", "daza", "bipad", "archos", "ainol", "arnova", "google", "elead", "singpc", "cms", "kodak", "klipsch", "jamo", "microtek", "sonicgear", "microlab", "logitech", "soundmax", "genius");
	} else if ($cat == 2) {
		/* Tablet */
		$brand = array("samsung", "nikon", "fujifilm", "sony", "canon", "pentax", "anaconda", "acer", "asus", "apple", "axioo", "hp", "lenovo", "toshiba", "fujitsu", "dell", "gateway", "ricoh", "panasonic", "brother", "oki", "epson", "fuji xerox", "yamaha", "tech mate", "tcl", "arirang", "boston", "sharp", "mpins", "lg", "de nhat", "denon", "gunners", "monster", "philips", "pioneer", "rapoo", "beehd", "akai", "vson", "vtb", "geniatech", "tvzoneplus", "nokia", "huawei", "q mobile", "q smart", "avio", "blackberry", "mobiistar", "oppo", "connspeed", "fpt", "htc", "otic", "sony ericsson", "malata", "motorola", "mobell", "gionee", "kingcom", "hanel", "alcatel", "aoson", "pop", "haipad", "opad", "coolpad", "coby", "kindle", "jsmax", "pipo", "dopad", "cutepad", "knc", "pi", "popcom", "viewsonic", "apad", "viewpad", "daza", "bipad", "archos", "ainol", "arnova", "google", "elead", "singpc", "cms", "kodak", "klipsch", "jamo", "microtek", "sonicgear", "microlab", "logitech", "soundmax", "genius");
	} else if ($cat == 3) {
		/* Computer */
		$brand = array("samsung", "nikon", "fujifilm", "sony", "canon", "pentax", "anaconda", "acer", "asus", "apple", "axioo", "hp", "lenovo", "toshiba", "fujitsu", "dell", "gateway", "ricoh", "panasonic", "brother", "oki", "epson", "fuji xerox", "yamaha", "tech mate", "tcl", "arirang", "boston", "sharp", "mpins", "lg", "de nhat", "denon", "gunners", "monster", "philips", "pioneer", "rapoo", "beehd", "akai", "vson", "vtb", "geniatech", "tvzoneplus", "nokia", "huawei", "q mobile", "q smart", "avio", "blackberry", "mobiistar", "oppo", "connspeed", "fpt", "htc", "otic", "sony ericsson", "malata", "motorola", "mobell", "gionee", "kingcom", "hanel", "alcatel", "aoson", "pop", "haipad", "opad", "coolpad", "coby", "kindle", "jsmax", "pipo", "dopad", "cutepad", "knc", "pi", "popcom", "viewsonic", "apad", "viewpad", "daza", "bipad", "archos", "ainol", "arnova", "google", "elead", "singpc", "cms", "kodak", "klipsch", "jamo", "microtek", "sonicgear", "microlab", "logitech", "soundmax", "genius");
	} else if ($cat == 4) {
		/* Camera */
		$brand = array("samsung", "nikon", "fujifilm", "sony", "canon", "pentax", "anaconda", "acer", "asus", "apple", "axioo", "hp", "lenovo", "toshiba", "fujitsu", "dell", "gateway", "ricoh", "panasonic", "brother", "oki", "epson", "fuji xerox", "yamaha", "tech mate", "tcl", "arirang", "boston", "sharp", "mpins", "lg", "de nhat", "denon", "gunners", "monster", "philips", "pioneer", "rapoo", "beehd", "akai", "vson", "vtb", "geniatech", "tvzoneplus", "nokia", "huawei", "q mobile", "q smart", "avio", "blackberry", "mobiistar", "oppo", "connspeed", "fpt", "htc", "otic", "sony ericsson", "malata", "motorola", "mobell", "gionee", "kingcom", "hanel", "alcatel", "aoson", "pop", "haipad", "opad", "coolpad", "coby", "kindle", "jsmax", "pipo", "dopad", "cutepad", "knc", "pi", "popcom", "viewsonic", "apad", "viewpad", "daza", "bipad", "archos", "ainol", "arnova", "google", "elead", "singpc", "cms", "kodak", "klipsch", "jamo", "microtek", "sonicgear", "microlab", "logitech", "soundmax", "genius");
	} else if ($cat == 5) {
		/* TV */
		$brand = array("samsung", "nikon", "fujifilm", "sony", "canon", "pentax", "anaconda", "acer", "asus", "apple", "axioo", "hp", "lenovo", "toshiba", "fujitsu", "dell", "gateway", "ricoh", "panasonic", "brother", "oki", "epson", "fuji xerox", "yamaha", "tech mate", "tcl", "arirang", "boston", "sharp", "mpins", "lg", "de nhat", "denon", "gunners", "monster", "philips", "pioneer", "rapoo", "beehd", "akai", "vson", "vtb", "geniatech", "tvzoneplus", "nokia", "huawei", "q mobile", "q smart", "avio", "blackberry", "mobiistar", "oppo", "connspeed", "fpt", "htc", "otic", "sony ericsson", "malata", "motorola", "mobell", "gionee", "kingcom", "hanel", "alcatel", "aoson", "pop", "haipad", "opad", "coolpad", "coby", "kindle", "jsmax", "pipo", "dopad", "cutepad", "knc", "pi", "popcom", "viewsonic", "apad", "viewpad", "daza", "bipad", "archos", "ainol", "arnova", "google", "elead", "singpc", "cms", "kodak", "klipsch", "jamo", "microtek", "sonicgear", "microlab", "logitech", "soundmax", "genius");
	} else if ($cat == 6) {
		/* Audio */
		$brand = array("samsung", "nikon", "fujifilm", "sony", "canon", "pentax", "anaconda", "acer", "asus", "apple", "axioo", "hp", "lenovo", "toshiba", "fujitsu", "dell", "gateway", "ricoh", "panasonic", "brother", "oki", "epson", "fuji xerox", "yamaha", "tech mate", "tcl", "arirang", "boston", "sharp", "mpins", "lg", "de nhat", "denon", "gunners", "monster", "philips", "pioneer", "rapoo", "beehd", "akai", "vson", "vtb", "geniatech", "tvzoneplus", "nokia", "huawei", "q mobile", "q smart", "avio", "blackberry", "mobiistar", "oppo", "connspeed", "fpt", "htc", "otic", "sony ericsson", "malata", "motorola", "mobell", "gionee", "kingcom", "hanel", "alcatel", "aoson", "pop", "haipad", "opad", "coolpad", "coby", "kindle", "jsmax", "pipo", "dopad", "cutepad", "knc", "pi", "popcom", "viewsonic", "apad", "viewpad", "daza", "bipad", "archos", "ainol", "arnova", "google", "elead", "singpc", "cms", "kodak", "klipsch", "jamo", "microtek", "sonicgear", "microlab", "logitech", "soundmax", "genius");
	} else if ($cat == 7) {
		/* *Accessory */
		$brand = array("samsung", "nikon", "fujifilm", "sony", "canon", "pentax", "anaconda", "acer", "asus", "apple", "axioo", "hp", "lenovo", "toshiba", "fujitsu", "dell", "gateway", "ricoh", "panasonic", "brother", "oki", "epson", "fuji xerox", "yamaha", "tech mate", "tcl", "arirang", "boston", "sharp", "mpins", "lg", "de nhat", "denon", "gunners", "monster", "philips", "pioneer", "rapoo", "beehd", "akai", "vson", "vtb", "geniatech", "tvzoneplus", "nokia", "huawei", "q mobile", "q smart", "avio", "blackberry", "mobiistar", "oppo", "connspeed", "fpt", "htc", "otic", "sony ericsson", "malata", "motorola", "mobell", "gionee", "kingcom", "hanel", "alcatel", "aoson", "pop", "haipad", "opad", "coolpad", "coby", "kindle", "jsmax", "pipo", "dopad", "cutepad", "knc", "pi", "popcom", "viewsonic", "apad", "viewpad", "daza", "bipad", "archos", "ainol", "arnova", "google", "elead", "singpc", "cms", "kodak", "klipsch", "jamo", "microtek", "sonicgear", "microlab", "logitech", "soundmax", "genius");
	} else if ($cat == 8) {
		/* *Female Fashion */
		$brand = array("adidas", "amazingtee", "nanas", "asia", "active", "atlas", "atypical", "bambo", "biti", "blue", "borani", "bourjois", "calvin", "canifa", "casio", "casways", "mezzo", "christian", "codad", "converse", "beckham", "dc", "eko", "esprit", "gosto", "guess", "gattini", "hacene", "circle", "koumi", "lempicka", "maschio", "milaross", "catsashop", "foci", "gal", "gap", "girlie", "balance", "nike", "nivea", "olalasexy", "onitsuka", "opi", "pierre", "playboy", "procare", "prospero", "puma", "reebok", "relax", "resources", "revlon", "sanding", "sidewalk", "borrowed", "somiviet", "speedo", "styluk", "clozet", "doll", "trend", "z collections", "zoo", "unicol", "vascara", "voschy", "wannabe", "laurent", "zilandi", "zipit", "zjstar", "5c", "nghi");
	} else if ($cat == 9) {
		/* *Male Fashion */
		$brand = array("adidas", "amazingtee", "nanas", "asia", "active", "atlas", "atypical", "bambo", "biti", "blue", "borani", "bourjois", "calvin", "canifa", "casio", "casways", "mezzo", "christian", "codad", "converse", "beckham", "dc", "eko", "esprit", "gosto", "guess", "gattini", "hacene", "circle", "koumi", "lempicka", "maschio", "milaross", "catsashop", "foci", "gal", "gap", "girlie", "balance", "nike", "nivea", "olalasexy", "onitsuka", "opi", "pierre", "playboy", "procare", "prospero", "puma", "reebok", "relax", "resources", "revlon", "sanding", "sidewalk", "borrowed", "somiviet", "speedo", "styluk", "clozet", "doll", "trend", "z collections", "zoo", "unicol", "vascara", "voschy", "wannabe", "laurent", "zilandi", "zipit", "zjstar", "5c", "nghi");
	}

	return $brand;
}
?>