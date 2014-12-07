<?php
require_once('zalora_link.php');

/* Function that converts normal link to utm link */
function create_utm_link($link, $website, $id, $cat, $subcat, $name) {
	//echo "link=$link id=$id cat=$cat subcat=$subcat name=$name<br/>";

	if ($website == "lazada") {
		return create_utm_link_lazada($link, $website, $id, $cat, $subcat, $name);
	}

	if ($website == "zalora") {
		$zalora_link = zalora_link();

		foreach ($zalora_link as $key => $value)
		{
			if (stripos($name, $key) !== false)
			{
				return $value;
			}
		}

		return $link."?wt_af=ph.Affiliate.DirectPartnership.oizoinoi.ProductFeed.NA&utm_source=DirectPartnership&utm_medium=Affiliate&utm_campaign=oizoinoi_ProductFeed&utm_content=NA";
	}

	if (strpos($link,"?") != false) {
		return $link."&utm_source=oizoioi.vn&utm_medium=product_detail_page&utm_campaign=oizoioi.vn";
	} else {
		return "/redirect.php?loc=".$link;
	}
}

function create_utm_link_lazada($link, $website, $id, $cat, $subcat, $name) {

	/* Add the end part here */
	if (substr($link, -1) != "/") {
		$link .= "/#";
	}

	$divid = $id % 26;

	switch ($divid):
		case 0: //Mobiles
	    	$link .= "Q2476C51t4l4h26";
	        break;
	    case 1: //Mobiles
	    	$link .= "Q2477C61g051vy4";
	        break;
	    case 2: //Tablets
	        $link .= "Q2473Cz11n1606";
	        break;
	    case 3: //Computer
	        $link .= "Q2474C91wnh0584";
	        break;
	    case 4: //Camera
	        $link .= "Q2475Chw111610z";
	        break;
	    case 5: //TV
	        $link .= "Q1895Cui610516y";
	        break;
	    case 6: //Audio
	        $link .= "Q1893Ch314g4i20";
	        break;
	    case 7: //Accessories
	        $link .= "Q1892C910y5z87z";
	        break;
	    case 8: //Female Fashion
	        $link .= "Q1891Cmw91t0587";
	        break;
	    case 9: //Male Fashion
	        $link .= "Q1838Ci1116v02k";
	        break;
	    case 10: //Male Fashion
	        $link .= "Q1839Cm6105k21i";
	        break;
	    case 11: //Male Fashion
	        $link .= "Q2440Cwi4115g45";
	        break;
	    case 12: //Male Fashion
	        $link .= "Q2441C61j.052.0";
	        break;
	    case 13: //Male Fashion
	        $link .= "Q2442C1hv116n11";
	        break;
	    case 14: //Male Fashion
	        $link .= "Q2443C111mjg607";
	        break;
	    case 15: //Male Fashion
	        $link .= "Q2433C210ww26t4";
	        break;
	    case 16: //Male Fashion
	        $link .= "Q1917Ck61051ti9";
	        break;
	    case 17: //Male Fashion
	        $link .= "Q1846CGg5Fhk6";
	        break;
	    case 18: //Male Fashion
	        $link .= "Q1847Ct4115h39n";
	        break;
	    case 19: //Male Fashion
	        $link .= "Q1848Cz11160yi9";
	        break;
	    case 20: //Male Fashion
	        $link .= "Q1849CnQg435g8";
	        break;
	    case 21: //Male Fashion
	        $link .= "Q1851Cjw610519n";
	        break;
	    case 22: //Male Fashion
	        $link .= "Q1915CtG5vEz9";
	        break;
	    case 23: //Male Fashion
	        $link .= "Q1914Cl2102Chy";
	        break;
	    case 24: //Male Fashion
	        $link .= "Q1912Cu91058vg7";
	        break;
	    case 25: //Male Fashion
	        $link .= "Q1913C6m1x0m523";
	        break;
	    default:
	    	//Shouldnt reach here
	endswitch;

	return $link;
}

?>