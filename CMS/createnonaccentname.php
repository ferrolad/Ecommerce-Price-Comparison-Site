<?php
include "header.php";
?>

<div id="tabs"><a href="/catalog/category" class="item active">Categories</a></div> 

<div id="viewport_main">

	<div id="content">    
		<div class="headline"><h1><a href="/cart-ext/matrix-upload">Catalog</a> &gt; Categories</h1><div class="wrapper"></div></div>    
		<form method = "GET" action = "createnonaccentname.php">
			<select name = "website"/>
			<?php
				$website_list = website_list();
                for ($i = 0 ; $i < sizeof($website_list); $i++) {
                  echo "<option value=\"".$website_list[$i]."\">".$website_list[$i]."</option>;\n";
                }
              ?>
            </select>
			<input type="submit" name="submission" value="Create_Non_Accent_Name" />
			<input type="submit" name="submission" value="Create_Non_Accent_Name_Full" />
		</form><br/>

		<?php
		set_time_limit(5000);
		$count = 0;
		if (isset($_GET["website"])) {
			$page = 1;
			$submission = "Create_Non_Accent_Name";

			if (isset($_GET["submission"]) && $_GET["submission"] == "Create_Non_Accent_Name_Full") {
				$submission = "Create_Non_Accent_Name_Full";
			}

			if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
				$page = $_GET["page"];
			}

			if ($submission == "Create_Non_Accent_Name_Full") {
				$count = $allproductDB->create_no_accent_name_full($_GET["website"], $page);
			} else if ($submission == "Create_Non_Accent_Name") {
				$count = $allproductDB->create_no_accent_name($_GET["website"], $page);
			}
		}

		if ($count > 0) {
			echo "<script>";
			echo "window.location = 'createnonaccentname.php?website=".$_GET["website"]."&page=".($page+1)."&submission=".$submission."';";
			echo "</script>";
		}

		?>

		<?php
		include ('./footer.html');
		?>

	</body>
	</html>