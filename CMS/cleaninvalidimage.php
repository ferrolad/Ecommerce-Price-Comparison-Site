<?php
include "header.php";
?>

<div id="tabs"><a href="/catalog/category" class="item active">Categories</a></div> 

<div id="viewport_main">

	<div id="content">    
		<div class="headline"><h1><a href="/cart-ext/matrix-upload">Catalog</a> &gt; Categories</h1><div class="wrapper"></div></div>    
		<form method = "GET" action = "cleaninvalidimage.php">
			<input type="hidden" name="page" value="1" />
			<input type="submit" name="submission" value="Clean Invalid Image" />
		</form><br/>

		<?php
		include "include/category_config.php";
		set_time_limit(5000);
		$count = 0;
		if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
			$page = $_GET["page"];
			$result = $allproductDB->get_image($page);

			if (sizeof($result) <=1) {
				$count = -1;
			} else {
				for ($i = 0; $i < sizeof($result); $i++) {
					$row = $result[$i];
					if (!valid_image("..".$row["newimage1"])) {
						$count ++;
						$allproductDB->remove_product($row["id"], $row["website"], get_catname_from_code_2($row["cat1"]));
					}
				}
				echo "";
			}
		}
		if ($count != -1) {
			echo "<script>";
			echo "window.location = 'cleaninvalidimage.php?page=".($page+1)."';";
			echo "</script>";
		}
		?>

		<?php
		include ('./footer.html');
		?>

	</body>
	</html>