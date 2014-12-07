<?php
include "header.php";

$website_list = website_list();

$page = 1;
if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
	$page = $_GET["page"];
}
?>

<div id="tabs"><a href="/catalog/category" class="item active">Categories</a></div> 

<div id="viewport_main">

	<div id="content">    
		<div class="headline"><h1><a href="/cart-ext/matrix-upload">Catalog</a> &gt; Categories</h1><div class="wrapper"></div></div>    
		<form method = "GET" action = "updatebrandproduct.php">
			<?php
				$website_list = website_list();
                for ($i = 0 ; $i < sizeof($website_list); $i++) {
                	echo "<input type=\"checkbox\" name=\"website[]\" value=\"".$website_list[$i]."\"><strong>".$website_list[$i]."</strong><br/>";
                }
              ?>
			<input type="submit" name="submission" value="Update_Brand_Product" />
			<input type="submit" name="submission" value="Update_Brand_Product_Full" />
			<input type="submit" name="submission" value="Update_Model_Product" />
			<input type="submit" name="submission" value="Update_Model_Product_Full" />
		</form><br/>

		<?php
		set_time_limit(5000);
		$count = 0;
		$website_argument = "";

		if (isset($_GET["website"]) && isset($_GET["submission"])) {
			$submission = $_GET["submission"];
			$website = $_GET["website"];

			if ($submission == "Update_Brand_Product_Full") {
				$count = $allproductDB->update_brand_product_full($website[0], $page);
			} else if ($submission == "Update_Brand_Product") {
				$count = $allproductDB->update_brand_product($website[0], $page);
			} else if ($submission == "Update_Model_Product_Full") {
				$count = $allproductDB->update_model_product_full($website[0], $page);
			} else if ($submission == "Update_Model_Product") {
				$count = $allproductDB->update_model_product($website[0], $page);
			}

			$website_argument = cat_arg($website, $count);
		}

		if ($count > 0) {
			echo "<script>";
			echo "window.location = 'updatebrandproduct.php?".$website_argument."page=".($page+1)."&submission=".$submission."';";
			echo "</script>";
		}
		else
		{
			if (isset($_GET["website"]))
			{
				echo "<script>";
				echo "window.location = 'updatebrandproduct.php?".$website_argument."page=1&submission=".$submission."';";
				echo "</script>";
			}
		}

		function cat_arg($website_list, $count)
		{
			$startIndex = 0;
			if ($count <= 0)
			{
				$startIndex = 1;
			}

			if (sizeof($website_list) > 0)
			{
				for ($i = $startIndex; $i < sizeof($website_list); ++$i)
				{
					$website_argument .= "website[]=".$website_list[$i]."&";
				}
			}

			return $website_argument;
		}

		?>

		<?php
		include ('./footer.html');
		?>

	</body>
	</html>