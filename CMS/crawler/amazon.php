<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class amazon {
		var $hostname;
		var $username;
		var $password;
		var $db;
		var $DBSite;
		var $curl_flag;
		var $name;

		public function SetConfig($host, $user, $pass, $db)
		{
			$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->DBSite = new DBCrawler();
			$this->name = "amazon";
			$this->DBSite->SetConfig($host, $user, $pass, $db, $this->name);
			$this->curl_flag = true;
		}

		public function getComment($link)
		{
			$content 	= get_content($link, $this->curl_flag);
			$result 	= explode("rev-dpReviewsMostHelpful",$content);
			$name 		= array();
			//$email 		= array();
			$title 		= array();
			$date		= array();
			$content 	= array();
			$value 		= array();
			$concatcomment = "";
			//echo "<h4>Number of content:".(sizeof($result)-1)."</h4>";
			for ($i = 1; $i < sizeof($result)-1; $i++) {
				$name[] = substr($result[$i], strpos($result[$i], "class=\"noTextDecoration\">")+strlen("class=\"noTextDecoration\">"));
				$name[$i-1] = substr($name[$i-1], 0, strpos($name[$i-1], "<"));

				$value[] = substr($result[$i], strpos($result[$i], "title=\"")+strlen("title=\""));
				$value[$i-1] = substr($value[$i-1], 0, strpos($value[$i-1], "."));

				// There are two type of date marks
				$dateIndex = stripos($result[$i], "inlineblock txtsmall\">");
			 	$dateMark = "";
			 	if ($dateIndex !== false)
			 	{
			 		$dateMark = "inlineblock txtsmall\">";
				} 
				else 
				{
					$dateIndex = stripos($result[$i], "</span> on");
					if ($dateIndex !== false)
				 	{
				 		$dateMark = "</span> on";
					}
					else
					{
						return "";
					}
				}
				$date[] = substr($result[$i], strpos($result[$i], $dateMark)+strlen($dateMark));
				$date[$i-1] = substr($date[$i-1], 0, strpos($date[$i-1], "<"));

			 	// There are two type of title marks
			 	$titleIndex = stripos($result[$i], "a-size-base a-text-bold\">");
			 	$titleMark = "";
			 	if ($titleIndex !== false)
			 	{
			 		$titleMark = "a-size-base a-text-bold\">";
				} 
				else 
				{
					$titleIndex = stripos($result[$i], "<strong>");
					if ($titleIndex !== false)
				 	{
				 		$titleMark = "<strong>";
					}
					else
					{
						return "";
					}
				}
				$title[] = substr($result[$i], strpos($result[$i], $titleMark)+strlen($titleMark));
				$title[$i-1] = substr($title[$i-1], 0, strpos($title[$i-1], "<"));


				// There are two type of content marks
			 	$contentIndex = stripos($result[$i], "<div class=\"a-section\">");
			 	$contentMark = "";
			 	if ($contentIndex !== false)
			 	{
			 		$contentMark = "<div class=\"a-section\">";
				} 
				else 
				{
					$contentIndex = stripos($result[$i], "<div class=\"drkgry\">");
					if ($contentIndex !== false)
				 	{
				 		$contentMark = "<div class=\"drkgry\">";
					}
					else
					{
						return "";
					}
				}
				$content[] = substr($result[$i], strpos($result[$i], $contentMark)+strlen($contentMark));
				$content[$i-1] = substr($content[$i-1], 0, strpos($content[$i-1], "</div>"));

				echo "name = ".$name[$i-1]."<br/>";
				echo "date = ".$date[$i-1]."<br/>";
				echo "title = ".$title[$i-1]."<br/>";
				echo "content = ".$content[$i-1]."<br/>";
				echo "value = ".$value[$i-1]."<br/><br/>";

				$concatcomment .= $name[$i-1]."<separator>".$date[$i-1]."<separator>".$title[$i-1]."<separator>".$content[$i-1]."<separator>".$value[$i-1]."<done>";
			}
			//echo $concatcomment;
			return $concatcomment;
		}

		public function getExtraComment($link)
		{
			$content 	= get_content($link, $this->curl_flag);
			$content 	= explode("customer reviews (newest first)", $content);
			$content 	= $content[0];
			$content 	= substr($content, strrpos($content, "href=\"")+strlen("href=\""));
			$link 		= substr($content, 0, stripos($content, "\""));

			$concatcomment = "";
			for ($j = 1; $j < 8; ++$j)
			{
				$pagelink 	= $link."&pageNumber=".$j;
				$content 	= get_content($pagelink, $this->curl_flag);

				if (stripos($content, "<span class=\"on\">".$j."</span>") === false)
				{
					break;
				}

				$content 	= substr($content, stripos($content, "<table id=\"productReviews\""));
				$result 	= explode("people found the following review helpful",$content);

				$name 		= array();
				//$email 		= array();
				$title 		= array();
				$date		= array();
				$content 	= array();
				$value 		= array();
				
				//echo "<h4>Number of content:".(sizeof($result)-1)."</h4>";
				for ($i = 1; $i < sizeof($result)-1; $i++) {
					$name[] = substr($result[$i], strpos($result[$i], "<span style = \"font-weight: bold;\">")+strlen("<span style = \"font-weight: bold;\">"));
					$name[$i-1] = substr($name[$i-1], 0, strpos($name[$i-1], "<"));

					$value[] = substr($result[$i], strpos($result[$i], "title=\"")+strlen("title=\""));
					$value[$i-1] = substr($value[$i-1], 0, strpos($value[$i-1], "."));

					$title[] = substr($result[$i], strpos($result[$i], "<b>")+strlen("<b>"));
					$title[$i-1] = substr($title[$i-1], 0, strpos($title[$i-1], "<"));

					$date[] = substr($result[$i], strpos($result[$i], "<nobr>")+strlen("<nobr>"));
					$date[$i-1] = substr($date[$i-1], 0, strpos($date[$i-1], "<"));

					$content[] = substr($result[$i], strpos($result[$i], "<span class=\"h3color tiny\">")+strlen("<span class=\"h3color tiny\">"));
					$content[$i-1] = substr($content[$i-1], strpos($content[$i-1], "</div>")+strlen("</div>"));
					$content[$i-1] = substr($content[$i-1], 0, strpos($content[$i-1], "<"));

					// echo "name = ".$name[$i-1]."<br/>";
					// echo "date = ".$date[$i-1]."<br/>";
					// echo "title = ".$title[$i-1]."<br/>";
					// echo "content = ".$content[$i-1]."<br/>";
					// echo "value = ".$value[$i-1]."<br/><br/>";

					$concatcomment .= $name[$i-1]."<separator>".$date[$i-1]."<separator>".$title[$i-1]."<separator>".$content[$i-1]."<separator>".$value[$i-1]."<done>";
				}
			}
			echo $concatcomment;
			return $concatcomment;
		}

		public function updateComment($website, $page)
		{
			if (empty($website)) 
			{
				return;
			}

			if (empty($page) || !is_numeric($page) || $page <= 0)
			{
				$page = 1;
			}

			$listPages = $this->listPages();
			$linkpage = $page % 5+1;
			$page = (($page-1) / 5)+1;
			echo "$linkpage $page<br/>";
			// Finish updating all links
			if ($page / 8 >= sizeof($listPages))
			{
				return 0;
			}

			$link = $listPages[$page/8]."&page=".$linkpage;
			echo "$link<br/><br/>";
			// Get all the model name from the page
			$modelName = $this->getModelName($link);

			// Finish updating all links
			if (sizeof($modelName) == 0)
			{
				return 0;
			}

			$numModelCrawledEachPage = sizeof($modelName) / 8;
			if ($numModelCrawledEachPage == 0) $numModelCrawledEachPage = 1;

			$lowerbound = $numModelCrawledEachPage * (($page%8-1)); 
			$upperbound = $numModelCrawledEachPage * ($page%8);

			if ($upperbound > sizeof($modelName)) $upperbound = sizeof($modelName);
			// Last page of the actual page
			if ($page % 8 == 7) $upperbound = sizeof($modelName);
			
			echo "lower = $lowerbound upper = $upperbound<br/>";	
			$db = $this->DBSite;
			for ($i = $lowerbound; $i < $upperbound; $i++)
			{
				echo $modelName[$i]["name"]."<br/>";
				// Get the list of product with the model name
				$listProductsToUpdateComment = $db->get_product_from_model_name($website, $modelName[$i]["name"]);

				if (sizeof($listProductsToUpdateComment) > 0)
				{
					// If comment are found, update the product to include the comment
					$comment = "";
					//$comment = $this->getComment($modelName[$i]["link"]);
					$comment = $comment.$this->getExtraComment($modelName[$i]["link"]);
					if (stripos($comment, "<done>") !== false)
					{
						for ($j = 0; $j < sizeof($listProductsToUpdateComment); ++$j)
						{
							$db->update_comment_product($listProductsToUpdateComment[$j]["id"], $website, $comment);
						}
					}
				}
			} 
			return 1;
		}

		public function listPages()
		{
			$listPages = array();

			// Important product
			$listPages[] = "http://www.amazon.com/s/ref=sr_nr_n_2?rh=n%3A2335752011%2Cn%3A7072561011%2Ck%3Aiphone&keywords=iphone&ie=UTF8&qid=1384908866&rnid=2335753011";
			$listPages[] = "http://www.amazon.com/s/ref=sr_nr_n_1?rh=n%3A1232597011%2Ck%3Aipad&keywords=ipad&ie=UTF8&qid=1384910112&rnid=2941120011";

			// Mobile Phones
			$listPages[] = "http://www.amazon.com/s/ref=sr_pg_1?rh=n%3A2335752011%2Cn%3A%212335753011%2Cn%3A7072561011%2Cn%3A2407747011%2Cp_n_feature_two_browse-bin%3A2420631011&bbn=2407747011&ie=UTF8&qid=1384856670";
			$listPages[] = "http://www.amazon.com/s/ref=wl_nav_cpa_left_100_att?ie=UTF8&bbn=2407747011&rh=i%3Amobile%2Cn%3A2335752011%2Cn%3A!2335753011%2Cn%3A7072561011%2Cn%3A2407747011%2Cp_n_feature_two_browse-bin%3A2420628011&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-leftnav&pf_rd_r=1AZVE83AHG2JCS2XZP6S&pf_rd_t=101&pf_rd_p=1646513402&pf_rd_i=2335752011";
			$listPages[] = "http://www.amazon.com/s/ref=wl_nav_cpa_left_100_sprint?ie=UTF8&bbn=2407747011&rh=i%3Amobile%2Cn%3A2335752011%2Cn%3A!2335753011%2Cn%3A7072561011%2Cn%3A2407747011%2Cp_n_feature_two_browse-bin%3A2420629011&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-leftnav&pf_rd_r=1B0T1W38QTQTW7NQ0BA9&pf_rd_t=101&pf_rd_p=1646513402&pf_rd_i=2335752011";

			// Camera
			$listPages[] = "http://www.amazon.com/b/ref=bw_ab_493706_1?ie=UTF8&node=2683450011&pf_rd_p=1479444002&pf_rd_s=left-2&pf_rd_t=101&pf_rd_i=493706&pf_rd_m=ATVPDKIKX0DER&pf_rd_r=19117EDSAKTFDREMRVCC";
			$listPages[] = "http://www.amazon.com/b/ref=bw_ab_493706_2?ie=UTF8&node=2683449011&pf_rd_p=1479444002&pf_rd_s=left-2&pf_rd_t=101&pf_rd_i=493706&pf_rd_m=ATVPDKIKX0DER&pf_rd_r=19117EDSAKTFDREMRVCC";

			// Computers
			$listPages[] = "http://www.amazon.com/s/ref=amb_link_378966742_8?ie=UTF8&rh=i%3Acomputers%2Cn%3A565108&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=center-4&pf_rd_r=0551A81FVW04XE68Q52R&pf_rd_t=101&pf_rd_p=1646573702&pf_rd_i=565108";
			$listPages[] = "http://www.amazon.com/s/ref=amb_link_352986662_11?ie=UTF8&rh=i%3Acomputers%2Cn%3A172282%2Cn%3A541966%2Cn%3A1232597011%2Ck%3Atablet%2Cp_n_size_browse-bin%3A1254616011%7C1254617011&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-leftnav&pf_rd_r=0ASG0HYYD32YWGAS84R8&pf_rd_t=101&pf_rd_p=1649708342&pf_rd_i=1232597011";
			$listPages[] = "http://www.amazon.com/s/ref=amb_link_352986662_12?ie=UTF8&rh=i%3Acomputers%2Cn%3A172282%2Cn%3A541966%2Cn%3A1232597011%2Ck%3Atablet%2Cp_n_size_browse-bin%3A1254618011&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-leftnav&pf_rd_r=0ASG0HYYD32YWGAS84R8&pf_rd_t=101&pf_rd_p=1649708342&pf_rd_i=1232597011";
			$listPages[] = "http://www.amazon.com/s/ref=amb_link_352986662_13?ie=UTF8&rh=i%3Acomputers%2Cn%3A172282%2Cn%3A541966%2Cn%3A1232597011%2Ck%3Atablet%2Cp_n_size_browse-bin%3A1254619011%7C1254620011&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-leftnav&pf_rd_r=0ASG0HYYD32YWGAS84R8&pf_rd_t=101&pf_rd_p=1649708342&pf_rd_i=1232597011";

			// TVs
			$listPages[] = "http://www.amazon.com/b/ref=tv_nav_type_lcd?ie=UTF8&node=6459736011&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-leftnav&pf_rd_r=04AA7ETBQYT3F3ZTHWH2&pf_rd_t=101&pf_rd_p=1660137622&pf_rd_i=1266092011";
			$listPages[] = "http://www.amazon.com/b/ref=tv_nav_type_led?ie=UTF8&node=6459737011&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-leftnav&pf_rd_r=04AA7ETBQYT3F3ZTHWH2&pf_rd_t=101&pf_rd_p=1660137622&pf_rd_i=1266092011";
			$listPages[] = "http://www.amazon.com/b/ref=tv_nav_type_pla?ie=UTF8&node=6459738011&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-leftnav&pf_rd_r=04AA7ETBQYT3F3ZTHWH2&pf_rd_t=101&pf_rd_p=1660137622&pf_rd_i=1266092011";

			return $listPages;

		}

		public function getModelName($link)
		{
			$content = get_content($link, $this->curl_flag);
			$content = substr($content, 0, stripos($content, "Previous Page"));
			$items = explode("div id=\"result_", $content);

			$modelName = array();
			for ($i = 1; $i < sizeof($items); ++$i)
			{
				$curitem = $items[$i];
				$curitem = substr($curitem, stripos($curitem, "<span class=\"lrg bold\">")+strlen("<span class=\"lrg bold\">"));
				$curitem = substr($curitem, 0, stripos($curitem, "<"));
				$curitem = explode(",", $curitem);
				$curitem = $curitem[0];
				$curitem = $this->remove_extra_word($curitem);

				$curitemLink = $items[$i];
				$curitemLink = substr($curitemLink, stripos($curitemLink, "href=\"")+strlen("href=\""));
				$curitemLink = substr($curitemLink, 0, stripos($curitemLink, "\""));

				$info = array();
				$info["name"] = trim($curitem);
				$info["link"] = trim($curitemLink);
				$modelName[] = $info;
			}

			return $modelName;
		}

		public function remove_extra_word($word) {
			$extra_word = array();
			$extra_word[] = "at&t";
			$extra_word[] = "verizon";

			$extra_word[] = "unlocked";

			$extra_word[] = "black";
			$extra_word[] = "(black)";
			$extra_word[] = "white";
			$extra_word[] = "(white)";
			$extra_word[] = "gold";
			$extra_word[] = "(gold)";
			$extra_word[] = "green";
			$extra_word[] = "(green)";
			$extra_word[] = "()";
			$extra_word[] = "-";

			$word = strtolower($word);
			for ($i = 0; $i < sizeof($extra_word); ++$i)
			{
				$word = str_replace($extra_word[$i], "", $word);
			}
			$word = str_replace("   ", " ", $word);
			$word = str_replace("  ", " ", $word);

			$word = trim($word);

			return $word;
		}
	}

	//$crawler = new amazon();
	//$crawler->getComment("http://www.amazon.com/Lenovo-IdeaPad-14-Inch-Touchscreen-Chocolate/dp/B00ATANWKG/ref=sr_1_4?ie=UTF8&qid=1386130393&sr=8-4&keywords=Lenovo+Z400T");
	//$crawler->getExtraComment("http://www.amazon.com/Lenovo-IdeaPad-14-Inch-Touchscreen-Chocolate/dp/B00ATANWKG/ref=sr_1_1?ie=UTF8&qid=1386147498&sr=8-1&keywords=lenovo+z400");
	//$crawler->getComment("http://www.amazon.com/Apple-iPhone-5c-White-Unlocked/dp/B00F3ISEUC/ref=pd_sim_cps_8");
	//$crawler->getModelName("http://www.amazon.com/s/ref=sr_pg_1?rh=n%3A2335752011%2Cn%3A%212335753011%2Cn%3A7072561011%2Cn%3A2407747011%2Cp_n_feature_two_browse-bin%3A2420631011&bbn=2407747011&ie=UTF8&qid=1384856670");
	// $result = $crawler->get_whole_product_page("http://www.lazada.com.my/shop-mobiles/", 1);

	// //Get the name, the image, the price, and the link of each product and add them to the database
	// $link = $crawler->get_link($result[0]);
	// $image = $crawler->get_image($result[0]);
	// $content = get_content($link, true);
	// $price = $crawler->get_price($content);
	// $name = $crawler->get_name($content);
	// $description = $crawler->get_description($content);
	// $extraimage = $crawler->get_extra_image($content);
	// $comment = $crawler->get_comment($content);
	?>	
</body>
</html>