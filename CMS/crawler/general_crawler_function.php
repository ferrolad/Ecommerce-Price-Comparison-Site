<?php
require_once("base_crawler.php");
require_once("generate_category.php");

function add_categories_website($cat, $page, $categories_list, $crawler) {
	$count = 0;

	if (!array_key_exists($cat, $categories_list))
	{
		echo $cat;
		foreach ($categories_list as $key => $value) {
			echo "key = $key<br/>";
		}
		echo "Key does not exist<br/>";
		return 0;
	}

	$cat2 = $cat;
    if (is_numeric(substr($cat, strlen($cat)-1, strlen($cat)-1)) && $cat != 'mp3') {
        $cat2 = substr($cat, 0, strlen($cat)-1);
    }

    $url = $categories_list[$cat];

    $count += $crawler->add_category($cat2, $url, $page);

    return $count;
}

// Get the first number from the left to the right of a string content for example
// get_closest_number("aaabbbbb  asd qweqw 156,789 asdqw 1231", ",") = 156789
function get_closest_number($content, $delimiter)
{
	$price = "";
	$start = 0;
	$content = trim($content);

	if (strlen($delimiter) == 0)
	{
		return $price;
	}

	for ($i = 0; $i < strlen($content); ++$i)
	{
		if ($start == 0 && is_numeric($content[$i]))
		{
			$start = 1;
			$price = intval($content[$i]);
			continue;
		}

		if ($start == 1 && !is_numeric($content[$i]) && $content[$i] != $delimiter[0])
		{
			break;
		}

		if ($start == 1 && is_numeric($content[$i]))
		{
			$price = $price * 10 + intval($content[$i]);
		}
	}

	return $price;
}

function get_images_page_website($category, $page, $website, $db, $resize = true) {
	$result = $db->get_images_page($category, $page);
	$count = 0;
	Xlog::record("<strong>Start downloading for $category page $page of website $website</strong>");
	/* Traverse through all products in the category */
	for ($j = 0; $j < sizeof($result); ++$j)
	{
		$row = $result[$j];
		$count++;
		Xlog::record("<strong>Start downloading image $count</strong>");
		/* If that product already has new image, break out */
		if ($row["newimage1"] <> "") 
		{
			Xlog::record("Already crawled image, skip");
			continue;
		}

		/* Try to get 5 images for each product*/
		for ($i = 1; $i <=5; $i++) {

			if (empty($row["orgimage1"]))
			{
				$db->remove_product($row["id"]);
				Xlog::record("Missing first pictures, remove");
				break;
			}

			/*if (empty($row["newimage1"]) && $i > 1)
			{
				$db->remove_product($row["id"]);
				Xlog::record("First image can't be crawl and updated, remove");
				break;
			}*/

			/* If the image link exists, download it to local server */
			if ($row["orgimage".$i] <> "") {
				try
				{
					Xlog::record("<br/><br/>Image URL = ".$row["orgimage".$i]);					
					// The file
					$dir = dirname(__FILE__)."/../../img/$website/current$i/";
					is_dir($dir) || @mkdir($dir, 755, true) || die("Can't Create folder");
					$filename = $dir.$row["id"].".jpg";
					file_put_contents($filename, file_get_contents($row["orgimage".$i]));
				
					$size = intval(filesize($filename));

					/* Resize the image */
					if ($resize && $size < 50000)
					{
						
								// Content type
						header('Content-Type: image/jpeg');
									// Get new dimensions
						list($width, $height) = getimagesize($filename);
									// Resample
						$image_p = imagecreatetruecolor(258, 262);

						// Check if module exif exits to call function exif_imagetype to check image type
						$size = intval(filesize($filename));
						if (extension_loaded("exif"))
						{
							$image = "";
							$image_type = exif_imagetype($filename);

							// Determine image type
							Xlog::record("Image type = $image_type");
							if ($image_type == IMAGETYPE_PNG) 
							{
								$image = imagecreatefrompng($filename);
							}
							else if ($image_type == IMAGETYPE_JPEG)
							{
								$image = imagecreatefromjpeg($filename);
							}
							else if ($image_type == IMAGETYPE_GIF)
							{
								$image = imagecreatefromgif($filename);
							}
							Xlog::record("Done creating image");

							// If the image type is undeterminable, delete it and skip it
							if ($image == "") 
							{
								//Xlog::record("Wrong format Image");
								Xlog::record("Wrong format Image, just reuse the original image url");
								//$db->remove_product($row["id"]);
								$db->reuse_original_image($row["id"], $i);
								break;
							}

							Xlog::record("Start resizing image");
							imagecopyresampled($image_p, $image, 0, 0, 0, 0, 258, 262, $width, $height);
										//Write the resized image to disk
							Xlog::record("Finish resizing image");
						}
						else 
						{
							$image = imagecreatefromjpeg($filename);
							/* If file is not jpeg, try png */
							if ($image == false) {
								$image = imagecreatefrompng($filename);
							}
							imagecopyresampled($image_p, $image, 0, 0, 0, 0, 258, 262, $width, $height);
										//Write the resized image to disk
						}
						
						Xlog::record("Start writing image to disk");
                        if(imagejpeg($image_p,$filename,100))
                            Xlog::record("Finish writing image to disk<br/>");
                        else
                            Xlog::record("Fail writing image to disk<br/>");
						imagedestroy($image_p);
					}

					if ($size < 50000)
					{
						/* Print out the image */
						Xlog::record("Updating database of the new image");
						Xlog::record("<img src = \"/img/$website/current$i/".$row["id"].".jpg\">");
						$db->updateNewImage($i, $row["id"], "/img/$website/current$i/".$row["id"].".jpg");
						Xlog::record("Finish database of the new image");
					}
					else
					{
						//$db->remove_product($row["id"]);
						$db->reuse_original_image($row["id"], $i);
						//Xlog::record("Image too big remove");
						Xlog::record("Image too big, just reuse the original image url");
						break;
					}
				}
				catch (Exception $e)
				{
					echo $e->getMessage();
					continue;
				}
			}
		}			
	}
	echo "count = $count<br/>";
	return $count;
}

/* Get the content of the product detail page using the link */
function get_content($link, $curl_flag) {
	if ($curl_flag) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.2; rv:2.0.1) Gecko/20100101 Firefox/4.0.1");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
		curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
		curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
		curl_setopt($ch, CURLOPT_HEADER, TRUE);

		$content 	= curl_exec($ch);
		curl_close($ch);
		return $content;
	} else {
		return file_get_contents($link);
	}
}

function update_price_page_website($category, $page, $db, $crawler, $curl_flag) {
	/* Get the link and the old price of all the product in that category */
	$result = $db->get_price_page($category, $page);
	$count = 0;
	Xlog::record("Start updating price for $category on page $page");
	for ($j = 0; $j < sizeof($result); ++$j)
	{
		$row = $result[$j];
		$count++;
		Xlog::record("Update price product $count");
		$link = $row["link"];
		$content = get_content($link, $curl_flag);
		$price = intval($crawler->get_price($content));

		if ($price == "" || !is_numeric($price)) $price = 0;

		$extra_image = array();
		$extra_image[] = "";
		for ($i = 1; $i <= 7; ++$i)
		{
			$extra_image[] = $row["orgimage".$i];
		}

		if (is_numeric($price) && ($price >= 1 || $price == 0)) {
			$db->add_prepocess_product($row["category"], $row["link"], $row["name"], $price, $row["orgimage"], $row["comment"], $row["description"], $row["cat1"], $row["subcat1"], $extra_image);
		}
		else 
		{
			echo $row["link"]."<br/>Price is not number or well-formated. Skip<br/>";
		}
	}
	echo "Done updating price for page $page<br/>";
	return $count;
}

/* Add all the products in the given category from the given link */
function add_category_website($category, $link, $page, $crawler, $db, $description_from_content, $curl_flag) {
	//Compute the category id
	$catid = generate_catid($category);

	/* Return the wrong cat */
	if ($catid == 0) return 0;

	$result = $crawler->get_whole_product_page($link, $page);

	$count = 0;
	//Get the name, the image, the price, and the link of each product and add them to the database
	for ($i = 0; $i < sizeof($result); $i++) {
		$link = $crawler->get_link($result[$i]);
		$image = $crawler->get_image($result[$i]);

		/* If the description_from_content flag is false */
		if (!$description_from_content) {
			$description = $crawler->get_description($result[$i]);
			$description2 = $crawler->get_description2($result[$i]);
		}

		$content = get_content($link, $curl_flag);
		$comment = $crawler->get_comment($content);
		if (stripos($comment, "done") === false) {
			$comment = "";	
		}
		//$comment = "";
		$price = $crawler->get_price($content);

		if (!(is_numeric($price))) 
		{
			echo "Price is not numeric or well formated. Terminated<br/>";
			continue;
		}

		$name = $crawler->get_name($content);
		/* If the description_from_content flag is true */
		if ($description_from_content) {
			$description = $crawler->get_description($content);
			$description2 = $crawler->get_description2($content);
		}

		if (strlen($description) < 10) 
		{
			echo "Description is too short. Terminated<br/>";
			continue;
		}

		$extraimage = $crawler->get_extra_image($content);
		/* Calculate sub category ids*/
		$subcat = generate_subcatid($category, $name);
		/* Return the wrong subcat */
		if ($subcat == 0 && $catid != 2) 
		{
			echo "Category generation is incorrect. Terminated";
			return 0;
		}

		echo "Product $i<br/>";
		$db->add_prepocess_product($category, $link, $name, $price, $image, $comment, $description, $catid, $subcat, $extraimage, $description2);		
		$count++;
	}
	return $count;
}

?>