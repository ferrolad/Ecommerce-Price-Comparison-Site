<?php
include "header.php";
?>

<div id="tabs"><a href="/catalog/category" class="item active">Categories</a></div> 

<div id="viewport_main">

	<div id="content">    
		<div class="headline"><h1><a href="/cart-ext/matrix-upload">Catalog</a> &gt; Categories</h1><div class="wrapper"></div></div>    
		<form method = "GET" action = "updatemodelproduct.php">
			<select name = "website"/>
			<?php
				$website_list = website_list();
                for ($i = 0 ; $i < sizeof($website_list); $i++) {
                  echo "<option value=\"".$website_list[$i]."\">".$website_list[$i]."</option>;\n";
                }
              ?>
            </select>
			<input type="submit" name="submission" value="Update_Model_Product" />
			<input type="submit" name="submission" value="Update_Model_Product_Full" />
		</form><br/>

		<?php
		set_time_limit(5000);
		$count = 0;
		if (isset($_GET["website"])) {
			$page = 1;
			$submission = "Update_Model_Product";

			if (isset($_GET["submission"]) && $_GET["submission"] == "Update_Model_Product_Full") {
				$submission = "Update_Model_Product_Full";
			}

			if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
				$page = $_GET["page"];
			}

			if ($submission == "Update_Model_Product_Full") {
				$count = $allproductDB->update_model_product_full($_GET["website"], $page);
			} else if ($submission == "Update_Model_Product") {
				$count = $allproductDB->update_model_product($_GET["website"], $page);
			}
		}

		if ($count > 0) {
			echo "<script>";
			echo "window.location = 'updatemodelproduct.php?website=".$_GET["website"]."&page=".($page+1)."&submission=".$submission."';";
			echo "</script>";
		}

		?>

		<?php
		include ('./footer.html');
		?>

	</body>
	</html>