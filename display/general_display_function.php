<?php
try {
	include "website_list.php";
	include "category_list.php";
	include "link_creation.php";
	include "similarity.php";
	include "config_language.php";
	//check if DB Connection included with child class inside DBConnection.php
	if(class_exists('DBCustomer') != true)
	{
	   include  "./CMS/include/DBConnection.php";	
	}	
} 
catch (Exception $e)
{
	echo "Some dependency error general_display_function.php";
}

/* Function gets the average rating of a product */
function get_avg_rating($id, $website, $db) {
	$avgrating = 4;

	$result = $db->get_avg_rating($website, $id);
	if ($result && sizeof($result) == 1) {
		if ($result[0] > 0) {
			$avgrating = round($result[0], 0, PHP_ROUND_HALF_UP);
		}
	}
	return $avgrating;
}

/* Function that remove special characters from words like " */
function remove_special_characters ($word)
{
	$word = str_replace("\"", "", $word);
	return $word;
}

/* Function display small or big star rating given the number of stars */
function display_star($number, $small) {
	for ($i = 1; $i <= 5; $i++) {
		if ($i == $number) {
			echo "<li>\n";
			$class = array("one", "two", "three", "four", "five");
			if ($small == 0) {
				echo "<a class=\"".($class[$number-1])."-stars\" style=\"background-image:url('/img/star_rating.png'); background-position: left bottom; left: 0px; width:".($number*26)."px\">".$number."</a>\n";
			} else {
				echo "<a class=\"".($class[$number-1])."-stars\" style=\"background-image:url('/img/star_rating_small.png'); background-position: left bottom; left: 0px; width:".($number*20)."px\">".$number."</a>\n";
			}
			echo "</li>\n";
		} else {
			echo "<li></li>\n";
		}
	}
}

/* Function remove non-ascii word at the beginning of the product name */
function remove_non_ascii_beginning ($text) {
	$text = trim($text);
	$words = explode(" ", $text);
	$newname = "";
	$check = false;
	for ($i = 0; $i < sizeof($words); $i++) {
		if ($check == false && mb_detect_encoding($words[$i], 'ASCII', true) && strcasecmp (trim($words[$i]), "TAY") != 0) {
			$check = true;
			$newname .= $words[$i]." ";
		} else if ($check == true) {
			$newname .= $words[$i]." ";
		}
	}
	if (strlen($newname) > 8) {
		return trim($newname);
	} else {
		return $text;
	}
}

/* Function display suggested product list */
function display_suggested_product($suggested, $id, $website, $position, $curlang, $l) {
	$count = 0;
	$numsigfig = $l[$curlang]["num_sigfig"];
	$decsep = $l[$curlang]["dec_separator"];
	$thoussep = $l[$curlang]["thousand_separator"];

	/* Traverse through the array of suggested product */
	for ($i = 0; $i < sizeof($suggested); $i++) {
		/* Fetch each product out */
		$row = $suggested[$i];
		/* We don't suggest the product to itself */
		if ($row["id"] != $id || $row["website"] != $website) {
			/* Check for 404 image link */
			/*if (!valid_image(".".$row["newimage1"])) {
				continue;
			}*/

			$count++;

			/* Create the right name link */
			$namelink = create_product_link($row["name"], $row["website"], $row["id"]);

			/* Difference between suggested in search and suggested in detail page */
			if ($position == 1) {
				echo "<div class=\"itemSuggestedSearch\">\n";
			} else {
				echo "<div class=\"items_detail\">\n";
			}

			echo "<div class=\"new\"></div>\n";
			/* Image of the suggested product */
			echo "<div><a href=\"/".$namelink."\"><img src=\"".$row["newimage1"]."\" width = \"157\" height = \"150\"alt=\"".$row["name"]."\" /></a></div>\n";
			/* Price of the suggested product */
			echo "<div class=\"priceItems\" style=\"width:160px;\"> ".$l[$curlang]["currency"]." ".number_format($row["price"], $numsigfig, $decsep, $thoussep)."</div>\n";
			/* Link to the suggested product */

			echo "<div class=\"btnMoreDetails\" style=\"width:148px;\"><a href=\"/".$namelink."\">".$l[$curlang]["more_detail"]."</a></div>\n";

			echo "</div>\n";
			echo "<!--close items-->\n";

			/* Only display 5 suggested products */
			if ($count != 5) {
				echo "<div class=\"lineGreyItemsDetail\"></div>\n";
			} else break;
		}
	}
}

/* Function construct product detail link from name, website, id of the product */
function create_product_link($name, $website, $id, $cat = "", $subcat= "") {

	// If it is a model, not a specific product, only use the first name 
	// to create the URL. For ex: Galaxy Note 2 and Galaxy Note II. Only 
	// use the first one, which is Galaxy Note 2 to create the link
	if (empty($website) || $website == "modelproduct")
	{
		$name = explode("/", $name);
		$name = $name[0];
	}

	$namelink = trim($name);
	$namelink = str_replace(" ", "-", $namelink);
	$namelink .= "-".translate_website_to_code($website)."-".$id;
	$namelink = remove_sign($namelink);

	// If website is empty or modelproduct, it is a model not a specific product
	if (empty($website) || $website == "modelproduct")
	{
		$namelink = get_catname_from_code($cat)."/".$namelink;
		$namelink .= "-model";
	}

	$namelink .= ".html";
	return $namelink;
}

/* Function return an array of words in a names */
function create_keyword_name($name) {
	$keyword_name = strtoupper($name);
	$keyword_name = str_replace("/", " ", $keyword_name);
	$keyword_name = str_replace("-", " ", $keyword_name);
	$keyword_name = str_replace("(", " ", $keyword_name);
	$keyword_name = str_replace(")", " ", $keyword_name);
	$keyword_name = str_replace("'", " ", $keyword_name);
	$keyword_name = str_replace(".", " ", $keyword_name);
	$keyword_name = str_replace("   ", " ", $keyword_name);
	$keyword_name = str_replace("  ", " ", $keyword_name);
	$keyword_name = explode(" ", $keyword_name);
	$words = array();
	for ($i=0; $i<sizeof($keyword_name); $i++) {
		if (mb_detect_encoding($keyword_name[$i], 'ASCII', true)) {
			$words[] = strtolower($keyword_name[$i]);
		}
	}
	return $words;
}

function strip_tags_custom($description) {
	$allowed_tags = 
	'<br><b><h1><h2><h3><h4><i><li><ol><strong>' .
	'<th><u><ul><em>';
	$description = strip_tags($description, $allowed_tags);
	$disallowed = 
	'javascript:|window|eval|setTimeout|setInterval|target|'.
	'onAbort|onBlur|onChange|onClick|onDblClick|'.
	'onDragDrop|onError|onFocus|onKeyDown|onKeyPress|'.
	'onKeyUp|onLoad|onMouseDown|onMouseMove|onMouseOut|'.
	'onMouseOver|onMouseUp|onMove|onReset|onResize|'.
	'onSelect|onSubmit|onUnload|style';
	$description = preg_replace("/$disallowed/i", " ", $description);
	return $description;
}

function sort_price_difference($similarproduct) {
	for ($i = 0; $i < sizeof($similarproduct); $i++) {
		$result1 = new $similarproduct[$i];
		for ($j = $i+1; $j < sizeof($similarproduct)-1; $j++) {
			$result2 = $similarproduct[$j];
			if ($result1["difference"] > $result2["difference"]) {
				$similarproduct[$i] = $result2;
				$similarproduct[$j] = $result1;
			}
		}
	}

	return $similarproduct;
}

/* Remove bad accent, signs of string, mainly used for url */
function remove_sign($str)
{
	//$str = utf8_decode($str);
	$coDau=array("à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
		"ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề"
		,"ế","ệ","ể","ễ",
		"ì","í","ị","ỉ","ĩ",
		"ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ"
		,"ờ","ớ","ợ","ở","ỡ",
		"ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
		"ỳ","ý","ỵ","ỷ","ỹ",
		"đ",
		"À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă"
		,"Ằ","Ắ","Ặ","Ẳ","Ẵ",
		"È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
		"Ì","Í","Ị","Ỉ","Ĩ",
		"Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ"
		,"Ờ","Ớ","Ợ","Ở","Ỡ",
		"Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
		"Ỳ","Ý","Ỵ","Ỷ","Ỹ",
		"Đ", " ");

	$khongDau=array("a","a","a","a","a","a","a","a","a","a","a"
		,"a","a","a","a","a","a",
		"e","e","e","e","e","e","e","e","e","e","e",
		"i","i","i","i","i",
		"o","o","o","o","o","o","o","o","o","o","o","o"
		,"o","o","o","o","o",
		"u","u","u","u","u","u","u","u","u","u","u",
		"y","y","y","y","y",
		"d",
		"A","A","A","A","A","A","A","A","A","A","A","A"
		,"A","A","A","A","A",
		"E","E","E","E","E","E","E","E","E","E","E",
		"I","I","I","I","I",
		"O","O","O","O","O","O","O","O","O","O","O","O"
		,"O","O","O","O","O",
		"U","U","U","U","U","U","U","U","U","U","U",
		"Y","Y","Y","Y","Y",
		"D", "-");

	$str = str_replace($coDau,$khongDau,$str);
	$str = preg_replace('/[^(\x20-\x7F)]*/','', $str);
	// Strip HTML Tags
	$str = strip_tags($str);
	// Clean up things like &amp;
	$str = html_entity_decode($str);
	// Strip out any url-encoded stuff
	$str = urldecode($str);
	$str = str_replace("(", "", $str);
	$str = str_replace(")", "", $str);
	$str = str_replace("[", "", $str);
	$str = str_replace("]", "", $str);
	$str = str_replace("/", "-", $str);
	$str = str_replace(".", "-", $str);
	$str = str_replace(":", "-", $str);
	$str = str_replace("\"", "-", $str);
	$str = str_replace("'", "-", $str);
	$str = str_replace("+", "-", $str);
	$str = str_replace("!", "", $str);
	$str = str_replace(",", "-", $str);
	$str = str_replace(";", "-", $str);
	$str = str_replace("&", "", $str);
	$str = str_replace("*", "-", $str);
	$str = str_replace("|", "-", $str);
	$str = str_replace(" ", "", $str);
	$str = str_replace("_", "-", $str);
	$str = str_replace("---", "-", $str);
	$str = str_replace("--", "-", $str);
	$str = utf8_decode($str);
	$str = str_replace("?", "", $str);
	return $str;
}

/* Function truncate a string to only 'length' length*/
function truncate_string($string, $length) {
	if ($length > 0 && strlen($string) > $length) {
        $string = substr($string, 0, $length);
        $string = substr($string, 0, strrpos($string, " "));
        $string .= " ...";
    }

    return $string;
}

/* Helper function to shorten the description of the product to fit the given space in the detail page */
function shorten($long) {
	$short = trim($long);

	$length = strlen($short);
	if ($length > 500) {
		$length = 500;
	}

	$newlength = 0;
	$addlength = 0;
	$arrayshort = explode("br", $short);
	$newshort = "";

	for ($i = 1; $i <= 9; $i++) {
		if (sizeof($arrayshort) >= $i) {
			if ($i > 1) {
				$newshort .= "br".$arrayshort[$i-1];
			} else {
				$newshort = $arrayshort[$i-1];
			}
			$newlength += strlen($arrayshort[$i-1])+70;
			$addlength += 70;
		}
	}

	if ($length > $newlength) {
		$length = $newlength - $addlength; 
		$short = substr($long, 0, $length);
	} else {
		$short = substr($newshort, 0, $length);
	}

	$short = substr($short, 0, strrpos($short, " "));

	if (strlen($long) > strlen($short)) {
		$short .= "...";
	}
	return $short;
}

/* Helper function to shorten the description of the product to fit the given space in the detail page */
function shorten_for_search($long) {
	$short = trim($long);
	$short = substr($short, 0 , 200);
	$numberofnewline = substr_count($short, "br");
	if ($numberofnewline <= 3) {
		$short = substr($short, 0 , 200-$numberofnewline*50);
	} else {
		$short = substr($short, 0 , strpos($short, "br"));
	}
	$lastempty = strrpos($short, " ");
	if ($lastempty > 0) {
		$short = substr($short, 0, $lastempty);
	}
	if (strlen($long) > 200-$numberofnewline*50) {
		$short .= "...";
	}
	return $short."<br/>";
}

/* Helper function to check if a image file is valid or not */
function valid_image($image) {
	/* If file doesn't exists or size is too small, return fasle */
	if (stripos($image, "http") === false && (!file_exists($image) || filesize($image) < 3*1024)) {
		return false;
	} else {
		return true;
	}
}

function format_time_review($time)
{
	$time = explode(" ", $time);
	$date = $time[0];
	$hour = $time[1];

	// Format date
	$date = format_date_review($date);
	$hour = format_hour_review($hour);

	return $hour." On ".$date;
}

function format_date_review($date)
{
	$date = explode("/", $date);
	$day = $date[0];
	$month = convert_month($date[1]);
	$year = "20".$date[2];

	return $month." ".$day." ".$year;
}

function format_hour_review($hour)
{
	return $hour;
}

function convert_month($month)
{
	switch ($month) {
		case 1:
			return "January";
		case 2:
			return "February";
		case 3:
			return "March";
		case 4:
			return "April";
		case 5:
			return "May";
		case 6:
			return "June";
		case 7:
			return "July";
		case 8:
			return "August";
		case 9:
			return "September";
		case 10:
			return "October";
		case 11:
			return "November";
		case 12:
			return "December";
		default:
			return "";
	}
}
?>