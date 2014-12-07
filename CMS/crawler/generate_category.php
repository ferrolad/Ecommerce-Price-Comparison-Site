<?php

/* Function given the category name (mobiles, tablets, etc..) return the category id*/
function generate_catid($category) {
	$catid = 0;
	if ($category == "mobiles" || $category == "smartphone" || $category == "cheap_phones" || $category == "middle_phones" || $category == "high_phones" || $category == "super_phones") {
		$catid = 1;
	} else if ($category == "tablets") {
		$catid = 2;
	} else if ($category == "laptop") {
		$catid = 3;
	} else if ($category == "ultrabook") {
		$catid = 3;
	} else if ($category == "desktop") {
		$catid = 3;
	} else if ($category == "camera") {
		$catid = 4;
	} else if ($category == "professional_camera") {
		$catid = 4;
	} else if ($category == "tv" || $category == "tv_box" || $category == "tv_led" || $category == "tv_lcd" || $category == "tv_plasma" || $category == "tv_crt") {
		$catid = 5;
	} else if ($category == "mp3") {
		$catid = 6;
	} else if ($category == "mp4") {
		$catid = 6;
	} else if ($category == "speaker") {
		$catid = 6;
	} else if ($category == "mobile_speaker") {
		$catid = 6;
	} else if ($category == "speaker_system") {
		$catid = 6;
	} else if ($category == "computer_speaker") {
		$catid = 6;
	} else if ($category == "multipurpose_speaker") {
		$catid = 6;
	} else if ($category == "gaming_headphone") {
		$catid = 6;
	} else if ($category == "headphone") {
		$catid = 6;
	} else if ($category == "router") {
		$catid = 7;
	} else if ($category == "software") {
		$catid = 7;
	} else if ($category == "amply") {
		$catid = 6;
	} else if ($category == "cassette") {
		$catid = 6;
	} else if (strpos($category, "phone_accessory") !== false) {
		$catid = 7;
	} else if ($category == "phone_audio") {
		$catid = 7;
	} else if ($category == "phone_battery") {
		$catid = 7;
	} else if ($category == "phone_cover") {
		$catid = 7;
	} else if ($category == "phone_memory_card") {
		$catid = 7;
	} else if ($category == "phone_card_reader") {
		$catid = 7;
	} else if ($category == "phone_prepaid_card") {
		$catid = 7;
	} else if ($category == "phone_stick_cover") {
		$catid = 7;
	} else if ($category == "video_recorder") {
		$catid = 7;
	} else if ($category == "disk_player") {
		$catid = 7;
	} else if (strpos($category, "computer_accessory") !== false) {
		$catid = 7;
	} else if ($category == "tablet_accessory") {
		$catid = 7;
	} else if ($category == "laptop_accessory") {
		$catid = 7;
	} else if ($category == "recorder") {
		$catid = 7;
	} else if ($category == "dictionary") {
		$catid = 7;
	} else if ($category == "accessories") {
		$catid = 7;
	} else if ($category == "usb3g") {
		$catid = 7;
	} else if ($category == "calculator") {
		$catid = 7;
	} else if ($category == "monitor") {
		$catid = 7;
	} else if ($category == "mouse" || $category == "gaming_mouse") {
		$catid = 7;
	} else if ($category == "keyboard" || $category == "gaming_keyboard") {
		$catid = 7;
	} else if ($category == "usb") {
		$catid = 7;
	} else if ($category == "cooler_pad") {
		$catid = 7;
	} else if ($category == "mouse_pad") {
		$catid = 7;
	} else if ($category == "network_device") {
		$catid = 7;
	} else if ($category == "gaming_devices") {
		$catid = 7;
	} else if ($category == "external_drive") {
		$catid = 7;
	//else if ($category == "female_high_heel_shoes" || $category == "female_ballet_shoes" ||$category == "female_sandals" ||$category == "female_dress" ||$category == "female_shirt" ||$category == "female_pants" ||$category == "female_accessories") {
	} else if (strpos($category, "female") !== false) {
		$catid = 8;
	//} else if ($category == "male_casual_shoes" || $category == "male_slip_on_shoes" ||$category == "male_canvas_shoes" ||$category == "male_shirt" ||$category == "male_tshirt" ||$category == "male_jeans" ||$category == "male_accessories") {
	} else if (strpos($category, "male") !== false && strpos($category, "female") == false) {
		$catid = 9;
	} 
	return $catid;
}

/* Function given the category name (mobiles, tablets, etc..), the category id (1, 2, 3, etc..)
and the name of the product () return the sub category id*/
function generate_subcatid($category, $name) {
	$subcat = 0;
	if ($category == "tablets") {
		$subcat = 0;
	} else if ($category == "computer") {
		$subcat = 9;
	} else if ($category == "laptop") {
		$subcat = 9;
	} else if ($category == "ultrabook") {
		$subcat = 9;
	} else if ($category == "desktop") {
		$subcat = 10;
	} else if ($category == "mp3") {
		$subcat = 26;
	} else if ($category == "mp4") {
		$subcat = 26;
	} else if ($category == "speaker") {
		$subcat = 25;
	} else if ($category == "mobile_speaker") {
		$subcat = 25;
	} else if ($category == "speaker_system") {
		$subcat = 25;
	} else if ($category == "computer_speaker") {
		$subcat = 25;
	} else if ($category == "multipurpose_speaker") {
		$subcat = 25;
	} else if ($category == "gaming_headphone") {
		$subcat = 24;
	} else if ($category == "headphone") {
		$subcat = 24;
	} else if ($category == "router") {
		$subcat = 29;
	} else if ($category == "software") {
		$subcat = 29;
	} else if ($category == "amply") {
		$subcat = 25;
	} else if ($category == "cassette") {
		$subcat = 25;
	} else if (strpos($category, "phone_accessory") !== false) {
		$subcat = 27;
	} else if ($category == "phone_audio") {
		$subcat = 27;
	} else if ($category == "phone_battery") {
		$subcat = 27;
	} else if ($category == "phone_cover") {
		$subcat = 27;
	} else if ($category == "phone_memory_card") {
		$subcat = 27;
	} else if ($category == "phone_card_reader") {
		$subcat = 27;
	} else if ($category == "phone_prepaid_card") {
		$subcat = 27;
	} else if ($category == "phone_stick_cover") {
		$subcat = 27;
	} else if ($category == "video_recorder") {
		$subcat = 29;
	} else if ($category == "disk_player") {
		$subcat = 29;
	} else if (strpos($category, "computer_accessory") !== false) {
		$subcat = 29;
	} else if ($category == "tablet_accessory") {
		$subcat = 28;
	} else if ($category == "laptop_accessory") {
		$subcat = 29;
	} else if ($category == "recorder") {
		$subcat = 29;
	} else if ($category == "dictionary") {
		$subcat = 29;
	} else if ($category == "accessories") {
		$subcat = 28;
	} else if ($category == "usb3g") {
		$subcat = 29;
	} else if ($category == "calculator") {
		$subcat = 29;
	} else if ($category == "monitor") {
		$subcat = 29;
	} else if ($category == "mouse" || $category == "gaming_mouse") {
		$subcat = 29;
	} else if ($category == "keyboard" || $category == "gaming_keyboard") {
		$subcat = 29;
	} else if ($category == "usb") {
		$subcat = 29;
	} else if ($category == "cooler_pad") {
		$subcat = 29;
	} else if ($category == "mouse_pad") {
		$subcat = 29;
	} else if ($category == "network_device") {
		$subcat = 29;
	} else if ($category == "gaming_devices") {
		$subcat = 29;
	} else if ($category == "external_drive") {
		$subcat = 29;
	} else if ($category == "mobiles" || $category == "smartphone" || $category == "cheap_phones" || $category == "middle_phones" || $category == "high_phones" || $category == "super_phones") {
		if(stristr($name, 'nokia')) { 
			$subcat = 1;
		} else if (stristr($name, 'iphone')) {
			$subcat = 2;
		} else if (stristr($name, 'samsung')) {
			$subcat = 3;
		} else if (stristr($name, 'lg')) {
			$subcat = 4;
		} else if (stristr($name, 'sony')) {
			$subcat = 5;
		} else if (stristr($name, 'htc')) {
			$subcat = 6;
		} else if (stristr($name, 'motorola')) {
			$subcat = 7;
		} else {
			$subcat = 8;
		}
	} else if ($category == "camera" || $category == "professional_camera") {
		if(stristr($name, 'nikon')) { 
			$subcat = 11;
		} else if (stristr($name, 'sony')) {
			$subcat = 12;
		} else if (stristr($name, 'samsung')) {
			$subcat = 13;
		} else if (stristr($name, 'canon')) {
			$subcat = 14;
		} else if (stristr($name, 'kodak')) {
			$subcat = 15;
		} else {
			$subcat = 16;
		}
	} else if ($category == "tv" || $category == "tv_box" || $category == "tv_led" || $category == "tv_lcd" || $category == "tv_plasma" || $category == "tv_crt") {
		if(stristr($name, 'sony')) { 
			$subcat = 17;
		} else if (stristr($name, 'samsung')) {
			$subcat = 18;
		} else if (stristr($name, 'panasonic')) {
			$subcat = 19;
		} else if (stristr($name, 'lg')) {
			$subcat = 20;
		} else if (stristr($name, 'toshiba')) {
			$subcat = 21;
		} else if (stristr($name, 'sharp')) {
			$subcat = 22;
		} else {
			$subcat = 23;
		}
	//} else if ($category == "female_high_heel_shoes" || $category == "female_ballet_shoes" || $category == "female_sandals" || $category == "female_shoes") {
	} else if (strpos($category, "female") !== false && strpos($category, "shoe") !== false) {
		$subcat = 33;
	//} else if ($category == "female_dress") {
	} else if (strpos($category, "female") !== false && (strpos($category, "dress") !== false || strpos($category, "skirt") !== false)) {
		$subcat = 32;
	//} else if ($category == "female_shirt") {
	} else if (strpos($category, "female") !== false && strpos($category, "shirt") !== false) {
		$subcat = 30;
	//} else if ($category == "female_pants") {
	} else if (strpos($category, "female") !== false && (strpos($category, "pant") !== false || strpos($category, "jean") !== false)) {
		$subcat = 31;
	} else if ($category == "female_accessories" || $category == "female_belt" || $category == "female_bag" || $category == "female_jewelry" || $category == "female_purse") {
		$subcat = 34;
	} else if ($category == "female_bra" || $category == "female_underwear" || $category == "female_swimsuit" || $category == "female_special_underwear" || $category == "female_body_underwear" || $category == "female_panties" || $category == "female_sleepwear") {
		$subcat = 35;
	//} else if ($category == "male_casual_shoes" || $category == "male_slip_on_shoes" || $category == "male_slip_on_shoes") {
	} else if (strpos($category, "male") !== false && strpos($category, "emale") == false && strpos($category, "shoe") !== false) {
		$subcat = 38;
	//} else if ($category == "male_shirt" || $category == "male_tshirt") {
	} else if (strpos($category, "male") !== false && strpos($category, "emale") == false && (strpos($category, "shirt") !== false || strpos($category, "polo") !== false)) {
		$subcat = 36;
	//} else if ($category == "male_jeans") {
	} else if (strpos($category, "male") !== false && strpos($category, "emale") == false && (strpos($category, "pant") !== false || strpos($category, "jean") !== false || strpos($category, "short") !== false)) {
		$subcat = 37;
	} else if (strpos($category, "male_accessories") !== false) {
		$subcat = 39;
	} else if ($category == "male_bra" || $category == "male_underwear" || $category == "male_swimsuit" || $category == "male_special_underwear" || $category == "male_body_underwear" || $category == "male_panties" || $category == "male_sleepwear") {
		$subcat = 40;
	} 
	return $subcat;
}

?>