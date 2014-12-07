<?php
function website_list() {
	$website_list = array();
	$website_list[] = "lazada";
	$website_list[] = "zalora";
	$website_list[] = "expansys";
	$website_list[] = "wiredsystems";
	$website_list[] = "weemall";
	$website_list[] = "gadgetgrocery";
	$website_list[] = "widgetcity";
	$website_list[] = "greatgadgetshop";
	$website_list[] = "villman";
	$website_list[] = "buyregalo";
	$website_list[] = "asianic";
	$website_list[] = "compex";
	$website_list[] = "enigma";
	return $website_list;
}

function website_vietnamese_name()
{
	$website_vnese_name = array();

	$website_vnese_name["lazada"] = "Lazada";
	$website_vnese_name["zalora"] = "Zalora";
	$website_vnese_name["expansys"] = "Expansys";
	$website_vnese_name["wiredsystems"] = "Wired Systems";
	$website_vnese_name["weemall"] = "Weemall";
	$website_vnese_name["gadgetgrocery"] = "Gadget Grocery";
	$website_vnese_name["widgetcity"] = "Widget City";
	$website_vnese_name["greatgadgetshop"] = "Great Gadget Shop";
	$website_vnese_name["villman"] = "Villman";
	$website_vnese_name["buyregalo"] = "BuyRegalo";
	$website_vnese_name["asianic"] = "Asianic";
	$website_vnese_name["compex"] = "Compex";
	$website_vnese_name["enigma"] = "Enigma";

	return $website_vnese_name;
}

function website_delivery_info()
{
	$website_delivery = array();

    $website_delivery["lazada"] = "• Free shipping over Php 1,000<br/>• Withink Manila: 5 working days<br/>• Outside Manila: 5-10 working days";
	$website_delivery["zalora"] = "";
	$website_delivery["expansys"] = "• Standard shipping 8-13 days<br/>• FedEx shipping 2-3 days<br/>• Shipping prices according to weight";
	$website_delivery["wiredsystems"] = "• Check payment possible";
	$website_delivery["weemall"] = "• Free Shipping for Metro Manila<br/>• Nationwide shipping via Air21<br/>";
	$website_delivery["gadgetgrocery"] = "• Shipping cost: Free Shipping<br/>• 7 days replacement warranty<br/>• Delivery: 2-3 working days";
	$website_delivery["widgetcity"] = "• 1 year manufacturer Warranty<br/>• 1 year service Warranty";
	$website_delivery["greatgadgetshop"] = "";
	$website_delivery["villman"] = "• 1 year manufacturer warranty<br/>• Pickup at a Vilman store possible";
	$website_delivery["buyregalo"] = "• Within Manila: 2-3 business days<br/>• Outside Manila: 4-7 business days<br/>• International shipping possible";
	$website_delivery["asianic"] = "";
	$website_delivery["compex"] = "• Shipping cost: Free Shipping<br/>• Delivery within Metro Manila<br/>• Tracking order tool";
    $website_delivery["enigma"] = "";

    return $website_delivery;
}

function translate_website_vnese ($key) {
	$website_vnese_name = website_vietnamese_name();
	$key = strtolower($key);

	if (array_key_exists($key, $website_vnese_name))
	{
		echo $website_vnese_name[$key];
	}
}

function result_website() {
	$website_list = website_list();

	$result_website = array();

	for ($i = 0; $i < sizeof($website_list); ++$i)
	{
		$result_website[$website_list[$i]] = 0;
	}

	return $result_website;
}

function translate_website_from_code($website) {
	$website_list = website_list();

	if (!is_numeric($website) || $website < 1 || $website > sizeof($website_list)) return "";

	return $website_list[$website - 1];
}

function translate_website_to_code($website) {
	$website_list = website_list();

	for ($i = 0; $i < sizeof($website_list); ++$i)
	{
		if ($website_list[$i] == $website)
		{
			return $i+1;
		}
	}
}

function get_website_delivery_info($website) {
	$website_delivery_list = website_delivery_info();

	if (array_key_exists($website, $website_delivery_list))
	{
		return $website_delivery_list[$website];
	}

	return ""; 
}
?>