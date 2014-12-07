<?php

function replace_to_correct_name_model($name) {
	if ($name == "iii") $name = "3";
	if ($name == "ii") $name = "2";
	return $name;
}

// function check_product_model($keyword_name, $model_list, $brand)
// {
// 	/* One word model */
// 	for ($i = 0; $i < sizeof($keyword_name); $i++) {
// 		$keyword_name[$i] = replace_to_correct_name_model($keyword_name[$i]);
// 		if ($brand == "apple") {
// 			if (in_array($keyword_name[$i], $model_list)) {
// 				return $keyword_name[$i];
// 			}
// 		} else {
// 			for ($j = 0; $j < sizeof($model_list); $j++) {
// 				if (strpos($keyword_name[$i], $model_list[$j]) !== false) {
// 					return $model_list[$j];
// 				}
// 			}
// 		}
// 	}

// 	/* Two word model */
// 	for ($i = 0; $i < sizeof($keyword_name)-1; $i++) {
// 		$keyword = $keyword_name[$i]." ".$keyword_name[$i+1];
// 		if (in_array($keyword, $model_list)) {
// 			return $keyword;
// 		}
// 	}

// 	/* Three word model */ 
// 	for ($i = 0; $i < sizeof($keyword_name)-2; $i++) {
// 		$keyword = $keyword_name[$i]." ".$keyword_name[$i+1]." ".$keyword_name[$i+2];
// 		if (in_array($keyword, $model_list)) {
// 			return $keyword;
// 		}
// 	}
// 	return "";
// }

function check_product_model($name, $model_list, $brand)
{
	$longest_match_model = "";
	$longest_match_model_id = "";

	for ($i = 0; $i < sizeof($model_list); ++$i)
	{
		$model = $model_list[$i];
		$model_name = $model["model"];
		$model_name = explode("/", $model_name);

		// Because model name can be different but mean the same model
		// For ex: Galaxy Note II and Galaxy Note 2.
		// So we use "/" in the model name to specify different name for the 
		// same model
		for ($k = 0; $k < sizeof($model_name); ++$k)
		{
			// Create keyword that specify a model
			$model_name[$k] = trim($model_name[$k]);
			$model_name_keyword = explode(";", $model_name[$k]);

			$check = true;
			for ($j = 0; $j < sizeof($model_name_keyword); ++$j)
			{
				if (stripos($name, $model_name_keyword[$j]) === false)
				{
					$check = false;
					break;
				}
			}

			if ($check && strlen($model_name[$k]) > strlen($longest_match_model))
			{
				$longest_match_model_id = $model["id"];
				$longest_match_model = $model_name[$k];
			}
		}
	}

	return $longest_match_model_id;
}

function model_word($cat, $subcat, $brand) {
	$model = array();
	/* Mobiles */
	if ($brand == "samsung") {
		$model = array("i9082", "1175", "i9152", "s7250", "6200", "s6102", "9300", "10 1", "2 7 0", "40d503", "43e490", "46es7100", "50es5600", "55es7100", "9070", "c3262", "c3303", "c3053", "c3312", "c3520", "e1050", "e1200", "e1232", "e2202", "e2222", "e2550", "f4500", "f4900", "f6400", "f6800", "f7500", "f8000", "gc100", "i8160", "i8190", "i8262", "i8530", "i8552", "i9200", "i9500", "i9502", "la32e420", "1671", "5220", "8150", "2161", "2164", "2580", "2950", "3310", "n7000", "n7100", "nc108 p03vn", "nc108 p04vn", "note 10 1", "note 8 0", "np300e4z a08vn", "np300e4z a09vn", "np300e4z s06vn", "np300v4z a03vn", "np300v4z a04vn", "np350u2y a04vn", "np350u2y a05vn", "p3100", "p3110", "p5100", "p5200", "ps43e470", "c3312r", "s 2", "s 3", "s5300", "s5312", "s5360", "s5570", "s5830", "s6102", "s6310", "s6500", "s6802", "s6810", "s7560", "s7562", "3401", "3406", "4727", "t211", "t311", "ua22es5000", "ua26eh4000", "ua32eh4000", "ua32eh4003", "ua32eh5300", "ua32es5600", "ua32es6220", "ua32f4000", "ua32f4500", "ua32f5000", "ua32f5500", "ua39eh5003", "ua40eh5000", "ua40eh5300", "ua40es5600", "ua40es6220", "ua40es6800", "ua40f5000", "ua40f5100", "ua40f5500", "ua40f6300", "ua46eh5300", "ua46es6220", "ua46es6800", "ua46es8000", "ua46f5000", "ua46f5500", "ua50es6220", "ua50es6900", "ua55es8000", "ua55f6300", "ua65es8000");
	} else if ($brand == "nikon") {
		$model = array("s9100", "d3200", "d7000", "d600", "d800", "d800e", "d3000", "d5100", "d3100", "d90", "d5200", "d7100", "j1", "1 v1", "l28", "s01", "s3500", "p310", "s3300", "p330", "aw110", "s9400", "s6500", "l810", "l26", "l610", "p7700", "s800c", "s9200", "s4300", "p510", "p7100", "l25", "s5200", "l320", "l27", "s30", "s9500", "l310", "l820", "p520");
	} else if ($brand == "fujifilm") { 

	} else if ($brand == "sony" || $brand == "sony ericsson") { 
		$model = array("24ex430", "32bx35a", "32cx520", "32ex310", "32ex330", "32ex650", "32hx750", "32nx650", "32r402a", "32w674a", "40bx420", "40bx450", "40ex430", "40ex650", "40hx750", "40hx855", "40nx650", "40r452a", "42ex410", "42w674a", "42w804a", "46bx450", "46ex650", "46hx750", "46hx855", "46w704a", "46w904a", "46w954a", "47w804a", "50w704a", "55hx750", "55hx855", "55hx955", "55w804a", "55w904a", "55w954a", "55x9004a", "60r550a", "65hx955", "65x9004a", "70r550a", "a37k", "a37m", "a57k", "a58k", "a65v", "a77", "alpha nex 3nl", "c1505", "c1605", "c2105 đen", "c2105 trắng đỏ", "c5302", "dw120", "dw125", "dx120", "dx125", "dx140", "dx145", "h200", "h90", "he20hf", "hx10v", "hx200v", "hx300", "hx50v", "lt26ii", "lt26w", "lt28h", "mk16i", "mt11i", "mt25i", "nex 5nk", "nex 5ry", "nex 6l", "nex 7", "nex f3k", "nex f3y", "nex rl", "ns638p", "ns648p", "ns758hp", "rx100", "s5000", "sgp321", "sk17i", "st15i", "st21i", "st21i2", "st23i", "st26i", "st27i", "svd11215cv", "sve11125cv", "sve11135cv", "sve14122cv", "sve14126cv", "sve14131cv", "sve14132cv", "sve14136cv", "sve14a15fgb", "sve14a15fgp", "sve14a15fgw", "sve14a16fgh", "sve14a16fgs", "sve14a25cvb", "sve14a25cvp", "sve14a25cvw", "sve14a26cv", "sve14a35cv", "sve14a37cv", "sve15117fg", "sve15123cv", "sve15126cv", "sve15127cv", "sve15133cv", "sve15136cv", "sve15138cv", "svs13112eg", "svs13117gg", "svs13117ggb", "svs13117ggs", "svs13123cv", "svs13126pg", "svs13132cv", "svs13136pg", "svs13a15gg", "svs13a25pg", "svs15115fg", "svs15116ggb", "svs15125cv", "svt11113fgs", "svt13125cv", "svt13126cv", "svt13137cv", "svt14115cv", "svt14126cv", "ck15i", "vpcca35fg", "vpcsb35fg", "w100i", "w205", "w610", "w630", "w670", "w690", "w710", "w730", "wt13i", "wt19i", "wx100", "wx200", "wx300", "wx50", "wx80", "xperia p", "xperia s", "xperia sola", "xperia tx", "xperia u", "xperia v", "xperia x8", "xperia z", "xperia zr", "zylo w20");
	} else if ($brand == "canon") { 
		 $model = array("lbp2900", "lbp3300", "100d", "1100 hs", "1100d", "115 hs", "125 hs", "132", "135", "140", "220 hs", "230 hs", "255 hs", "303", "308", "310 hs", "316 bk", "316 c", "316 m", "316 y", "319", "325", "326", "328", "500 hs", "550d", "5600f", "5d mark 2", "5d mark 3", "600d", "60d", "650d", "6d", "700d", "7d", "9000 mkii", "9000f", "a2200", "a2300", "a2400 is", "a2500", "a2600", "a3200 is", "a3300 is", "a3400", "a4000 is", "a810", "cl 41", "cl 741", "cl 811", "cl 98", "cli 36 c", "cli 726 y", "cli 726bk", "cli 726c", "cli 726m", "cli 726y", "cli 8 bk", "cli 8 c", "cli 8 m", "cli 8 y", "cli 821 bk", "cli 821 c", "cli 821 m", "cli 821 y", "cli 8bk", "cli 8c", "cli 8g", "cli 8m", "cli 8pc", "cli 8pm", "cli 8r", "cli 8y", "d520", "e500", "e510", "e600", "eos m", "g1 x", "g12", "g15", "ip100", "ip2770", "ip3680", "ip4970", "ix 6560", "lbp3500", "lbp5050", "lbp5050n", "lbp6000", "lbp6200d", "lbp6650dn", "lbp7018c", "lbp7200cdn", "lide 110", "lide 210", "lide 700f", "mf3010ae", "mf4750", "mf4870dn", "mf5980dw", "mf8080cw", "mf8380cdw", "mg 6270", "mg2170", "mg2270", "mg3170", "mg4170", "mg4270", "mg5370", "mp237", "mp287", "mx366", "mx377", "mx416", "mx437", "mx517", "pg 740", "pg 810", "pg 88", "pgi 35 bk", "pgi 725bk", "pgi 820 bk", "s100", "s110", "sx150 is", "sx160 is", "sx230 hs", "sx240 hs", "sx260 hs", "sx270 hs", "sx280 hs", "sx40 hs", "sx50 hs", "sx500");
	} else if ($brand == "pentax") { 

	} else if ($brand == "anaconda") { 

	} else if ($brand == "acer") { 

	} else if ($brand == "asus") { 

	} else if ($brand == "apple") {
		$model = array("macbook pro", "macbook air", "iphone 4s", "iphone 4", "iphone 5", "iphone 3gs", "iphone 3", "iphone 2", "new ipad", "ipad mini", "ipad 4", "ipad 2", "ipad 3");
	} else if ($brand == "axioo") { 

	} else if ($brand == "hp") { 

	} else if ($brand == "lenovo") { 

	} else if ($brand == "toshiba") { 
		$model = array("19hv10v", "23pb200", "23pu200v", "24hv10", "24pb2v", "24ps10", "29pb200", "32hv10v", "32pb200", "32pb20v", "32pb2v", "32ps10v", "32ps200v", "32pu200v", "32px200v", "40al10v", "40pb200v", "40pb20v", "40ps200v", "40pu200v", "40px200v", "46px200v", "at305t16", "at305t32", "c640 1059u", "c665 1003u", "c800 1008", "c800 1016", "c800 1020", "c840 1003b", "c840 1003r", "c840 1012x", "l740 1222u", "l840 1029", "l840 1029r", "l840 1029w", "l840 1030", "l840 1030xr", "l840 1030xw", "l840 1031x", "l840 1031xr", "l840 1031xw", "l840 1032x", "l840 1032xr", "l840 1032xw", "l850 1011x", "m840 1011g", "m840 1011p", "m840 1020g", "m840 1020p", "m840 1020q", "m840 1021g", "m840 1021p", "m840 1021q", "psc6cl 01n002", "psk8jl 00w004", "psk8jl 00x004", "psk8jl 00y004", "psk9sl 00j001", "psk9sl 011001", "regza 47rw1", "regza 55rw1", "z830 2005u", "z930 2002", "z930 2005");
	} else if ($brand == "fujitsu") { 

	} else if ($brand == "dell") { 

	} else if ($brand == "gateway") { 

	} else if ($brand == "ricoh") { 

	} else if ($brand == "panasonic") { 
		$model = array("24x5v", "32c30", "32em5v", "32xm5v", "39em5v", "42 e5v", "42x30v", "50st50v", "55vt50v", "65st50v", "ae7000ea", "ae8000ea", "d6000ek", "dw6300ek", "fl 422", "fl 612", "fmb1500", "fmb1520", "fmb1530", "l32c4v", "l32c5v", "l32e3v", "l32e5v", "l42et5v", "l47e5v", "lb1vea", "lb2vea", "lb3ea", "lb90ea", "lb90ntea", "lx22ea", "lx26ea", "lx30hea", "1900", "2010", "2025", "2030", "mb772", "p50x50v", "32b6v", "32xm6v", "39b6v", "42e6v", "50x60", "l24xm6", "l39em6v", "l39ev6v", "l50b60v", "l50em6v", "l50et60v", "l55et60v", "l32u5v", "vx400ntea", "vx41ea");
	} else if ($brand == "brother") { 

	} else if ($brand == "oki") { 

	} else if ($brand == "epson") { 

	} else if ($brand == "fuji xerox") { 

	} else if ($brand == "yamaha") { 

	} else if ($brand == "tech mate") { 

	} else if ($brand == "tcl") { 

	} else if ($brand == "arirang") { 

	} else if ($brand == "boston") { 

	} else if ($brand == "sharp") { 
		$model = array("32le243", "32le240m", "60le835", "60le940", "60le830", "60le630", "52le840", "52le835", "32le340m", "32m300m", "22dc30m", "40le430", "24dc50m", "40m500m", "sh631w", "sh530u", "sh930w", "sh837w");
	} else if ($brand == "mpins") { 

	} else if ($brand == "lg") { 
		$model = array("24ln4110", "26ln4110", "26ls3300", "28ln4110", "32cs410", "32cs460", "32la613b", "32ln4900", "32ln5110", "32ln5120", "32ln541b", "32ln571b", "32ls3300", "32ls3500", "39ln5120", "39ln5400", "42cs460", "42la6200", "42la6620", "42la6910", "42lm6200", "42lm6410", "42lm7600", "42ln5110", "42ln5120", "42ln5400", "42ls4600", "42pm4700", "47la6200", "47la6910", "47lm6200", "47ln5400", "47ln5710", "47ls4600", "50la6200", "50ln5400", "50pm4700", "50pn4500", "55la6200", "55la6910", "55la8600", "55lm7600", "55lm9600", "55ln5710", "60la8600", "60ln5400", "72lm9500", "a290", "c375", "c660", "e400", "e405", "e425", "e435", "e440", "e450", "e510", "e612", "e615", "e730", "e960", "e975", "gm360i", "gs290", "gt350i", "p350", "p698", "p705", "p713", "p715", "p725", "p768", "p880", "p895", "p920", "p970", "s365", "t300", "t375", "t500", "t515");
	} else if ($brand == "de nhat") { 

	} else if ($brand == "denon") { 

	} else if ($brand == "gunners") { 

	} else if ($brand == "monster") { 

	} else if ($brand == "philips") { 

	} else if ($brand == "pioneer") { 

	} else if ($brand == "rapoo") { 

	} else if ($brand == "beehd") { 

	} else if ($brand == "akai") { 

	} else if ($brand == "vson") { 

	} else if ($brand == "vtb") { 

	} else if ($brand == "geniatech") { 

	} else if ($brand == "tvzoneplus") { 

	} else if ($brand == "nokia") {
		$model = array("100", "101", "105", "109", "110", "112", "301", "500", "603", "700", "701", "808", "1280", "1616", "2730", "5130", "5233", "7230", "asha 200", "asha 202", "asha 205", "asha 205", "asha 206", "asha 300", "asha 302", "asha 303", "asha 305", "asha 306", "asha 308", "asha 309", "asha 310", "asha 311", "asha 501", "c1 01", "c2 00", "c2 01", "c2 03", "c3 00", "c3 01", "c5 00", "c5 03", "c5 06", "c6 00", "e5 00", "e6 00", "e63", "e66", "e7 00", "e75", "lumia 520", "lumia 610", "lumia 620", "lumia 710", "lumia 720", "lumia 800", "lumia 820", "lumia 900", "lumia 920", "n500", "n8 00", "n9", "n9 00", "n97", "nokia 105", "x1 01", "x2 00", "x2 01", "x2 02", "x3 00", "x3 02", "x7 00");
	} else if ($brand == "huawei") { 

	} else if ($brand == "q mobile") { 

	} else if ($brand == "q smart") { 

	} else if ($brand == "avio") { 

	} else if ($brand == "blackberry") { 

	} else if ($brand == "mobiistar") { 

	} else if ($brand == "oppo") { 

	} else if ($brand == "connspeed") { 

	} else if ($brand == "fpt") { 

	} else if ($brand == "htc") { 
		$model = array("one x", "sensation xe", "sensation xe", "sensation", "one v", "desire x", "desire hd", "one s", "explorer", "chacha", "incredible s", "one x", "wildfire s", "8s", "desire c", "desire 600", "one", "desire sv", "desire sv", "8x", "desire v", "evo 3d", "rhyme", "desire 200", "desire u");
	} else if ($brand == "otic") { 

	} else if ($brand == "malata") { 

	} else if ($brand == "motorola") { 
		$model = array("xt910", "xt321", "ex226");
	} else if ($brand == "mobell") { 

	} else if ($brand == "gionee") { 

	} else if ($brand == "kingcom") { 

	} else if ($brand == "hanel") { 

	} else if ($brand == "alcatel") { 

	} else if ($brand == "aoson") { 

	} else if ($brand == "pop") { 

	} else if ($brand == "haipad") { 

	} else if ($brand == "opad") { 

	} else if ($brand == "coolpad") { 

	} else if ($brand == "coby") { 

	} else if ($brand == "kindle") { 

	} else if ($brand == "jsmax") { 

	} else if ($brand == "pipo") { 

	} else if ($brand == "dopad") { 

	} else if ($brand == "cutepad") { 

	} else if ($brand == "knc") { 

	} else if ($brand == "pi") { 

	} else if ($brand == "popcom") { 

	} else if ($brand == "viewsonic") { 

	} else if ($brand == "apad") { 

	} else if ($brand == "viewpad") { 

	} else if ($brand == "daza") { 

	} else if ($brand == "bipad") { 

	} else if ($brand == "archos") { 

	} else if ($brand == "ainol") { 

	} else if ($brand == "arnova") { 

	} else if ($brand == "google") { 

	} else if ($brand == "elead") { 

	} else if ($brand == "singpc") { 

	} else if ($brand == "cms") { 

	} else if ($brand == "kodak") { 

	} else if ($brand == "klipsch") { 

	} else if ($brand == "jamo") { 

	} else if ($brand == "microtek") { 

	} else if ($brand == "sonicgear") { 

	} else if ($brand == "microlab") { 

	} else if ($brand == "logitech") { 

	} else if ($brand == "soundmax") { 

	} else if ($brand == "genius") {

	}

	return $model;
}

?>