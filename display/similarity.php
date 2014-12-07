<?php
include "brand.php";
include "model.php";

/* A word is considered model keyword if it contains at least a number */
function model_keyword_mobile($keyword)
{
	for ($i = 0; $i < strlen($keyword); ++$i)
	{
		if (is_numeric($keyword[$i]))
		{
			return true;
		}
	}
	return false;
}

/* The longest word with number in it consider the model keyword */
function find_model_keyword_mobile($product_name)
{
	$model_keyword = "";

	for ($i = 0; $i < sizeof($product_name); ++$i)
	{
		if (model_keyword_mobile($product_name[$i]) && strlen($product_name[$i]) > 1 && strlen($product_name[$i]) > strlen($model_keyword))
		{
			$model_keyword = $product_name[$i];
		}
	}

	return $model_keyword;
}

/* The longest word with number in it consider the model keyword */
function find_model_keyword_female_fashion($product_name)
{
	$model_keyword = "";
	$keywords = array("ao", "quan", "short", "jean", "khaki", "kaki", "underwear", "sooc", "sip", "dui", "lot");

	for ($i = 0; $i < sizeof($product_name); ++$i)
	{
		for ($j = 0; $j < sizeof($keywords); ++$j)
		{
			if (stripos($product_name[$i], $keywords[$j]) !== false)
			{
				return $keywords[$j];
			}
		}
	}

	return $model_keyword;
}

/* Calculate similarity degree of mobiles */
function calculate_similarity_degree_mobiles($keyword_name, $product_keyword_name, $cat, $subcat, $brand)
{
	$model_word_1 = find_model_keyword_mobile($keyword_name);
	$model_word_2 = find_model_keyword_mobile($product_keyword_name);

	// Definitely the same
	if (!empty($model_word_1) && !empty($model_word_2) && $model_word_1 == $model_word_2)
	{
		return sizeof($keyword_name);
	}

	// Might or might not be the same
	if (empty($model_word_1) && empty($model_word_2))
	{
		return -1;
	}

	// Definitely not the same
	return 0;
}

/* Calculate similarity degree of tablets */
function calculate_similarity_degree_tablets($keyword_name, $product_keyword_name, $cat, $subcat, $brand)
{
	return calculate_similarity_degree_mobiles($keyword_name, $product_keyword_name, $cat, $subcat, $brand);
}

/* Calculate similarity degree of computer */
function calculate_similarity_degree_computer($keyword_name, $product_keyword_name, $cat, $subcat, $brand)
{
	return calculate_similarity_degree_mobiles($keyword_name, $product_keyword_name, $cat, $subcat, $brand);
}

/* Calculate similarity degree of camera */
function calculate_similarity_degree_camera($keyword_name, $product_keyword_name, $cat, $subcat, $brand)
{
	return calculate_similarity_degree_mobiles($keyword_name, $product_keyword_name, $cat, $subcat, $brand);
}

/* Calculate similarity degree of tv */
function calculate_similarity_degree_tv($keyword_name, $product_keyword_name, $cat, $subcat, $brand)
{
	return calculate_similarity_degree_mobiles($keyword_name, $product_keyword_name, $cat, $subcat, $brand);
}

/* Calculate similarity degree of audio */
function calculate_similarity_degree_audio($keyword_name, $product_keyword_name, $cat, $subcat, $brand)
{
	return calculate_similarity_degree_mobiles($keyword_name, $product_keyword_name, $cat, $subcat, $brand);
}

/* Calculate similarity degree of accessories */
function calculate_similarity_degree_accessories($keyword_name, $product_keyword_name, $cat, $subcat, $brand)
{
	return calculate_similarity_degree_mobiles($keyword_name, $product_keyword_name, $cat, $subcat, $brand);
}

/* Calculate similarity degree of female fashion */
function calculate_similarity_degree_female_fashion($keyword_name, $product_keyword_name, $cat, $subcat, $brand)
{
	$model_word_1 = find_model_keyword_female_fashion($keyword_name);
	$model_word_2 = find_model_keyword_female_fashion($product_keyword_name);

	// Definitely the same
	if (!empty($model_word_1) && !empty($model_word_2) && $model_word_1 == $model_word_2)
	{
		return sizeof($keyword_name);
	}

	// Might or might not be the same
	if (empty($model_word_1) && empty($model_word_2))
	{
		return -1;
	}

	// Definitely not the same
	return 0;
}

/* Calculate similarity degree of male fashion */
function calculate_similarity_degree_male_fashion($keyword_name, $product_keyword_name, $cat, $subcat, $brand)
{
	return calculate_similarity_degree_female_fashion($keyword_name, $product_keyword_name, $cat, $subcat, $brand);
}

function general_similarity($keyword_name, $product_keyword_name, $cat, $subcat, $brand)
{
	$size = sizeof($product_keyword_name);
	$count = 0;
	$similar_word = 0;
	/* If in the first 3 words, if 2 wwords match, highly likely it is correct */
	if ($size >= 2) {
		if (in_array($product_keyword_name[0], $keyword_name)) {
			$count++;
		}
		if (in_array($product_keyword_name[1], $keyword_name)) {
			$count++;
		}
		if ($count == 2 && $size <= 3) {
			return sizeof($keyword_name);
		}
	} else {
		if (in_array($product_keyword_name[0], $keyword_name)) {
			return sizeof($keyword_name);
		}
	}

	if ($size >= 3 && $size <= 4) {
		if (in_array($product_keyword_name[2], $keyword_name) && $count >= 2) {
			return sizeof($keyword_name);
		}
	}

	$model = model_word($cat, $subcat, $brand);

	for ($i = 0; $i < $size; $i++) {
		/* If the word is not empty */
		if (!empty($product_keyword_name[$i]) && in_array($product_keyword_name[$i], $keyword_name)) 
		{
			/* If the main product contains that word of the compare product */
			$similar_word += 2;
			if ($i < $size-1) {
				$i++;
				if (in_array($product_keyword_name[$i], $keyword_name) || in_array($product_keyword_name[$i], $model)) {
					/* If the word is a model word */
					$similar_word+=2;	
					return $similar_word;
				}
				if ($i < $size-1) {
					$i++;
					if (in_array($product_keyword_name[$i], $keyword_name) || in_array($product_keyword_name[$i], $model)) {
						/* If the word is a model word */
						$similar_word+=2;	
						return $similar_word;
					}
				}
			}
		}
	}
				
	return $similar_word;
}

/* Function calculate the similarity degree between 2 products */
function calculate_similarity_degree($keyword_name, $product_keyword_name, $cat, $subcat, $brand) 
{
	$similar_word = -1;
	$size = sizeof($product_keyword_name);

	if ($size <= 0) 
	{
		return 0;
	}

	switch ($cat)
	{
		case 1:
			$similar_word = calculate_similarity_degree_mobiles($keyword_name, $product_keyword_name, $cat, $subcat, $brand);	
			break;
		case 2:
			$similar_word = calculate_similarity_degree_tablets($keyword_name, $product_keyword_name, $cat, $subcat, $brand);	
			break;
		case 3:
			$similar_word = calculate_similarity_degree_computer($keyword_name, $product_keyword_name, $cat, $subcat, $brand);	
			break;
		case 4:
			$similar_word = calculate_similarity_degree_camera($keyword_name, $product_keyword_name, $cat, $subcat, $brand);	
			break;
		case 5:
			$similar_word = calculate_similarity_degree_tv($keyword_name, $product_keyword_name, $cat, $subcat, $brand);	
			break;
		case 6:
			$similar_word = calculate_similarity_degree_audio($keyword_name, $product_keyword_name, $cat, $subcat, $brand);	
			break;
		case 7:
			$similar_word = calculate_similarity_degree_accessories($keyword_name, $product_keyword_name, $cat, $subcat, $brand);	
			break;
		case 8:
			$similar_word = calculate_similarity_degree_female_fashion($keyword_name, $product_keyword_name, $cat, $subcat, $brand);	
			break;
		case 9:
			$similar_word = calculate_similarity_degree_male_fashion($keyword_name, $product_keyword_name, $cat, $subcat, $brand);
			break;
	}

	if ($similar_word >= 0)
	{
		return $similar_word; 
	}

	$similar_word += general_similarity($keyword_name, $product_keyword_name, $cat, $subcat, $brand);

	return $similar_word;
} 

function price_mismatch($product, $cat, $subcat, $name, $website, $brand, $model, $price)
{
	$product_price = $product["price"];

	$upper_bound = $product_price * 1.15;
	if ($upper_bound < $product_price + 50)
	{
		$upper_bound = $product_price + 1000;
	}

	$lower_bound = $product_price * 0.85;
	if ($lower_bound > $product_price - 50)
	{
		$lower_bound = $product_price - 1000;
	}

	if ($price > $upper_bound || $price < $lower_bound)
	{
		return true;
	}

	return false;
}

/* Helper function that generates a list of similar products */
function find_similarproduct($cat, $subcat, $name, $website, $brand, $model, $price, $db) {
	$result = $db->get_similar_product($brand, $model, $cat, $subcat);
	/* If there is both identified brand and product, just return the result */
	if (!empty($brand) && !empty($model)) {
		return $result;
	}

	$keyword_name = create_keyword_name($name);

	$size2 = sizeof($keyword_name);

	/* array of similar products */
	$similarproduct = array();

	/* Iterate through the list of result to find similar products */
	for ($i = 0; $i < sizeof($result); $i++) {
		$product = $result[$i];
		
		$product_name = $product["name"];
		/* If the product is in fashion, use all the keyword */
		if ($cat == 8 || $cat == 9) {
			$product_name = $product["no_accent_name"];
		}

		if ($product["no_accent_name"] == $name && $product["website"] == $website) {
			$similarproduct[] = $result[$i];
			continue;
		}
		
		// If their prices are too different, move on to the next product
		if (price_mismatch($product, $cat, $subcat, $name, $website, $brand, $model, $price))
		{
			continue;
		}
		
		$product_keyword_name = create_keyword_name($product_name);

		/* Check if the two products are similar or not */
		$similarity_degree = calculate_similarity_degree($keyword_name, $product_keyword_name, $cat, $subcat, $brand);
		$size1 = sizeof($product_keyword_name);

		/* If their names are not similar enough, move on to the next product */
		if ($similarity_degree/$size1 < 0.6 && $similarity_degree/$size2 < 0.6 && $similarity_degree < 3) 
		{
			continue;
		}

		$similarproduct[] = $result[$i];
	}
	return $similarproduct;
}
?>