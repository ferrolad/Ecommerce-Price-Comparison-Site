<?php
require_once("./include/membersite_config.php");
require_once("./include/category_config.php");

if(!$fgmembersite->CheckLogin())
{
  $fgmembersite->RedirectToURL("login.php");
  exit;
}

if(isset($_POST['submitted']))
{
 if($fgmembersite->Login())
 {
  $fgmembersite->RedirectToURL("/CMS/homepage.php");
}
}

include ('./header.php');
?>  
    
    
            <div id="tabs"><a href="/pet/product" class="item active">Slider</a><a href="/catalog/category" class="item">New Products</a><a href="/catalog-ext/filters" class="item">Hot Deals</a><a href="/catalog/brand" class="item">SEO</a><a href="/catalog/supplier" class="item">Catalog Sorting</a><a href="/catalog/attribute" class="item">Rating & Reviews</a><a href="/import/import" class="item">URL Manager</a></div>    
    <div id="viewport_main">
    
            <div id="content">    
                <div class="headline"><h1><a href="/cart-ext/matrix-upload">Catalog</a> &gt; Slider</h1><div class="wrapper"></div></div>    
                <div id="main">
				<?php
                    include "include/hotDeal_config.php";
					for ($i = 1; $i <=15; $i++) {
                        $result = $hotDealsDB->get($i);
						echo "<img src = \"".$result[2]."\"/><br/><br/>";
                        echo "<form action=\"uploadHotdeals.php\" method=\"post\" enctype=\"multipart/form-data\">";
                        echo "<input type=\"hidden\" name=\"pos\" value=\"$i\"/>";
                        echo "<strong>Link:<br/> </strong><input type=\"text\" name=\"link\" value = \"".$result[1]."\" size = 70px /><br/>\n";
                        echo "<strong>Image alt:<br/> </strong><input type=\"text\" name=\"alt\" value = \"".$result[3]."\" size = 70px /><br/>\n";
                        echo "<strong>Price:<br/> </strong><input type=\"text\" name=\"price\" value = \"".$result[6]."\" size = 70px /><br/>\n";
                        echo "<strong>Description:</strong><br/> <textarea name = \"description\" value = \"".$result[5]."\" rows=\"4\" cols=\"50\">\n";
                        echo $result[5];
                        echo "</textarea><br/>\n";
                        echo "<input name=\"file\" type=\"file\" multiple />"; 
                        echo "<input type=\"submit\" value=\"Submit\" />";
                        echo "</form>";
                        echo "<br/>";
					}
				?>
                                                            <div class="wrapper"><!-- &nbsp; -->
				
															</div>
                </div>
    
                    
            </div>   

<?php
    include ('./footer.html');
?>

    </body>
</html>
