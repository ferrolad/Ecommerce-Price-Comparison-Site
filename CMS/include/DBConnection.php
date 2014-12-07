<?php
class DBCustomer {
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $dbcust;
	
	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
		$db = new PDO("mysql:host=".$this->hostname.";dbname=".$this->db, $this->username, $this->password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);		
		$this->dbcust = $db;
	}
	
	/* Function adds new customers into the database */
	public function add ($email, $firstname, $lastname, $createdate, $createtime, $updatedate, $updatetime,$gender, $birthdate=""){
		$db = $this->dbcust;	

		/* Check if there exists a customer with that email or not*/
		$sql = "SELECT * FROM customers WHERE email = '$email'";
		$sql = $db->prepare($sql);
		$sql->execute();

		/* If that email hasn't existed, add it in */
		if (sizeof($sql->fetchAll(PDO::FETCH_ASSOC)) <= 1) {
			$sql = "INSERT INTO customers (email, firstname, lastname, createdate, createtime,updatedate,updatetime,gender,birthdate) VALUES ('$email', '$firstname', '$lastname', '$createdate', '$createtime', '$updatedate', '$updatetime', '$gender', '$birthdate')";
			Xlog::record($sql);
			$sql = $db->prepare($sql);
			$sql->execute();
		}
	}
	
	/* Function returns the list of customers */
	public function get() {
		/* Prepare database */
		$db = $this->dbcust;	

		/* Select all customers */
		$sql = "SELECT * FROM customers";
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}
}

class DBComment {
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $dbcomm;
	
	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
		$db = new PDO("mysql:host=".$this->hostname.";dbname=".$this->db, $this->username, $this->password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);		
		$this->dbcomm = $db;
	}
	
	/* Function to add comment for a product */
	public function add ($name, $title, $content, $rating, $id, $website, $createdate) {
		/* Prepare database */
		$db = $this->dbcomm;	

		$comment = $name."<separator>".$createdate."<separator>".$title."<separator>".$content."<separator>".$rating."<done>";
		echo "here";
		/* Insert the new comment */
		if ($website != "modelproduct")
		{
			$sql = "SELECT comment FROM site_".$website." WHERE id = ".$id;
			$sql = $db->prepare($sql);
			$sql->execute();
			$existing_comment = $sql->fetchAll(PDO::FETCH_ASSOC);
			$existing_comment = $existing_comment[0]["comment"]; 

			$comment = $existing_comment.$comment;

			$sql = "UPDATE site_".$website." SET comment = '".$comment."' WHERE id = ".$id;
			$sql = $db->prepare($sql);
			$sql->execute();
		}
		else
		{
			$sql = "SELECT comment FROM model WHERE id = ".$id;
			$sql = $db->prepare($sql);
			$sql->execute();
			$existing_comment = $sql->fetchAll(PDO::FETCH_ASSOC);
			$existing_comment = $existing_comment[0]["comment"]; 

			$comment = $existing_comment.$comment;

			$sql = "UPDATE model SET comment = '".$comment."' WHERE id = ".$id;
			$sql = $db->prepare($sql);
			$sql->execute();
		}
	}
	
	public function get($website, $id) {
		/* Prepare database */
		$db = $this->dbcomm;	

		/* Insert the new comment */
		$sql = "SELECT * FROM comment WHERE productid = $id AND website = '$website' ORDER BY date DESC";
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}

	public function get_avg_rating($website, $id)
	{
		/* Prepare database */
		$db = $this->dbcomm;	

		/* Insert the new comment */
		$sql = "SELECT AVG(rating) FROM comment WHERE productid = ".$id." AND website = '".$website."'";
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}

	public function recordVote($vote, $id, $website)
	{
		if ($vote < 1 || $vote > 5) return;

		/* Prepare database */
		$db = $this->dbcomm;	

		/* Insert new vote */
		if ($website == 'modelproduct')
		{
			$website = 'model';
		}
		else
		{
			$website = 'site_'.$website;
		}

		$sql = "SELECT vote FROM `$website` WHERE id = ".$id;
		$sql = $db->prepare($sql);
		$sql->execute();
		
		$voteArray = $sql->fetchAll(PDO::FETCH_ASSOC); 

		if (sizeof($voteArray) != 1) return;

		$voteArray = $voteArray[0]["vote"];
		$voteArray = explode("<>", $voteArray);
		$voteArray[$vote-1] = $voteArray[$vote-1]+1;

		$voteString = implode("<>", $voteArray);
		$sql = "UPDATE `$website` set vote = '".$voteString."' WHERE id = ".$id;
		$sql = $db->prepare($sql);
		$sql->execute();

		return array_sum($voteArray);
	}
}

class DBSeoText 
{
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $dbseo;
	
	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
		$db = new PDO("mysql:host=".$this->hostname.";dbname=".$this->db, $this->username, $this->password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);		
		$this->dbseo = $db;
	}
	
	public function get($website, $id) {
		/* Prepare database */
		$db = $this->dbseo;	

		/* Insert the new comment */
		$sql = "SELECT * FROM seo_text WHERE product_id = ".$id." AND website = '".$website."'";
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}
}

class DBEditorialReview
{
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $dbreview;
	
	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
		$db = new PDO("mysql:host=".$this->hostname.";dbname=".$this->db, $this->username, $this->password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);		
		$this->dbreview = $db;
	}
	
	public function add($reviewer = "", $model = 0, $website = "", $product_id, $seo_text, $active = 1, $rating = 5) {
		/* Prepare database */
		$db = $this->dbreview;	

		// Check condition to add valid review
		if (empty($product_id) || empty($seo_text) || !is_numeric($active) || !is_numeric($model) || !is_numeric($rating) || !is_numeric($product_id) || $rating > 5 || $rating < 1)
		{
			return;
		}

		$seo_text = str_replace("'", "\'", $seo_text);
		$time = date("d/m/y g:h:i");
		/* Insert the new comment */
		$sql = "INSERT INTO editorial_review (reviewer, model, website, product_id, seo_text, active, rating, time) VALUES ('".$reviewer."', ".$model.", '".$website."', ".$product_id.", '".$seo_text."', ".$active.", ".$rating.", '".$time."')";
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}

	public function get($model = 0, $website = "", $product_id, $active = 1) {
		/* Prepare database */
		$db = $this->dbreview;	

		/* Insert the new comment */
		$sql = "SELECT * FROM editorial_review WHERE model = ".$model." AND website = '".$website."' AND product_id = ".$product_id." AND active = ".$active;
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}
}

class DBProductVideo
{
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $dbvideo;
	
	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
		$db = new PDO("mysql:host=".$this->hostname.";dbname=".$this->db, $this->username, $this->password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);		
		$this->dbvideo = $db;
	}
	
	public function addModelVideo($model_id, $link1, $link2) {
		/* Prepare database */
		$db = $this->dbvideo;	

		// Check condition to add valid video
		if (empty($model_id) || (empty($link1) && empty($link2)))
		{
			return;
		}

		// TODO implement video for model
		return;
	}

	public function AddProductVideo($product_id, $website, $link1, $link2) {
		/* Prepare database */
		$db = $this->dbvideo;	

		// Check condition to add valid video
		if (empty($model_id) || (empty($link1) && empty($link2)))
		{
			return;
		}

		/* Insert the new video */
		$sql = "UPDATE site_".$website." SET video1 = '".$link1."' AND video2 = '".$link2."' WHERE id = ".$product_id;
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}

	public function get($product_id, $website) {
		/* Prepare database */
		$db = $this->dbvideo;	

		/* Insert the new comment */
		$sql = "SELECT video1, video2 FROM site_".$website." id = ".$product_id;
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}
}

class DBSearchStats
{
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $dbstats;
	
	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
		$db = new PDO("mysql:host=".$this->hostname.";dbname=".$this->db, $this->username, $this->password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);		
		$this->dbstats = $db;
	}
	
	public function add($term, $category, $subcategory, $website, $lower_price, $upper_price) {
		/* Prepare database */
		$db = $this->dbstats;	

		/* Update the search statistics table */
		$sql = "INSERT INTO searchstats (term, cat, subcat, website, lowerprice, upperprice, time) VALUES ('".$term."', '".$category."', '".$subcategory."', '".$website."', ".$lower_price.", ".$upper_price.", '".(date("Y-m-d H:i:s"))."')";
		if ($sql = $db->prepare($sql)) {
			$sql->execute();
		}
	}
}

class DBCategory {
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $dbcat;
	
	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
		$db = new PDO("mysql:host=".$this->hostname.";dbname=".$this->db, $this->username, $this->password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);		
		$this->dbcat = $db;
	}
	
	/* Function adds new category */
	public function add ($category, $vnese_catname, $numproduct){
		$db = $this->dbcat;	

		/* Insert the new category */
		$sql = "INSERT INTO product_category (Category, num_product, vnese_name) VALUES ('$category', $numproduct, '$vnese_catname')";
		$sql = $db->prepare($sql);
		$sql->execute();
	}
	
	/* Function returns an array of categories */
	public function get() {
		$db = $this->dbcat;	

		/* Get the list of categories */
		$sql = "SELECT * FROM product_category";
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}

	/* Function return the name of a category given an id */
	public function get_catname($id) {
		$db = $this->dbcat;	

		/* Get the name of the category */
		$sql = "SELECT * FROM product_category WHERE id = ".$id;
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}

	/* Function return the name of a category given an id */
	public function get_subcatname($id) {
		$db = $this->dbcat;	

		/* Get the name of the category */
		$sql = "SELECT * FROM product_subcat WHERE id = ".$id;
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}

	/* Function adds new subcategory */
	public function addsub($category, $subcategory, $vnese_subcatname, $numproduct) {
		$db = $this->dbcat;	

		/* Insert new subcategory */
		$sql = "INSERT INTO product_subcat (catid, subcat, vnese_name, num_product) VALUES ($category, '$subcategory', '$vnese_subcatname', $numproduct)";
		$sql = $db->prepare($sql);
		$sql->execute();
	}

	/* Function get the list of subcategoies of a given category id */
	public function get_subcat($id) {
		$db = $this->dbcat;	

		/* Get the list of subcategories */
		$sql = "SELECT * FROM product_subcat WHERE catid = $id";
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}

	/* Function removes the category and all its subcategories given the category's id */
	public function remove($id) {
		$db = $this->dbcat;	

		/* Delete category and all subcategories in that category */
		$sql = "DELETE FROM product_category WHERE id = $id";
		$sql = $db->prepare($sql);
		$sql->execute();

		$sql = "DELETE FROM product_subcat WHERE catid = $id";
		$sql = $db->prepare($sql);
		$sql->execute();
	}

	/* Function get the name of the category given its id, both english and vietnamese names */
	public function get_namecat($id) {
		$db = $this->dbcat;

		/* Get the list of subcategories */
		$sql = "SELECT category, vnese_name FROM product_category WHERE id = $id";
		$sql = $db->prepare($sql);
		$sql->execute();

		$result = $sql->fetchAll(PDO::FETCH_ASSOC); 
		$row = $result[0];

		$name_result = array();
		$name_result[] = $row["category"];
		$name_result[] = $row["vnese_name"];
		/* Return the name of the category */
		return $name_result; 
	}

	/* Function that update the number of products in each category and subcategory */
	public function update_num_products() {
		// $db = $this->dbcat;	

		// /* Get the list of subcategories */
		// $sql = "SELECT COUNT(*) count, cat1, subcat1 FROM (SELECT * FROM dienmay UNION SELECT * FROM dienmaycholon UNION SELECT * FROM dienmaythienhoa UNION SELECT * FROM nguyenkim UNION SELECT * FROM thegioididong UNION SELECT * FROM lazada) A GROUP BY A.cat1, A.subcat1";
		// $sql = $db->prepare($sql);
		// $sql->execute();

		// $result = $sql->fetchAll(PDO::FETCH_ASSOC); 

		// /* Reset the number of products in each category to zero */
		// $sql = "UPDATE product_category SET num_product = 0";
		// $sql = $db->prepare($sql);
		// $sql->execute();

		//  Iterate through the list of number of products from each subcategory, update the number of products in the database 
		// for ($i = 0; $i < sizeof($result); $i++) {
		// 	$subcat = $result[$i];
		// 	/* Update the number of products in the subcategory */
		// 	$sql = "UPDATE product_subcat SET num_product = ".$subcat["count"]." WHERE id = ".$subcat["subcat1"];
		// 	$sql = $db->prepare($sql);
		// 	$sql->execute();
		// 	/* Update the number of products in the category */
		// 	$sql = "UPDATE product_category SET num_product = num_product + ".$subcat["count"]." WHERE id = ".$subcat["cat1"];
		// 	$sql = $db->prepare($sql);
		// 	$sql->execute();
		// }
	}

	/* Function returns the number of products without images */
	function num_non_image_products ($subcat, $cat) {
		// $db = $this->dbcat;		

		// /* Get the list of products without images */
		// $sql = "SELECT * FROM (SELECT * FROM dienmay UNION SELECT * FROM dienmaycholon UNION SELECT * FROM dienmaythienhoa UNION SELECT * FROM nguyenkim UNION SELECT * FROM thegioididong UNION SELECT * FROM lazada) A WHERE (newimage1 IS NULL or newimage1 = '') AND subcat1 = $subcat AND cat1 = $cat";
		// $sql = $db->prepare($sql);
		// $sql->execute();
		// $result = $sql->fetchAll(PDO::FETCH_ASSOC); 

		// return sizeof($result);
	}

	/* Function returns the number of products with invalid images */
	function num_invalid_image_products ($subcat, $cat) {
		// $db = $this->dbcat;	

		// /* Get the list of products without images */
		// $sql = "SELECT * FROM (SELECT * FROM dienmay UNION SELECT * FROM dienmaycholon UNION SELECT * FROM dienmaythienhoa UNION SELECT * FROM nguyenkim UNION SELECT * FROM thegioididong UNION SELECT * FROM lazada) A WHERE newimage1 IS NOT NULL AND newimage1 <> '' AND subcat1 = $subcat AND cat1 = $cat";
		// $sql = $db->prepare($sql);
		// $sql->execute();
		// $result = $sql->fetchAll(PDO::FETCH_ASSOC); 

		// $num_invalid_image = 0;
		// for ($i = 0; $i < sizeof($result); $i++) {
		// 	$row = $result[$i];
		// 	if (!file_exists("..".$row["newimage1"]) || filesize("..".$row["newimage1"]) < 3*1024) {
		// 		$num_invalid_image++;
		// 	}
		// }

		// return $num_invalid_image;
	}

	/* Function that delete products without images */
	function clean_non_image_products() {
		// $db = $this->dbcat;	

		// /* Delete products without images from the database */
		// $sql = "DELETE FROM (SELECT * FROM dienmay UNION SELECT * FROM dienmaycholon UNION SELECT * FROM dienmaythienhoa UNION SELECT * FROM nguyenkim UNION SELECT * FROM thegioididong UNION SELECT * FROM lazada) A WHERE newimage1 IS NULL or newimage1 = ''";
		// $sql = $db->prepare($sql);
		// $sql->execute();
	}

	/* Function that delete products without images */
	function clean_invalid_image_products() {
		// $db = $this->dbcat;	

		// /* Delete products without images from the database */
		// $sql = "DELETE FROM (SELECT * FROM dienmay UNION SELECT * FROM dienmaycholon UNION SELECT * FROM dienmaythienhoa UNION SELECT * FROM nguyenkim UNION SELECT * FROM thegioididong UNION SELECT * FROM lazada) A WHERE newimage1 IS NULL or newimage1 = ''";
		// $sql = $db->prepare($sql);
		// $sql->execute();
	}

	/* Function that get the list of all products images */
	function get_images() {
		// $db = $this->dbcat;	

		// /* Delete products without images from the database */
		// $sql = "SELECT id, website, newimage1, newimage2, newimage3, newimage4, newimage5, newimage6, newimage7 FROM (SELECT * FROM dienmay UNION SELECT * FROM dienmaycholon UNION SELECT * FROM dienmaythienhoa UNION SELECT * FROM nguyenkim UNION SELECT * FROM thegioididong UNION SELECT * FROM lazada) A ";
		// $sql = $db->prepare($sql);
		// $sql->execute();
		// $result = $sql->fetchAll(PDO::FETCH_ASSOC); 

		// return $result;
	}

	/* Function that checks whether a produc from specific website with specific id has the specific thumbnail or not */
	function check_thumbnail_existed($id, $website, $order) {
		$db = $this->dbcat;

		/* Check whether the thumbnail exists in the database or not */
		$sql = "SELECT thumbnail".$order." FROM $website WHERE id = $id";
		$sql = $db->prepare($sql);
		$sql->execute();
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);

		/* If the thumbnail exists return true, otherwises return false*/ 
		if (sizeof($result) > 0 && $result[0]["thumbnail".$order] <> "") {
			return true;
		}

		return false;
	}

	/* Function to update the database of the information of a specific thumbnail */	
	public function update_thumbnail($path, $id, $website, $order) {
		$db = $this->dbcat;

		/* Check whether the thumbnail exists in the database or not */
		$sql = "UPDATE $website SET thumbnail".$order." = '".$path."' WHERE id = $id";
		Xlog::record($sql);
		$sql = $db->prepare($sql);
		$sql->execute();
	}
}

class DBBrandModel {
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $dbbrand;
	
	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
		$db = new PDO("mysql:host=".$this->hostname.";dbname=".$this->db, $this->username, $this->password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);		
		$this->dbbrand = $db;
	}
	
	public function addBrand ($brand, $cat, $subcat, $description = "", $image = "")
	{
		/* Prepare database */
		$db = $this->dbbrand;	
		$description = str_replace("'", "", $description);
		
		// Don't add in without enough information
		if (empty($brand) || empty($cat) || empty($description) || empty($image))
		{
			return;
		}

		if (empty($subcat)) 
		{
			$subcat = 0;
		}

		$sql = "SELECT * FROM `brand` WHERE brand = '".$brand."' AND cat1 = ".$cat." AND subcat1 = ".$subcat;
		$sql = $db->prepare($sql);
		$sql->execute();
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);

		// If the brand already exist, update it
		if (sizeof($result) != 0) {
			$sql = "UPDATE `brand` SET description = '".$description."' AND newimage1 = '".$image."' WHERE brand = '".$brand."' AND cat1 = ".$cat." AND subcat1 = ".$subcat;
		}
		// If the brand doesn't exist, insert new 
		else
		{
			$sql = "INSERT INTO `brand` (brand, subcat1, cat1, description, newimage1) VALUES ('".$brand."', ".$subcat.", ".$cat.", '".$description."', '".$image."')";
		}
		echo $sql;

		$sql = $db->prepare($sql);
		$sql->execute();
	}

	public function addModel ($model, $brand, $cat, $subcat, $description = "", $image = "")
	{
		/* Prepare database */
		$db = $this->dbbrand;	
		$description = str_replace("'", "", $description);

		// Don't add in without enough information
		if (empty($brand) || empty($cat))
		{
			return;
		}

		if (empty($subcat))
		{
			$subcat = 0;
		}

		$model_list = explode("///", $model);

		for ($i = 0 ; $i < sizeof($model_list); ++$i)
		{
			$model = trim($model_list[$i]);

			$sql = "SELECT * FROM `model` WHERE model = '".$model."' AND brand = '".$brand."' AND cat1 = ".$cat." AND subcat1 = ".$subcat;
			$sql = $db->prepare($sql);
			$sql->execute();
			$result = $sql->fetchAll(PDO::FETCH_ASSOC);

			// If the brand already exist, update it
			if (sizeof($result) != 0) {
				$sql = "UPDATE `model` SET description = '".$description."' AND newimage1 = '".$image."' WHERE model = '".$model."' AND brand = '".$brand."' AND cat1 = ".$cat." AND subcat1 = ".$subcat;
			}
			// If the brand doesn't exist, insert new 
			else
			{
				$sql = "INSERT INTO `model` (model, brand, subcat1, cat1, description, newimage1) VALUES ('".$model."', '".$brand."', ".$subcat.", ".$cat.", '".$description."', '".$image."')";
			}
			
			$sql = $db->prepare($sql);
			$sql->execute();
		}
	}
	
	public function getBrand($cat, $subcat) 
	{
		/* Prepare database */
		$db = $this->dbbrand;	

		/* Insert the new comment */
		$sql = "SELECT * FROM brand WHERE cat1 = ".$cat." AND subcat1 = ".$subcat;
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}

	public function getModel($cat, $subcat, $brand) 
	{
		/* Prepare database */
		$db = $this->dbbrand;	

		/* Insert the new comment */
		$sql = "SELECT * FROM model WHERE cat1 = ".$cat." AND subcat1 = ".$subcat." AND brand = '".$brand."'";
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}

	public function getModelId($model_id) {
		/* Prepare database */
		$db = $this->dbbrand;	

		/* Insert the new comment */
		$sql = "SELECT DISTINCT * FROM model A, (SELECT id as brand_id, newimage1 As brand_image, brand as brand_name FROM brand) B WHERE id = $model_id AND brand = brand_id";
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Return the array of results */
		return $sql->fetchAll(PDO::FETCH_ASSOC); 
	}

	public function get_lowest_price_of_model_id($model_id, $num_product = -1) {
		/* Prepare database */
		$db = $this->dbbrand;	

		/* Insert the new comment */
		$sql = "SELECT MIN(price) AS price FROM active_product WHERE model = ".$model_id;

		if ($num_product != -1) 
		{
			$sql = "SELECT MIN(price) AS price FROM (SELECT * FROM active_product WHERE model = ".$model_id. " ORDER BY PRICE ASC LIMIT 0, 10) A";
		}

		$sql = $db->prepare($sql);
		$sql->execute();
		$best_price = $sql->fetchAll(PDO::FETCH_ASSOC); 

		if (sizeof($best_price) <= 0) 
		{
			return 0;
		}

		/* Return the array of results */
		return $best_price[0]["price"];
	}

	public function get_highest_price_of_model_id($model_id, $num_product = -1) {
		/* Prepare database */
		$db = $this->dbbrand;	

		/* Insert the new comment */
		$sql = "SELECT MAX(price) AS price FROM active_product WHERE model = ".$model_id;

		if ($num_product != -1) 
		{
			$sql = "SELECT MAX(price) AS price FROM (SELECT * FROM active_product WHERE model = ".$model_id. " ORDER BY PRICE ASC LIMIT 0, 10) A";
		}
		
		$sql = $db->prepare($sql);
		$sql->execute();
		$best_price = $sql->fetchAll(PDO::FETCH_ASSOC); 

		if (sizeof($best_price) <= 0) 
		{
			return 0;
		}
		/* Return the array of results */
		return $best_price[0]["price"];
	}

	public function increment_product_numclick($id)
	{
		$db = $this->dbbrand;

		$sql = "UPDATE `model` SET numclick = numclick+1 WHERE id = $id";
		/* Execute the select statement */
		$sql = $db->prepare($sql);
		$sql->execute();
	}
}

class DBSlider {
	var $hostname;
	var $username;
	var $password;
	var $db;

	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
	}
	
	public function add ($id, $url, $image, $imagealt, $position, $numclick, $start, $end){
		
		$con = mysql_connect($this->hostname, $this->username, $this->password);	
		mysql_select_db($this->db, $con);
		
		$result = mysql_query("SELECT * FROM slider WHERE id =$id");
		$sql = "";
		if (mysql_num_rows($result)) {
			$sql = "UPDATE slider SET url = '$url', image = '$image', imagealt = '$imagealt', position = $position, numclick = $numclick, start = '$start', end = '$end'WHERE id = $id";
		} else {
			$sql = "INSERT INTO slider VALUES ($id, '$url', '$image', '$imagealt', $position, $numclick, '$start', '$end')";
		}
		mysql_query($sql);
	}
	
	public function get($id) {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM slider WHERE id = $id");
		if ($result != null && mysql_num_rows($result) != 0) {
			$result = mysql_fetch_row($result);
			mysql_close($con);
			return $result; 
		}

		return null;
	}

	public function getStartTime($id) {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT start FROM slider WHERE id = $id");
		$result = mysql_fetch_row($result);
		$result = $result[0];
		mysql_close($con);
		return $result;
	}
}

class DBSliderHistory {
	var $hostname;
	var $username;
	var $password;
	var $db;

	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
	}
	
	public function add ($id, $url, $image, $imagealt, $position, $numclick, $start, $end){
		$con = mysql_connect($this->hostname, $this->username, $this->password);	
		mysql_select_db($this->db, $con);
		$sql = "INSERT INTO sliderhistory VALUES ($id, '$url', '$image', $position, $numclick, '$start', '$end')";
		$result = mysql_query("SELECT * FROM sliderhistory WHERE id =$id");
		$sql = "";
		if (mysql_num_rows($result)) {
			$sql = "UPDATE sliderhistory SET url = '$url', image = '$image', imagealt = '$imagealt', position = $position, numclick = $numclick, start = '$start', end = '$end'WHERE id = $id";
		} else {
			$sql = "INSERT INTO sliderhistory VALUES ($id, '$url', '$image', '$imagealt', $position, $numclick, '$start', '$end')";
		}
		mysql_query($sql);
	}
	
	public function get() {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM sliderhistory");
		mysql_close($con);
		return $result; 
	}

	public function getNumber() {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT id FROM sliderhistory ORDER BY id DESC");
		if ($result) {
			$result = mysql_fetch_row($result);
			$result = $result[0];
			mysql_close($con);
			return $result;
		} else {
			return 0;
		} 
	}
}

class DBNewProducts {
	var $hostname;
	var $username;
	var $password;
	var $db;

	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
	}
	
	public function add ($id, $url, $image, $alt, $position, $description, $price, $numclick, $start, $end){
		$con = mysql_connect($this->hostname, $this->username, $this->password);	
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM newproducts WHERE id =$id");
		$sql = "";
		if (mysql_num_rows($result)) {
			$sql = "UPDATE newproducts SET url = '$url', image = '$image', imagealt = '$alt', position = $position, description = '$description', price = $price, numclick = $numclick, start = '$start', end = '$end' WHERE id = $id";
		} else {
			$sql = "INSERT INTO newproducts VALUES ($id, '$url', '$image', '$alt', $position, '$description', $price, $numclick, '$start', '$end')";
		}
		mysql_query($sql);
	}
	
	public function get($id) {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM newproducts WHERE id = $id");
		if ($result != null && mysql_num_rows($result) != 0) {
			$result = mysql_fetch_row($result);
			mysql_close($con);
			return $result; 
		}

		return null;
	}

	public function getStartTime($id) {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT start FROM newproducts WHERE id = $id");
		$result = mysql_fetch_row($result);
		$result = $result[0];
		mysql_close($con);
		return $result;
	}
}

class DBNewProductsHistory {
	var $hostname;
	var $username;
	var $password;
	var $db;

	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
	}
	
	public function add ($id, $url, $image, $alt, $position, $description, $price, $numclick, $start, $end){
		$con = mysql_connect($this->hostname, $this->username, $this->password);	
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM newproductshistory WHERE id =$id");
		$sql = "";
		if (mysql_num_rows($result)) {
			$sql = "UPDATE newproductshistory SET url = '$url', image = '$image', imagealt = '$alt', position = $position, description = '$description', price = $price, numclick = $numclick, start = '$start', end = '$end'WHERE id = $id";
		} else {
			$sql = "INSERT INTO newproductshistory VALUES ($id, '$url', '$image', '$alt', $position, '$description', $price, $numclick, '$start', '$end')";
		}
		mysql_query($sql);
	}
	
	public function get() {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM newproductshistory");
		mysql_close($con);
		return $result; 
	}

	public function getNumber() {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT id FROM newproductshistory ORDER BY id DESC");
		if ($result) {
			$result = mysql_fetch_row($result);
			$result = $result[0];
			mysql_close($con);
			return $result;
		} else {
			return 0;
		} 
	}
}

class DBHotDeals {
	var $hostname;
	var $username;
	var $password;
	var $db;

	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
	}
	
	public function add ($id, $url, $image, $alt, $position, $description, $price, $numclick, $start, $end){
		$con = mysql_connect($this->hostname, $this->username, $this->password);	
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM hotdeals WHERE id =$id");
		$sql = "";
		if (mysql_num_rows($result)) {
			$sql = "UPDATE hotdeals SET url = '$url', image = '$image', imagealt = '$alt', position = $position, description = '$description', price = $price, numclick = $numclick, start = '$start', end = '$end' WHERE id = $id";
		} else {
			$sql = "INSERT INTO hotdeals VALUES ($id, '$url', '$image', '$alt', $position, '$description', $price, $numclick, '$start', '$end')";
		}
		mysql_query($sql);
	}
	
	public function get($id) {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM hotdeals WHERE id = $id");
		if ($result != null && mysql_num_rows($result) != 0) {
			$result = mysql_fetch_row($result);
			mysql_close($con);
			return $result; 
		}

		return null;
	}

	public function get_total_hotdeal() {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT COUNT(*) FROM hotdeals");
		$result = mysql_fetch_row($result);
		$result = $result[0];
		mysql_close($con);
		return $result;
	}

	public function getStartTime($id) {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT start FROM hotdeals WHERE id = $id");
		$result = mysql_fetch_row($result);
		$result = $result[0];
		mysql_close($con);
		return $result;
	}
}

class DBHotDealsHistory {
	var $hostname;
	var $username;
	var $password;
	var $db;

	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
	}
	
	public function add ($id, $url, $image, $alt, $position, $description, $numclick, $start, $end){
		$con = mysql_connect($this->hostname, $this->username, $this->password);	
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM hotdealshistory WHERE id =$id");
		$sql = "";
		if (mysql_num_rows($result)) {
			$sql = "UPDATE hotdealshistory SET url = '$url', image = '$image', imagealt = '$alt', position = $position, description = '$description', numclick = $numclick, start = '$start', end = '$end'WHERE id = $id";
		} else {
			$sql = "INSERT INTO hotdealshistory VALUES ($id, '$url', '$image', '$alt', $position, '$description', $numclick, '$start', '$end')";
		}
		mysql_query($sql);
	}
	
	public function get() {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM hotdealshistory");
		mysql_close($con);
		return $result; 
	}

	public function getNumber() {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT id FROM hotdealshistory ORDER BY id DESC");
		if ($result) {
			$result = mysql_fetch_row($result);
			$result = $result[0];
			mysql_close($con);
			return $result;
		} else {
			return 0;
		} 
	}
}

class DBProduct {
	var $hostname;
	var $username;
	var $password;
	var $db;

	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
	}

	function add($id, $name, $cateid, $source, $link, $image, $price, $specialprice, $pricestatus, $rating, $numreviews, $description, $numclick, $lastupdate, $status) {
		$con = mysql_connect($this->hostname, $this->username, $this->password);	
		mysql_select_db($this->db, $con);
		$sql = "INSERT INTO product VALUES ($id, '$name', $cateid, '$source', '$link', '$image', $price, $specialprice, '$pricestatus', $rating, $numreviews, '$description', $numclick, '$lastupdate', '$status')";
		mysql_query($sql);
	}
}

class DBUser {
	var $hostname;
	var $username;
	var $password;
	var $db;

	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
	}

	function add($id, $username, $password, $email, $confirmcode, $name) {
		$con = mysql_connect($this->hostname, $this->username, $this->password);	
		mysql_select_db($this->db, $con);
		$sql = "INSERT INTO users VALUES ($id, '$username', '$password', '$email', '$confirmcode', '$name')";
		mysql_query($sql);
	}

	function get($username, $password) {
		$con = mysql_connect($this->hostname, $this->username, $this->password);	
		mysql_select_db($this->db, $con);
		$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
		$result = mysql_query($sql);
		return $result;
	}
}

class DBSearchProduct {
	var $hostname;
	var $username;
	var $password;
	var $db;

	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
	}

	function get($term, $category, $lower_price, $upper_price, $website, $rating) {
		$con = mysql_connect($this->hostname, $this->username, $this->password);	
		mysql_select_db($this->db, $con);
		$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
		$result = mysql_query($sql);
		return $result;
	}
}

class DBAds {
	var $hostname;
	var $username;
	var $password;
	var $db;

	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
	}
	
	public function add ($id, $url, $image, $alt, $position, $description, $price, $numclick, $start, $end){
		$con = mysql_connect($this->hostname, $this->username, $this->password);	
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM ads WHERE id =$id");
		$sql = "";
		if (mysql_num_rows($result)) {
			$sql = "UPDATE ads SET url = '$url', image = '$image', imagealt = '$alt', position = $position, description = '$description', price = $price, numclick = $numclick, start = '$start', end = '$end' WHERE id = $id";
		} else {
			$sql = "INSERT INTO ads VALUES ($id, '$url', '$image', '$alt', $position, '$description', $price, $numclick, '$start', '$end')";
		}
		mysql_query($sql);
	}
	
	public function get($id) {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM ads WHERE id = $id");
		if ($result != null && mysql_num_rows($result) != 0) {
			$result = mysql_fetch_row($result);
			mysql_close($con);
			return $result; 
		}

		return null;
	}

	public function getStartTime($id) {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT start FROM ads WHERE id = $id");
		$result = mysql_fetch_row($result);
		$result = $result[0];
		mysql_close($con);
		return $result;
	}
}

class DBAdsHistory {
	var $hostname;
	var $username;
	var $password;
	var $db;

	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
	}
	
	public function add ($id, $url, $image, $alt, $position, $description, $price, $numclick, $start, $end){
		$con = mysql_connect($this->hostname, $this->username, $this->password);	
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM adshistory WHERE id =$id");
		$sql = "";
		if (mysql_num_rows($result)) {
			$sql = "UPDATE adshistory SET url = '$url', image = '$image', imagealt = '$alt', position = $position, description = '$description', price = $price, numclick = $numclick, start = '$start', end = '$end'WHERE id = $id";
		} else {
			$sql = "INSERT INTO adshistory VALUES ($id, '$url', '$image', '$alt', $position, '$description', $price, $numclick, '$start', '$end')";
		}
		mysql_query($sql);
	}


	public function get() {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT * FROM adshistory");
		mysql_close($con);
		return $result; 
	}

	public function getNumber() {
		$con = mysql_connect($this->hostname, $this->username, $this->password);			
		mysql_select_db($this->db, $con);
		$result = mysql_query("SELECT id FROM adshistory ORDER BY id DESC");
		if ($result) {
			$result = mysql_fetch_row($result);
			$result = $result[0];
			mysql_close($con);
			return $result;
		} else {
			return 0;
		} 
	}
}

class DBAllProduct {
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $dball;
	var $brand;
	var $model;

	function SetConfig($host, $user, $pass, $db)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;

		$db = new PDO("mysql:host=".$this->hostname.";dbname=".$this->db, $this->username, $this->password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);		
		$this->dball = $db;

		// Get all the brands info
		$sql = "SELECT * FROM brand";
		$sql = $db->prepare($sql);
		$sql->execute();
		$this->brand = $sql->fetchAll(PDO::FETCH_ASSOC); 

		// Get all the brands info
		$sql = "SELECT * FROM model";
		$sql = $db->prepare($sql);
		$sql->execute();
		$this->model = $sql->fetchAll(PDO::FETCH_ASSOC); 
	}

	function get_image($page) {
		/* Prepare database */
		$db = $this->dball;

		$lowerpage = ($page-1)*500;
		$sql = "SELECT * FROM 
		(SELECT id, website, newimage1, cat1, price FROM active_product
			UNION 
			SELECT id, website, newimage1, cat1, price FROM expire_product
			) A WHERE newimage1 IS NOT NULL LIMIT $lowerpage,500";
$sql = $db->prepare($sql);
$sql->execute();

$result = $sql->fetchAll(PDO::FETCH_ASSOC); 
return $result; 
}


public function get_product($website, $id)
{
	$db = $this->dball;

	$sql = "SELECT * FROM `site_".$website."` WHERE id = $id";
	/* Execute the select statement */
	$sql = $db->prepare($sql);
	$sql->execute();

	/* Put the result in the internal data strucutre (array) */
	$result = $sql->fetchAll(PDO::FETCH_ASSOC);
	return $result; 
}

public function increment_product_numclick($website, $id)
{
	$db = $this->dball;

	$sql = "UPDATE `site_".$website."` SET numclick = numclick+1 WHERE id = $id";
	/* Execute the select statement */
	$sql = $db->prepare($sql);
	$sql->execute();
}

public function get_suggested_product($name, $cat)
{
	$db = $this->dball;

	$words = create_keyword_name($name);
	$size = 3;
	if (sizeof($words) < 3) {
		$size = sizeof($words);
	}

	$concat_sql2 = " ";
	$concat_sql = "";
	if ($cat != 0) {
		$concat_sql = " A.cat1 = $cat ";
		$concat_sql2 = " WHERE cat1 = $cat ";
	} else {
		$concat_sql = " A.cat1 > 0 ";
	}

	if ($size == 1) {
		$concat_sql .= " AND A.name LIKE '%".$words[0]."%'";
	} else if ($size == 2) {
		$concat_sql .= " AND (A.name LIKE '%".$words[0]."%' OR A.name LIKE '%".$words[1]."%')";
	} else if ($size == 3) {
		$concat_sql .= " AND (A.name LIKE '%".$words[0]."%' OR A.name LIKE '%".$words[1]."%' OR A.name LIKE '%".$words[2]."%')";
	}

	/* Prepare the statement */
	$sql = "(SELECT id, website, name, newimage1, price, cat1, subcat1 FROM active_product A WHERE".$concat_sql. " AND A.newimage1 IS NOT NULL AND A .price > 0 LIMIT 0, 10) UNION (SELECT id, website, name, newimage1, price, cat1, subcat1 FROM site_lazada".$concat_sql2."LIMIT 0, 10)";
	
	$sql = $db->prepare($sql);
	$sql->execute();
	return $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function get_model($brand, $model, $cat, $subcat)
{
	$db = $this->dball;
	$sql = "SELECT DISTINCT A.id,name,no_accent_name,price,website,newimage1, newimage2,newimage3,newimage4,newimage5,thumbnail1,link,cat1,subcat1,description,description2, video1 FROM active_product A WHERE A.brand = ".$brand." AND A.model = ".$model." AND A.cat1 = ".$cat." AND A.subcat1 = ".$subcat." AND A.newimage1 IS NOT NULL ORDER BY price ASC";
	$sql = $db->prepare($sql);
	$sql->execute();

	return $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function get_similar_product($brand, $model, $cat, $subcat)
{
	$db = $this->dball;

	$sql = "";
	if (!empty($brand) && !empty($model)) {
		$sql = "SELECT DISTINCT A.id,name,no_accent_name,price,website,newimage1,newimage2,newimage3,newimage4,newimage5,thumbnail1,link,cat1,subcat1,description,description2,video1,comment FROM active_product A WHERE A.brand = '".$brand."' AND A.model = '".$model."' AND A.cat1 = ".$cat." AND A.subcat1 = ".$subcat." AND A.newimage1 IS NOT NULL ORDER BY price ASC";
	} else if (!empty($brand)) {
		$sql = "SELECT DISTINCT A.id,name,no_accent_name,price,website,newimage1,newimage2,newimage3,newimage4,newimage5,thumbnail1,link,cat1,subcat1,description,description2,video1,comment FROM active_product A WHERE A.brand = '".$brand."' AND A.cat1 = ".$cat." AND A.subcat1 = ".$subcat." AND A.model IS NULL AND A.newimage1 IS NOT NULL ORDER BY price ASC";
	} else {
		$sql = "SELECT DISTINCT A.id,name,no_accent_name,price,website,newimage1,newimage2,newimage3,newimage4,newimage5,thumbnail1,link,cat1,subcat1,description,description2,video1,comment FROM active_product A WHERE A.cat1 = ".$cat." AND A.subcat1 = ".$subcat." AND A.model IS NULL AND A.brand IS NULL AND A.newimage1 IS NOT NULL ORDER BY price ASC";
	}
	$sql = $db->prepare($sql);
	$sql->execute();

	return $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function get_model_product($term, $category, $subcategory, $website, $lower_price, $upper_price, $order, $website_list, $page, $outofstock)
{
	$db = $this->dball;

	$sql = $this->construct_sql($term, $category, $subcategory, $website, $lower_price, $upper_price, $order, $website_list, $page, $outofstock, true);

	/* Execute the select statement */
	$sql = $db->prepare($sql);
	$sql->execute();
	$model = $sql->fetchAll(PDO::FETCH_ASSOC);

	// Return empty list if there is no model
	if (sizeof($model) == 0) 
	{
		return array();
	}
	else
	{
		for ($i = 0; $i < sizeof($model); ++$i)
		{
			if (empty($model[$i]["model"]))
			{
				return array();
			}
		}
	}

	// Get model we need
	$sql = "SELECT DISTINCT * FROM model A, (SELECT id AS brand_id, newimage1 AS brand_image FROM brand) B WHERE (";
	for ($i = 0; $i < sizeof($model)-1; ++$i)
	{
		$model_id = $model[$i]["model"];
		$sql .= " id = ".$model_id." OR";
	}
	$sql .= " id = ".$model[sizeof($model)-1]["model"].") AND A.brand = B.brand_id";
	
	/* Execute the select statement */
	$sql = $db->prepare($sql);
	$sql->execute();	

	return $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function get_search_product($term, $category, $subcategory, $website, $lower_price, $upper_price, $order, $website_list, $page, $outofstock)
{
	$db = $this->dball;

	$sql = $this->construct_sql($term, $category, $subcategory, $website, $lower_price, $upper_price, $order, $website_list, $page, $outofstock);
	/* Execute the select statement */
	$sql = $db->prepare($sql);
	$sql->execute();

	return $sql->fetchAll(PDO::FETCH_ASSOC);
}

function construct_sql($term, $category, $subcategory, $website, $lower_price, $upper_price, $order, $website_list, $page, $outofstock, $model = false) {

	$sql = "";

	if ($model)
	{
		$sql = "SELECT DISTINCT model FROM  product_category B, product_subcat C, ";
	}
	else
	{
		$sql = "SELECT DISTINCT A.id id, link, name, website, cat1, subcat1, price, description, newimage1, video1 FROM  product_category B, product_subcat C, ";	
	}

	$table = $this->select_table($category, $outofstock);
	if (trim($table) == "") $table = "active_product";

	$sql .= $table." A WHERE A.cat1 = B.id AND ((A.subcat1 = 0 AND C.id > 39) OR A.subcat1 = C.id) AND A.newimage1 IS NOT NULL";

	if ($table != "active_product" && $table != "expire_product") {
		if ($outofstock == "true") {
			$sql .= " AND price = 0";
		} else $sql .= " AND price > 0";
	}

	/* The search term */
	if ($term <> "") {
		$term = str_replace("-", " ", remove_sign($term));
		$term = " ".$term." ";
		$term = str_replace(" ", "%", $term);
		$sql .= " AND (A.no_accent_name LIKE '$term' OR C.subcat LIKE '$term')";
	}

	$sql .= $this->add_criteria_sql($category, $subcategory, $website, $lower_price, $upper_price, $order, $model);

	return $sql;
}

function select_table($cat, $outofstock) {
	if (trim($cat) != "") {
		$catname = get_catname($cat);
		if (trim($catname) != "") return $catname;
	}

	if ($outofstock == "false") {
		return "active_product";
	} else if ($outofstock == "true") {
		return "expire_product";
	}

	return "";
}

function construct_sql_only_ascii($term, $category, $subcategory, $website, $lower_price, $upper_price, $order, $website_list, $page, $outofstock) {

	$terms = create_keyword_name($term);
	$term = implode(" ", $terms); 

	return $this->construct_sql($term, $category, $subcategory, $website, $lower_price, $upper_price, $order, $website_list, $page, $outofstock);
}

function add_criteria_sql($category, $subcategory, $website, $lower_price, $upper_price, $order, $model = false) {
	$sql = "";

	/* The search category */
	if ($category <> "") {
		$sql .= " AND B.category LIKE '$category'";
	}

	/* The search subcategory */
	if ($subcategory <> "") {
		$sql .= " AND C.subcat LIKE '$subcategory'";
	}

	/* The search website */
	if ($website <> "") {
		$sql .= " AND A.website LIKE '$website'";
	}

	/* The search lower price */
	if ($lower_price <> "" && is_numeric($lower_price)) {
		$sql .= " AND A.price >= $lower_price";
	} 

	/* The search upper price */
	if ($upper_price <> "" && is_numeric($upper_price)) {
		$sql .= " AND A.price <= $upper_price";
	} 

	if ($model)
	{
		$sql .= " AND A.brand IS NOT NULL AND A.model IS NOT NULL";
	}
	else
	{
		$sql .= " AND A.model IS NULL";
	}

	/* Order the result */
	if ($order == "" || !is_numeric($order) || $order > 4 || $order < 0) {
		$order = 0;
	}

	$this->order = $order;
	if ($order == 0) {
		$sql .= " ORDER BY FIELD(cat1";
			$catcode = catcode_list_sorted_priority();
			for ($i = 0; $i < sizeof($catcode); $i++) {
				$sql .= ", ".$catcode[$i];
			}
			$sql .= "), A.price ASC";
}
if ($order == 1) {
	$sql .= " ORDER BY A.price";
}
if ($order == 2) {
	$sql .= " ORDER BY A.price DESC";
}
if ($order == 3) {
	$sql .= " ORDER BY A.name";
}
if ($order == 4) {
	$sql .= " ORDER BY A.website";
}

return $sql;
}

function remove_product($id, $website, $cat) {
	/* Prepare database */
	$db = $this->dball;

	/* Insert the remove product into the remove_product list */
	$sql = "INSERT INTO remove_product SELECT * FROM `site_".$website."` WHERE id= $id AND website = '$website'";
	Xlog::record($sql);
	$sql = $db->prepare($sql);
	$sql->execute();
	/* Remove from the website list */
	$sql = "DELETE FROM `site_".$website."` WHERE id = $id AND website = '$website'";
	Xlog::record($sql);
	$sql = $db->prepare($sql);
	$sql->execute();
	/* Remove from the active or expire product list */
	$sql = "DELETE FROM active_product WHERE id = $id AND website = '$website'";
	Xlog::record($sql);
	$sql = $db->prepare($sql);
	$sql->execute();
	$sql = "DELETE FROM expire_product WHERE id = $id AND website = '$website'";
	Xlog::record($sql);
	$sql = $db->prepare($sql);
	$sql->execute();
	/* Remove from the respetive category list */
	$sql = "DELETE FROM $cat WHERE id = $id AND website = '$website'";
	Xlog::record($sql);
	$sql = $db->prepare($sql);
	$sql->execute();
}

function update_all_price() {
	$this->update_category("accessories");
	$this->update_category("audio");
	$this->update_category("camera");
	$this->update_category("computer");
	$this->update_category("mobiles");
	$this->update_category("tablets");
	$this->update_category("tv");
	$this->update_category("female_fashion");
	$this->update_category("male_fashion");
	$this->update_active_prodcut();
	$this->update_expire_prodcut();
}

function update_accessories() {
	$this->update_category("accessories");
	$this->update_active_prodcut();
	$this->update_expire_prodcut();
}

function update_audio() {
	$this->update_category("audio");
	$this->update_active_prodcut();
	$this->update_expire_prodcut();
}

function update_camera() {
	$this->update_category("camera");
	$this->update_active_prodcut();
	$this->update_expire_prodcut();
}

function update_computer() {
	$this->update_category("computer");
	$this->update_active_prodcut();
	$this->update_expire_prodcut();
}

function update_mobiles() {
	$this->update_category("mobiles");
	$this->update_active_prodcut();
	$this->update_expire_prodcut();
}

function update_tablets() {
	$this->update_category("tablets");
	$this->update_active_prodcut();
	$this->update_expire_prodcut();
}

function update_tv() {
	$this->update_category("tv");
	$this->update_active_prodcut();
	$this->update_expire_prodcut();
}

function update_male_fashion() {
	$this->update_category("male_fashion");
	$this->update_active_prodcut();
	$this->update_expire_prodcut();
}

function update_female_fashion() {
	$this->update_category("female_fashion");
	$this->update_active_prodcut();
	$this->update_expire_prodcut();
}

function update_category($cat) {
	/* Prepare database */
	$db = $this->dball;	

	$catcode = get_catcode($cat);
	$website_list = website_list();

	if ($catcode > 0) {
		/* Truncate category table */
		$sql = "TRUNCATE TABLE $cat";
		Xlog::record($sql);
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Insert new products into table */
		$sql = "INSERT INTO $cat SELECT * FROM (";

			for ($i = 0; $i < sizeof($website_list)-1; $i++) {
				$sql .= "SELECT * FROM `site_".$website_list[$i]."` UNION ";
			}
			$sql .= "SELECT * FROM `site_".$website_list[sizeof($website_list)-1];
			$sql .= "`) A WHERE cat1=$catcode";

Xlog::record($sql);
$sql = $db->prepare($sql);
$sql->execute();
}
}

function update_active_prodcut() {
	/* Prepare database */
	$db = $this->dball;	

	$website_list = website_list();

	/* Truncate category table */
	$sql = "TRUNCATE TABLE active_product";
	Xlog::record($sql);
	$sql = $db->prepare($sql);
	$sql->execute();

	/* Insert new products into table */
	$sql = "INSERT INTO active_product SELECT * FROM (";

		for ($i = 0; $i < sizeof($website_list)-1; $i++) {
			$sql .= "SELECT * FROM `site_".$website_list[$i]."` UNION ";
		}
		$sql .= "SELECT * FROM `site_".$website_list[sizeof($website_list)-1];
		$sql .= "`) A WHERE price > 0";

Xlog::record($sql);
$sql = $db->prepare($sql);
$sql->execute();
}

function update_expire_prodcut() {
	/* Prepare database */
	$db = $this->dball;	

	$website_list = website_list();

	/* Truncate category table */
	$sql = "TRUNCATE TABLE expire_product";
	Xlog::record($sql);
	$sql = $db->prepare($sql);
	$sql->execute();

	/* Insert new products into table */
	$sql = "INSERT INTO expire_product SELECT * FROM (";

		for ($i = 0; $i < sizeof($website_list)-1; $i++) {
			$sql .= "SELECT * FROM `site_".$website_list[$i]."` UNION ";
		}
		$sql .= "SELECT * FROM `site_".$website_list[sizeof($website_list)-1];
		$sql .= "`) A WHERE price = 0";

Xlog::record($sql);
$sql = $db->prepare($sql);
$sql->execute();
}

function create_no_accent_name($website, $page) {
	/* Prepare database */
	$db = $this->dball;		

	/* Find product in the website table which doesn't have a */
	$sql = "SELECT id, name `site_".$website."` WHERE no_accent_name IS NULL LIMIT 0, 50";
	$sql = $db->prepare($sql);
	$sql->execute();
	/* Put the result in the internal data strucutre (array) */
	$resultArray = $sql->fetchAll(PDO::FETCH_ASSOC);

	$count = 0;

	for ($i = 0; $i < sizeof($resultArray); $i++) {
		$count++;
		$row = $resultArray[$i];

		$name = $row["name"];
		$id = $row["id"];

		$no_accent_name = str_replace("-", " ", remove_sign($name));

		$sql = "UPDATE `site_".$website."` SET no_accent_name = '$no_accent_name' WHERE id = $id";
		$sql = $db->prepare($sql);
		$sql->execute();
	}

	return $count;
}

function create_no_accent_name_full($website, $page) {
	/* Prepare database */
	$db = $this->dball;		

	/* Find product in the website table which doesn't have a */
	$lowerpage = ($page-1)*50;
	$sql = "SELECT id, name FROM `site_".$website."` LIMIT $lowerpage, 50";
	$sql = $db->prepare($sql);
	$sql->execute();
	/* Put the result in the internal data strucutre (array) */
	$resultArray = $sql->fetchAll(PDO::FETCH_ASSOC);

	$count = 0;

	for ($i = 0; $i < sizeof($resultArray); $i++) {
		$count++;
		$row = $resultArray[$i];

		$name = $row["name"];
		$id = $row["id"];

		$no_accent_name = str_replace("-", " ", remove_sign($name));

		$sql = "UPDATE `site_".$website."` SET no_accent_name = '$no_accent_name' WHERE id = $id";
		$sql = $db->prepare($sql);
		$sql->execute();
	}

	return $count;
}

function update_brand_product_given_list($db, $website, $resultArray, $full_list_flag) {
	$count = 0;
	$brand_list = array();
	$previous_cat = 0;
	$previous_subcat = 0;

	for ($i = 0; $i < sizeof($resultArray); $i++) {
		$count++;
		$row = $resultArray[$i];

		if (!$full_list_flag && !empty($row["brand"])) {
			continue;
		}

		//$keyword_name = create_keyword_name($row["no_accent_name"]);
		$id = $row["id"];

		/* Get the list of brands from the category and subcategory */
		if ($previous_cat != $row["cat1"] || $previous_subcat != $row["subcat1"])
		{
			//$brand_list = important_brand_word($row["cat1"], $row["subcat1"]);
			$brand = $this->brand;

			// Get out the list of corresponding brands
			$brand_list = array();
			for ($j = 0; $j < sizeof($brand); ++$j)
			{
				if ($brand[$j]["subcat1"] == $row["subcat1"] && $brand[$j]["cat1"] == $row["cat1"]) 
				{
					$brand_list[] = $brand[$j];
				}
			}
			
			// Save previous cat & subcat to save compute time if matching
			$previous_cat = $row["cat1"];
			$previous_subcat = $row["subcat1"];
		}

		/* Get the name of the brand of the product */
		//$brand = check_product_brand($keyword_name, $brand_list);
		$brand = check_product_brand($row["no_accent_name"], $brand_list);

		$sql = "";
		if (!empty($brand)) {
			$sql = "UPDATE `site_".$website."` SET brand = '$brand' WHERE id = $id";
		}
		else
		{
			$sql = "UPDATE `site_".$website."` SET brand = NULL WHERE id = $id";
		}

		$sql = $db->prepare($sql);
		$sql->execute();
	}
	return $count;
}

function update_brand_product($website, $page) {
	/* Prepare database */
	$db = $this->dball;		

	/* Find product in the website table which doesn't have a */
	$lowerpage = ($page-1)*500;
	$sql = "SELECT id, no_accent_name, cat1, subcat1 FROM `site_".$website."` LIMIT $lowerpage, 500";
	$sql = $db->prepare($sql);
	$sql->execute();
	/* Put the result in the internal data strucutre (array) */
	$resultArray = $sql->fetchAll(PDO::FETCH_ASSOC);

	$count = $this->update_brand_product_given_list($db, $website, $resultArray, false);

	return $count;
}

function update_brand_product_full($website, $page) {
	/* Prepare database */
	$db = $this->dball;		

	/* Find product in the website table which doesn't have a */
	$lowerpage = ($page-1)*500;
	$sql = "SELECT id, no_accent_name, cat1, subcat1 FROM `site_".$website."` LIMIT $lowerpage, 500";
	$sql = $db->prepare($sql);
	$sql->execute();
	/* Put the result in the internal data strucutre (array) */
	$resultArray = $sql->fetchAll(PDO::FETCH_ASSOC);

	$count = $this->update_brand_product_given_list($db, $website, $resultArray, true);

	return $count;
}

function update_model_product_given_list($db, $website, $resultArray, $full_list_flag) {
	$count = 0;
	$model_list = array();
	$previous_cat = 0;
	$previous_subcat = 0;
	$previous_brand = "";
	for ($i = 0; $i < sizeof($resultArray); $i++) {
		$count++;
		$row = $resultArray[$i];

		if (!$full_list_flag && !empty($row["model"])) {
			continue;
		}

		//$keyword_name = create_keyword_name($row["no_accent_name"]);
		$id = $row["id"];

		/* Get the list of brands from the category and subcategory */
		if ($previous_cat != $row["cat1"] || $previous_subcat != $row["subcat1"] || $previous_brand != $row["brand"])
		{
			//$model_list = model_word($row["cat1"], $row["subcat1"], $row["brand"]);
			$model = $this->model;

			// Get out the list of corresponding brands
			$model_list = array();
			for ($j = 0; $j < sizeof($model); ++$j)
			{
				if ($model[$j]["subcat1"] == $row["subcat1"] && $model[$j]["cat1"] == $row["cat1"] && $model[$j]["brand"] == $row["brand"]) 
				{
					$model_name_parts = explode("/", $model[$j]["model"]);
					for ($m = 0; $m < sizeof($model_name_parts); ++$m)
					{
						$model_name_parts[$m] = str_replace(";", "fakestring", $model_name_parts[$m]);
						$model_name_parts[$m] = str_replace("-", " ", remove_sign($model_name_parts[$m]));
						$model_name_parts[$m] = str_replace("fakestring", ";", $model_name_parts[$m]);
					}
					$model[$j]["model"] = implode("/",$model_name_parts);

					$model_list[] = $model[$j];

					// if (stripos($model[$j]["model"], "Aigo") !== false)
					// {
					// 	echo $model[$j]["model"];
					// 	return 0;
					// }
				}
			}

			$previous_cat = $row["cat1"];
			$previous_subcat = $row["subcat1"];
			$previous_brand = $row["brand"];
		}
		/* Get the name of the model of the product */
		//$model = check_product_model($keyword_name, $model_list, $row["brand"]); 
		$model = check_product_model($row["no_accent_name"], $model_list, $row["brand"]);

		$sql = "";
		if (!empty($model)) {
			$sql = "UPDATE `site_".$website."` SET model = '$model' WHERE id = $id";
		}
		else
		{
			$sql = "UPDATE `site_".$website."` SET model = NULL WHERE id = $id";
		}

		$sql = $db->prepare($sql);
		$sql->execute();	
	}
	return $count;
}

function update_model_product($website, $page) {
	/* Prepare database */
	$db = $this->dball;		

	$lowerpage = ($page-1)*500;
	$sql = "SELECT id, no_accent_name, cat1, subcat1, brand FROM `site_".$website."` LIMIT $lowerpage, 500";
	$sql = $db->prepare($sql);
	$sql->execute();
	/* Put the result in the internal data strucutre (array) */
	$resultArray = $sql->fetchAll(PDO::FETCH_ASSOC);

	$count = $this->update_model_product_given_list($db, $website, $resultArray, false);

	return $count;
}

function update_model_product_full($website, $page) {
	/* Prepare database */
	$db = $this->dball;		
	$lowerpage = ($page-1)*500;
	$sql = "SELECT id, no_accent_name, cat1, subcat1, brand FROM `site_".$website."` LIMIT $lowerpage, 500";
	$sql = $db->prepare($sql);
	$sql->execute();
	/* Put the result in the internal data strucutre (array) */
	$resultArray = $sql->fetchAll(PDO::FETCH_ASSOC);
	$count = $this->update_model_product_given_list($db, $website, $resultArray, true);

	return $count;
}

function try_find_image_for_model($model_id) {
	/* Prepare database */
	$db = $this->dball;		
	$sql = "SELECT newimage1 FROM `active_product` WHERE model = ".$model_id." LIMIT 0,1";
	$sql = $db->prepare($sql);
	$sql->execute();
	/* Put the result in the internal data strucutre (array) */
	$resultArray = $sql->fetchAll(PDO::FETCH_ASSOC);

	return $resultArray[0]["newimage1"];
}

function try_find_description_for_model($model_id) {
	/* Prepare database */
	$db = $this->dball;		
	$sql = "SELECT description FROM `active_product` WHERE model = ".$model_id." LIMIT 0, 3";
	$sql = $db->prepare($sql);
	$sql->execute();
	/* Put the result in the internal data strucutre (array) */
	$resultArray = $sql->fetchAll(PDO::FETCH_ASSOC);

	$longest_description = $resultArray[0]["description"];

	for ($i = 1; $i < sizeof($resultArray); ++$i)
	{
		if (strlen($longest_description) < strlen($resultArray[$i]["description"]))
		{
			$longest_description = $resultArray[$i]["description"];
		}
	}

	return $longest_description;
}
}

class DBCrawler {
	var $hostname;
	var $username;
	var $password;
	var $db;
	var $website;
	var $dbcrawler;
	var $dbbrand;

	public function SetConfig($host, $user, $pass, $db, $website)
	{
		$this->hostname = $host;
		$this->username = $user;
		$this->password = $pass;
		$this->db = $db;
		$this->website = $website;

		$this->dbbrand = new DBBrandModel();
		$this->dbbrand->SetConfig($host, $user, $pass, $db);

		$db = new PDO("mysql:host=".$this->hostname.";dbname=".$this->db, $this->username, $this->password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);		
		$this->dbcrawler = $db;
	}

	public function add_prepocess_product($category, $link, $name, $price, $image, $comment, $description, $catid, $subcat, $extra_image, $description2 = "")
	{
		Xlog::record("Start adding product $name ($link) to preprocess database for website ".$this->website);		
		
		$db = $this->dbcrawler;		

		$website = $this->website;

		$link2 = str_replace("http://", "http://www.", $link);
		$link3 = str_replace("http://www.", "http://", $link);
		$description = str_replace("'", "", $description);
		$name = str_replace("'", "\"", $name);
		$today = date("d/m/y", time());

		$sql = "SELECT * FROM `preprocess_".$website."` WHERE link ='$link' OR link = '$link2' OR link = '$link3'";
		Xlog::record($sql);

		$sql = $db->prepare($sql);
		$sql->execute();
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);

		if (sizeof($result) > 0) {
			$sql = "UPDATE `preprocess_".$website."` SET price=".$price.", description='".$description."', description2 = '".$description2."', update_price_date='".$today."' WHERE link='".$link."' OR link = '".$link2."' OR link = '".$link3."'";
		} else {
			$sql = "INSERT INTO `preprocess_".$website."` (category, link, name, price, orgimage, newimage, comment, date, description, description2, website, cat1, subcat1, orgimage1, orgimage2, orgimage3, orgimage4, orgimage5, orgimage6, orgimage7,numclick,update_price_date) VALUES ('".$category."', '".$link."', '".$name."', ".$price.", '".$image."', NULL, '".$comment."', '".date("Y-m-d H:i:s")."', '".$description."', '".$description2."', '".$website."', ".$catid.", ".$subcat.", '".$extra_image[1]."', '".$extra_image[2]."', '".$extra_image[3]."', '".$extra_image[4]."', '".$extra_image[5]."', '".$extra_image[6]."', '".$extra_image[7]."', 0, '".$today."')";
		}

		try
		{
			$result = $db->prepare($sql);
			$result->execute();
			$sql = strip_tags($sql);
			$sql = str_replace("<!--", "", $sql);

			// Record to file and echo out
			Xlog::record($sql, true, true, true);
			Xlog::record("<br/>___________________________________________________________________________________________________________________________________________________________<br/>", true, false);
		}
		catch (Exception $e)
		{
			Xlog::record("Potential Duplicate Name $name <br/>", false, true, true);
		}

		// Set the field in the real database that it has an updated price
		$sql = "UPDATE `site_".$website."` SET has_updated_price = 1 WHERE link='$link' OR link = '$link2' OR link = '$link3'";
		
		Xlog::record($sql, true, true, true);
		$result = $db->prepare($sql);
		$result->execute();
	}

	public function add_product($category, $link, $name, $price, $image, $comment, $description, $catid, $subcat, $extra_image, $date, $description2 = "") {

		Xlog::record("Start adding product $name ($link) for website ".$this->website);

		$db = $this->dbcrawler;	
		$website = $this->website;

		if ($this->print_empty_message($category, $link, $name, $price, $description, $catid, $extra_image[1], $subcat, $subcat) == -1)
		{
			Xlog::record("<h2>There missing some field</h2><br/>");
			return;
		}

		$link2 = str_replace("http://", "http://www.", $link);
		$link3 = str_replace("http://www.", "http://", $link);
		$description = str_replace("'", "", $description);
		$description2 = str_replace("'", "", $description2);
		$name = str_replace("'", "\"", $name);

		$sql = "SELECT * FROM `site_".$website."` WHERE link ='$link' OR link = '$link2' OR link = '$link3'";

		Xlog::record($sql);

		$sql = $db->prepare($sql);
		$sql->execute();
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);
		// echo "here<br/>";
		// print_r($result);
		// echo "there<br/>";
		if (sizeof($result) > 0) {
			$sql = "UPDATE `site_".$website."` SET price=".$price.", description='".$description."', description2 = '".$description2."', update_price_date='".$date."', comment = '".$comment."' WHERE link='".$link."' OR link = '".$link2."' OR link = '".$link3."'";
		} else {

			$no_accent_name = str_replace("-", " ", remove_sign($name));

			$keyword_name = create_keyword_name($no_accent_name);
			/* Get the list of brands from the category and subcategory */
			//$brand_list = important_brand_word($catid, $subcat);
			$brand_list = $this->dbbrand->getBrand($catid, $subcat);

			/* Get the name of the brand of the product */
			//$brand = check_product_brand($keyword_name, $brand_list);
			$brand = trim(check_product_brand($no_accent_name, $brand_list));
			/* Get the list of models from the category, subcategory and brand */
			//$model_list = model_word($catid, $subcat, $brand);
			$model_list = $this->dbbrand->getModel($catid, $subcat, $brand);

			/* Get the name of the model of the product */
			//$model = check_product_model($keyword_name, $model_list, $brand);
			$model = trim(check_product_model($no_accent_name, $model_list, $brand));

			if (empty($brand))
			{
				$brand = "NULL";
			}
			else
			{
				$brand = "'".$brand."'";
			}

			if (empty($model))
			{
				$model = "NULL";
			}
			else
			{
				$model = "'".$model."'";
			}
			
			$sql = "INSERT INTO `site_".$website."` (category, link, name, no_accent_name, price, orgimage, newimage, comment, date, description, description2, website, brand, model, cat1, subcat1, orgimage1, orgimage2, orgimage3, orgimage4, orgimage5, orgimage6, orgimage7,numclick,update_price_date) VALUES ('".$category."', '".$link."', '".$name."', '".$no_accent_name."', ".$price.", '".$image."', NULL, '".$comment."', '".date("Y-m-d H:i:s")."', '".$description."', '".$description2."', '".$website."', ".$brand.", ".$model.", ".$catid.", ".$subcat.", '".$extra_image[1]."', '".$extra_image[2]."', '".$extra_image[3]."', '".$extra_image[4]."', '".$extra_image[5]."', '".$extra_image[6]."', '".$extra_image[7]."', 0, '".$date."')";
		}

		try 
		{
			$result = $db->prepare($sql);
			$result->execute();
			$sql = strip_tags($sql);
			$sql = str_replace("<!--", "", $sql);

			// Only record to file, not echo out 
			Xlog::record($sql, false, true, true);
			Xlog::record("<br/>___________________________________________________________________________________________________________________________________________________________<br/>", false, false);
		}
		// Exception like duplicate name
		catch (Exception $e)
		{
			Xlog::record("Potential Duplicate Name $name <br/>", false, true, true);
		}

		// Set the field in the real database that it doesn't have an updated price
		$sql = "UPDATE `site_".$website."` SET has_updated_price = 0 WHERE link='$link' OR link = '$link2' OR link = '$link3'";
		
		Xlog::record($sql, true, true, true);
		$result = $db->prepare($sql);
		$result->execute();
	}

	public function print_empty_message($category, $link, $name, $price, $description, $catid, $extra_image, $subcat, $subcat)
	{
		if (empty($category))
		{
			Xlog::record("<h2>Empty Category</h2>");
			return -1;
		}
		if (empty($link))
		{
			Xlog::record("<h2>Empty Link</h2><br/>");
			return -1;
		}
		if (empty($name))
		{
			Xlog::record("<h2>Empty Name</h2><br/>");
			return -1;
		}
		/*if (empty($price))
		{
			Xlog::record("<h2>Empty Price</h2><br/>");
			return -1;
		}*/
		if (empty($description))
		{
			Xlog::record("<h2>Empty Description</h2><br/>");
			return -1;
		}
		if (empty($extra_image))
		{
			Xlog::record("<h2>Empty extra image</h2><br/>");
			return -1;
		}
		if ((empty($subcat) && $catid != 2) || $catid == 0)
		{
			Xlog::record("<h2>Generate wrong subcat and cat id</h2><br/>");
			return -1;
		}
		return 0;
	}

	public function get_images($category) {
		$db = $this->dbcrawler;	

		$website = $this->website;

		$sql = "SELECT id, orgimage1, orgimage2, orgimage3, orgimage4, orgimage5, newimage1 FROM `site_".$website."` WHERE category = '$category' AND newimage1 IS NULL ";

		$sql = $db->prepare($sql);
		$sql->execute();
		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}

	public function get_images_page($category, $page, $resultPerPage = 5) {
		$db = $this->dbcrawler;	

		$website = $this->website;

		$lowerpage = ($page-1)*5;
		$sql = "SELECT id, orgimage1, orgimage2, orgimage3, orgimage4, orgimage5, newimage1 FROM `site_".$website."` WHERE category = '$category' AND newimage1 IS NULL LIMIT 0, $resultPerPage";

		if ($category = "all")
		{
			$sql = "SELECT id, orgimage1, orgimage2, orgimage3, orgimage4, orgimage5, newimage1 FROM `site_".$website."` WHERE newimage1 IS NULL LIMIT 0, $resultPerPage";
		}

		Xlog::record($sql);

		$sql = $db->prepare($sql);
		$sql->execute();
		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}

	public function get_price($category) {
		$db = $this->dbcrawler;	

		$website = $this->website;

		$today = date("d/m/y", time());
		$sql = "SELECT link, price FROM `site_".$website."` WHERE category = '$category' AND price > 0 AND update_price_date <> '".$today."'";
		Xlog::record($sql);

		$sql = $db->prepare($sql);
		$sql->execute();
		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}

	public function get_price_page($category, $page, $resultPerPage = 5) {
		$db = $this->dbcrawler;	

		$website = $this->website;

		$lowerpage = ($page-1)*5;
		$today = date("d/m/y", time());
		$ystd = date("d/m/y", time() - 1 * 60 * 60 * 24);

		$presql = "SELECT category, link, name, orgimage, comment, description, cat1, subcat1, orgimage1, orgimage2, orgimage3, orgimage4, orgimage5, orgimage6, orgimage7 FROM `site_".$website."`";

		$sql = $presql." WHERE category = '$category' AND price > 0 AND has_updated_price = 0 AND ((update_price_date <> '".$today."' AND update_price_date <> '".$ystd."') OR update_price_date IS NULL) LIMIT 0, $resultPerPage";

		if ($category == "all")
		{
			Xlog::record("All category<br/>", true, false);
			$sql = $presql." WHERE price > 0 AND has_updated_price = 0 AND ((update_price_date <> '".$today."' AND update_price_date <> '".$ystd."') OR update_price_date IS NULL) LIMIT 0, $resultPerPage";
		}
		else if ($category == "all_products")
		{
			Xlog::record("All category regardless of date<br/>", true, false);
			$sql = $presql." WHERE price > 0 LIMIT $lowerpage, $resultPerPage";	
		}

		Xlog::record("Get price page query: ".$sql);

		$sql = $db->prepare($sql);
		$sql->execute();
		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}

	public function merge_preprocess_to_site_database_website($resultPerPage = 50)
	{
		$db = $this->dbcrawler;

		$website = $this->website;

		$count = 0;
		$sql = "SELECT * FROM `preprocess_".$website."` LIMIT 0, 50";
		Xlog::record($sql);

		$sql = $db->prepare($sql);
		$sql->execute();
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);

		if (sizeof($result) == 0) return $count;

		// Go through each product in the preprocess database to add to the post-process database
		for ($j = 0; $j < sizeof($result); ++$j)
		{
			$row = $result[$j];
			++$count;

			$extra_images = array();
			$extra_images[] = "";
			for ($i = 1 ; $i <= 7 ; ++$i)
			{
				$extra_images[] = $row["orgimage".$i];
			}

			// Add product from the preprocess database to the postprocess database
			$this->add_product($row["category"], $row["link"], $row["name"], $row["price"], $row["orgimage"], $row["comment"], $row["description"], $row["cat1"], $row["subcat1"], $extra_images, $row["update_price_date"], $row["description2"]);
			
			// Delete product from the preprocess database after adding it into the postprocess database			
			$sql = "DELETE FROM `preprocess_".$website."` WHERE id = ".$row["id"];
			Xlog::record($sql);				
			$sql = $db->prepare($sql);
			$sql->execute();

			// Stop when reaching the desired number of result per page
			if ($count >= $resultPerPage) 
			{
				break;
			}
		}				

		return $count;	
	}

	public function remove($category) {
		$db = $this->dbcrawler;
		$website = $this->website;

		$sql = "DELETE FROM `site_".$website."` WHERE category = '$category'";
		$sql = $db->prepare($sql);
		$sql->execute();
	}

	public function remove_product($id) {
		$db = $this->dbcrawler;
		$website = $this->website;

		Xlog::record("Start removing product $id from $website");

		$sql = "DELETE FROM `site_".$website."` WHERE id = $id";
		$sql = $db->prepare($sql);
		$sql->execute();

		Xlog::record("Finish removing product $id from $website");
	}

	public function reuse_original_image($id, $position) {
		$db = $this->dbcrawler;
		$website = $this->website;

		Xlog::record("Start reusing original image for new image for product $id from $website");

		$sql = "UPDATE `site_".$website."` SET newimage".$position." = orgimage".$position." WHERE id = $id";
		Xlog::record($sql);
		$sql = $db->prepare($sql);
		$sql->execute();

		Xlog::record("Finish reusing original image for new image for product $id from $website");
	}

	public function updateNewImage($order, $id, $link) {
		$db = $this->dbcrawler;
		$website = $this->website;

		$sql = "UPDATE `site_".$website."` SET newimage$order='$link' WHERE id = $id";
		$sql = $db->prepare($sql);
		$sql->execute();
	}

	public function get() {
		$db = $this->dbcrawler;
		$website = $this->website;

		$sql = "SELECT * FROM `site_".$website."`";
		$sql = $db->prepare($sql);
		$sql->execute();
		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}

	public function get_stats_date($category, $day_back) {
		$db = $this->dbcrawler;
		$website = $this->website;

		$sql = "";
		if ($day_back >= 0) 
		{
			$day = date("d/m/y", time() - $day_back * 60 * 60 * 24);
			$sql = "SELECT COUNT(*) AS count FROM `site_".$website."` WHERE price > 0 AND category = '".$category."' AND update_price_date = '".$day."'";
		}
		else 
		{
			$sql = "SELECT COUNT(*) AS count FROM `site_".$website."` WHERE price > 0 AND category = '".$category."'";
		}
		$sql = $db->prepare($sql);
		$sql->execute();
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);
		return $result[0];
	}

	public function get_stats_preprocess($category, $price_greater_than_zero) {
		$db = $this->dbcrawler;
		$website = $this->website;

		$sql = "";
		if ($price_greater_than_zero)
		{
			$sql = "SELECT COUNT(*) AS count FROM `preprocess_".$website."` WHERE price > 0 AND category = '".$category."'";
		}
		else
		{
			$sql = "SELECT COUNT(*) AS count FROM `preprocess_".$website."` WHERE price <= 0 AND category = '".$category."'";
		}

		$sql = $db->prepare($sql);
		$sql->execute();
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);
		return $result[0];
	}

	public function get_product_from_model_name($website, $model)
	{
		$db = $this->dbcrawler;

		$model = create_keyword_name($model);
		// Remove the first keyword which is brand name

		if (sizeof($model) > 2)
		{
			$model[0] = "%";
			$model = implode(" ", $model);
			$model = $model." %";
		}
		else
		{
			$model = implode(" ", $model);
			$model = "%".$model." %";	
		}

		$sql = "SELECT id FROM `site_".$website."`WHERE price > 0 AND name LIKE'".$model."'";
		echo $sql."<br/>";

		$sql = $db->prepare($sql);
		$sql->execute();
		return $result = $sql->fetchAll(PDO::FETCH_ASSOC);
	}

	public function update_video_product($id, $website, $video1, $video2)
	{
		$db = $this->dbcrawler;
		$sql = "UPDATE `site_".$website."` SET video1 = '".$video1."', video2 = '".$video2."' WHERE id = ".$id;
		echo $sql."<br/>";

		$sql = $db->prepare($sql);
		$sql->execute();
	}

	public function update_comment_product($id, $website, $comment)
	{
		$db = $this->dbcrawler;
		$comment = str_replace("'", "", $comment);
		$sql = "UPDATE `site_".$website."` SET comment = '".$comment."' WHERE id = ".$id;
		echo $sql."<br/>";

		$sql = $db->prepare($sql);
		$sql->execute();
	}

	public function clear_preprocess_price_information()
	{
		$db = $this->dbcrawler;
		$website = $this->website;

		/* Clear all the has updated price flag*/
		$sql = "UPDATE `site_".$website."` SET has_updated_price = 0";
		echo $sql."<br/>";
		$sql = $db->prepare($sql);
		$sql->execute();

		/* Clear all the has updated price flag*/
		$sql = "TRUNCATE TABLE `preprocess_".$website."`";
		echo $sql."<br/>";
		$sql = $db->prepare($sql);
		$sql->execute();		
	}
}
?>