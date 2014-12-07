<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	require_once("general_crawler_function.php");

	class mysmartprice {
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
			$this->name = "mysmartprice";
			$this->DBSite->SetConfig($host, $user, $pass, $db, $this->name);
			$this->curl_flag = true;
		}

		public function getVideo($link)
		{
			$content = get_content($link, $this->curl_flag);
			$content = explode("<div class=\"mobile_video\">", $content);

			if (sizeof($content) > 1)
			{
				$video = $content[1];
				$video = substr($video, stripos($video, "src=\"")+strlen("src=\""));
				$video = substr($video, 0, stripos($video, "\""));

				$video_array = array();
				$video_array[] = $video;
				return $video_array;
			}

			return array();
		}

		public function updateVideo($website, $page)
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

			// Finish updating all links
			if ($page / 15 >= sizeof($listPages))
			{
				return 0;
			}

			$link = $listPages[$page/15];
			echo "$link<br/><br/>";
			// Get all the model name from the page
			$modelName = $this->getModelName($link);

			// Finish updating all links
			if (sizeof($modelName) == 0)
			{
				return 0;
			}

			$numModelCrawledEachPage = sizeof($modelName) / 15;
			if ($numModelCrawledEachPage == 0) $numModelCrawledEachPage = 1;

			$lowerbound = $numModelCrawledEachPage * (($page%15) - 1); 
			$upperbound = $numModelCrawledEachPage * ($page%15);

			if ($upperbound > sizeof($modelName)) $upperbound = sizeof($modelName);
			// Last page of the actual page
			if ($page % 15 == 14) $upperbound = sizeof($modelName);
				
			$db = $this->DBSite;
			for ($i = $lowerbound; $i < $upperbound; $i++)
			{
				echo $modelName[$i]["name"]."<br/>";
				// Get the list of product with the model name
				$listProductsToUpdateVideo = $db->get_product_from_model_name($website, $modelName[$i]["name"]);

				if (sizeof($listProductsToUpdateVideo) > 0)
				{
					// If videos are found, update the product to include the video
					$videos = $this->getVideo($modelName[$i]["link"]); 
					if (sizeof($videos) > 0)
					{
						if (sizeof($videos) < 2)
						{
							$videos[] = "";
						}

						for ($j = 0; $j < sizeof($listProductsToUpdateVideo); ++$j)
						{
							$db->update_video_product($listProductsToUpdateVideo[$j]["id"], $website, $videos[0], $videos[1]);
						}
					}
				}
			} 
			return 1;
		}

		public function listPages()
		{
			$listPages = array();

			// Mobile Phones
			$listPages[] = "http://www.mysmartprice.com/mobile/pricelist/nokia-mobile-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/mobile/pricelist/samsung-mobile-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/mobile/pricelist/blackberry-mobile-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/mobile/pricelist/lg-mobile-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/mobile/pricelist/sony-ericsson-mobile-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/mobile/pricelist/motorola-mobile-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/mobile/pricelist/htc-mobile-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/mobile/pricelist/apple-mobile-price-list-in-india.html";

			// Camera
			$listPages[] = "http://www.mysmartprice.com/camera/pricelist/nikon-camera-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/camera/pricelist/canon-camera-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/camera/pricelist/sony-camera-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/camera/pricelist/kodak-camera-price-list-in-india.html";

			// Computers
			$listPages[] = "http://www.mysmartprice.com/computer/pricelist/hp-laptop-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/computer/pricelist/dell-laptop-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/computer/pricelist/sony-laptop-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/computer/pricelist/acer-laptop-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/computer/pricelist/lenovo-laptop-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/computer/pricelist/toshiba-laptop-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/computer/pricelist/samsung-laptop-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/computer/pricelist/apple-laptop-price-list-in-india.html";

			// TVs
			$listPages[] = "http://www.mysmartprice.com/electronics/pricelist/samsung-tv-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/electronics/pricelist/sony-tv-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/electronics/pricelist/lg-tv-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/electronics/pricelist/toshiba-tv-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/electronics/pricelist/panasonic-tv-price-list-in-india.html";
			$listPages[] = "http://www.mysmartprice.com/electronics/pricelist/philips-tv-price-list-in-india.html";

			return $listPages;

		}

		public function getModelName($link)
		{
			$content = get_content($link, $this->curl_flag);
			$items = explode("<div class=\"item\">", $content);
			$startSymbol = "alt=\"";
			$endSymbol = "\"";

			if (sizeof($items) == 1)
			{
				$items = explode("div class=\"msplistitem\"", $content);
				$startSymbol = "class=\"item-title\">";
				$endSymbol = "<";
			}

			$modelName = array();
			for ($i = 1; $i < sizeof($items); ++$i)
			{
				$curitem = $items[$i];
				$curitem = substr($curitem, stripos($curitem, $startSymbol)+strlen($startSymbol));
				$curitem = substr($curitem, 0, stripos($curitem, $endSymbol));

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
	}
	?>	
</body>
</html>