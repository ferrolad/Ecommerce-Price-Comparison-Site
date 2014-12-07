<?php
require_once("./include/membersite_config.php");
require_once("./include/category_config.php");
require_once("./include/crawler_config.php");

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

include ('./menu.html');
?>  
            
            <div id="tabs"><a href="/pet/product" class="item active">The gioi di dong</a><a href="/catalog/category" class="item">New Products</a><a href="/catalog-ext/filters" class="item">Hot Deals</a><a href="/catalog/brand" class="item">SEO</a><a href="/catalog/supplier" class="item">Catalog Sorting</a><a href="/catalog/attribute" class="item">Rating & Reviews</a><a href="/import/import" class="item">URL Manager</a></div>    
    <div id="viewport_main">
    
            <div id="content">    
                <div class="headline"><h1><a href="/cart-ext/matrix-upload">Catalog</a> &gt; Slider</h1><div class="wrapper"></div>

                <a href = ""></a>
                </div>    
                <div id="main">
				<?php
                    $site = 'thegioididong';
                    if (isset($_GET["site"])) {
                        $site = $_GET["site"];
                    }

                    $result = null;
                    $page = 1;
                    if (isset($_GET["page"])) {
                        $page = $_GET["page"];
                    }

                    echo "<form action=\"product.php\" method=\"GET\">";
                    echo "<input type = \"hidden\" name=\"site\" value=\"$site\">";
                    echo "<input type = \"hidden\" name=\"page\" value=\"".($page+1)."\">";
                    echo "<input type= \"Submit\" value = \"Next Page\">";
                    echo "</form>";

                    if ($page > 1) {
                        echo "<form action=\"product.php\" method=\"GET\">";
                        echo "<input type = \"hidden\" name=\"site\" value=\"$site\">";
                        echo "<input type = \"hidden\" name=\"page\" value=\"".($page-1)."\">";
                        echo "<input type= \"Submit\" value = \"Previous Page\">";
                        echo "</form>";
                    }

                    if ($site == 'thegioididong') {
                        $result = $thegioididongDB->get();
                    }

                    if ($site == 'dienmay') {
                        $result = $dienmayDB->get();
                    }

                    if ($site == 'dienmaycholon') {
                        $result = $dienmaycholonDB->get();
                    }

                    if ($site == 'dienmaythienhoa') {
                        $result = $dienmaythienhoaDB->get();
                    }

                    if ($site == 'nguyenkim') {
                        $result = $nguyenkimDB->get();
                    }

                    if ($result != null) {
                        $index = 0;
                        while ($row = mysql_fetch_row($result)) {
                            $index++;
                            if ($index <= (($page-1)*10)) continue;
                            if ($index > $page*10) break;
                            $id = $row[0];
                            echo "<h2>".$row[3]."</h2>";
                            echo "<img src = \"../img/$site/current/$id.jpg\"/><br/><br/>";
                            echo "<form action=\"uploadHotdeals.php\" method=\"post\" enctype=\"multipart/form-data\">";
                            echo "<input type=\"hidden\" name=\"pos\" value=\"$id\"/>";
                            echo "<strong>Link:<br/> </strong><input type=\"text\" name=\"link\" value = \"".$row[2]."\" size = 70px /><br/>\n";
                            echo "<strong>Image alt:<br/> </strong><input type=\"text\" name=\"alt\" value = \"\" size = 70px /><br/>\n";
                            echo "<strong>Description:</strong><br/> <textarea name = \"description\" rows=\"4\" cols=\"50\">\n";
                            echo "</textarea><br/>\n";
                            echo "<input name=\"file\" type=\"file\" multiple />"; 
                            echo "<input type=\"submit\" value=\"Submit\" />";
                            echo "</form>";
                            echo "<br/>";
                        }
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
