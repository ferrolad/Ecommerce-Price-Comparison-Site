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

            <div id="tabs"><a href="/catalog/category" class="item active">Categories</a></div> 

    <div id="viewport_main">
    
            <div id="content">    
                <div class="headline"><h1><a href="/cart-ext/matrix-upload">Catalog</a> &gt; Categories</h1><div class="wrapper"></div></div>    

            <form method = "GET" action = "addCategory.php">
            <strong>Add category:<br/></strong>
            English name:<input type = "text" name = "catname"/><br/>
            Vietnamese name:<input type = "text" name = "vnese_catname"/><br/>
            <input type="submit" value="Add" />
            </form><br/>

            <form method = "GET" action = "removeCategory.php">
            <strong>Remove category:</strong>
            
            <?php
              $result = $categoryDB->get();
              $catlist = array();
              $idlist = array();
              $numproductlist = array();
              $vnese_catlist = array();
              for ($i = 0; $i < sizeof($result); $i++) {
                $row = $result[$i];
                $idlist[] = $row["id"];
                $catlist[] = $row["category"];
                $numproductlist[] = $row["num_product"];
                $vnese_catlist[] = $row["vnese_name"];
              }
            ?>
            <select name = "catname"/>
              <?php
                for ($i = 0 ; $i < sizeof($catlist); $i++) {
                  echo "<option value=\"".$idlist[$i]."\">".$catlist[$i]."</option>;\n";
                }
              ?>
            </select>
            <input type="submit" value="Remove" />
            </form><br/>

            <form method = "GET" action = "addSubCategory.php">
            <strong>Add subcategory:<br/></strong>
            English name:<input type = "text" name = "subcatname"/><br/>
            Vietnamese name:<input type = "text" name = "vnese_subcatname"/><br/>
            To:
            <select name = "catname"/>
            <?php
                for ($i = 0 ; $i < sizeof($catlist); $i++) {
                  echo "<option value=\"".$idlist[$i]."\">".$catlist[$i]."</option>;\n";
                }
            ?>
            </select>
            <input type="submit" value="Add" />
            </form><br/>

            <form method = "GET" action = "removeSubCategory.php">
            <strong>Remove Subcategory:</strong> 
            <input type = "text" name = "subcatname"/>
            From: 
            <select name = "catname"/>
            <?php
                for ($i = 0 ; $i < sizeof($catlist); $i++) {
                  echo "<option value=\"".$idlist[$i]."\">".$catlist[$i]."</option>;\n";
                }
            ?>
            </select>
            <input type="submit" value="Remove" />
            </form><br/>            

            <Strong>Update And Clean Database</strong><br/>
            <form method = "GET" action = "updateCategoryAndSubcategory.php">
            <input type = "submit" value = "Update Number of Products"/>
            </form>

            <form method = "GET" action = "cleanProductWithInvalidImage.php">
            <input type = "submit" value = "Clean products without images or invalid images"/>
            </form>

            <form method = "GET" action = "createThumbnails.php">
            <input type = "submit" value = "Create thumbnails images"/>
            </form>

            <br/><h1>Category List:</h1><br/>
            <?php
              for ($i = 0 ; $i < sizeof($catlist); $i++) {
                echo "<Strong>".$catlist[$i].": (".utf8_decode($vnese_catlist[$i]).") </Strong> ".$numproductlist[$i]." products<br/>";
                $subcat = $categoryDB->get_subcat($idlist[$i]);
                for ($j = 0; $j < sizeof($subcat); $j++) {
                  $row = $subcat[$j];
                  //$number_non_image_products = $categoryDB->num_non_image_products($row["id"],$idlist[$i]);
                  //$number_invalid_image_products = $categoryDB->num_invalid_image_products($row["id"],$idlist[$i]);
                  //echo $row["subcat"]." (".utf8_decode($row["vnese_name"]).") : ".$row["num_product"]." (".$number_non_image_products." without images, ".$number_invalid_image_products." with invalid images)<br/>";
                  echo $row["subcat"]." (".utf8_decode($row["vnese_name"]).") : ".$row["num_product"]."<br/>";
                }
                echo "</br>";
              }
            ?>
                
<?php
    include ('./footer.html');
?>
  </body>
</html>