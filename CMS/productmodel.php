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

// List of brand
$dbbrand = new DBBrandModel();
$dbbrand->SetConfig($default_hostname, $default_username, $default_password, $default_db);

//List of categories
$result = $categoryDB->get();
$catlist = array();
$catidlist = array();
$numproductlist = array();
$vnese_catlist = array();
for ($i = 0; $i < sizeof($result); $i++) {
  $row = $result[$i];
  $catidlist[] = $row["id"];
  $catlist[] = $row["category"];
  $vnese_catlist[] = $row["vnese_name"];
}

include ('./header.php');
?>

<div id="tabs"><a href="/catalog/category" class="item active">Categories</a></div> 

<div id="viewport_main">

  <div id="content">    
    <div class="headline"><h1><a href="/cart-ext/matrix-upload">Catalog</a> &gt; Categories</h1><div class="wrapper"></div></div>    
    
    <!-- Add new brand -->
    <form method = "POST" action = "productmodel.php">
      <strong>Add Brand:<br/></strong>
      Brand:<input type = "text" name = "brand"/><br/>
      Category:<select name = "cat" id="brandcat" onchange="updatesubcategory('brandcat', 'brandsubcat')"/>
      <?php
      for ($i = 0 ; $i < sizeof($catlist); $i++) {
        echo "<option value=\"".$catidlist[$i]."\">".$catlist[$i]."</option>;\n";
      }
      ?>
    </select><br/>
    Subcategory:<select name = "subcat" id="brandsubcat" /></select><br/>
    Description:<input type = "text" name = "description"/><br/>
    Image:<input type = "text" name = "image"/><br/>
    <input type="hidden" name="Action" value="AddBrand" />
    <input type="submit" value="Add" />
  </form><br/>

  <!-- Add new model to existing brand -->
  <strong>(Note: To add multiple models, use ""///"" as the delimiter)</strong>
  <form method = "POST" action = "productmodel.php">
    <strong>Add Model:<br/></strong>
    Model:<input type = "text" name = "model" size=120/><br/>
    Category:<select name = "cat" id="modelcat" onchange="updatesubcategory('modelcat', 'modelsubcat'); updatebrand('modelcat', '0', 'modelbrand')"/>
    <?php
    for ($i = 0 ; $i < sizeof($catlist); $i++) {
      echo "<option value=\"".$catidlist[$i]."\">".$catlist[$i]."</option>;\n";
    }
    ?>
  </select><br/>
  Subcategory:<select name = "subcat" id="modelsubcat" onchange="updatebrand('modelcat', 'modelsubcat', 'modelbrand')"/></select><br/>
  Brand:<select name = "brand" id="modelbrand"/></select><br/>
  Description:<input type = "text" name = "description"/><br/>
  Image:<input type = "text" name = "image"/><br/>
  <input type="hidden" name="Action" value="AddModel" />
  <input type="submit" value="Add" />
</form><br/>
<br/>

<?php
for ($i = 0; $i < sizeof($catlist); ++$i)
{
  echo "<strong style=\"color:red\">".$catlist[$i].":</strong><br/>";

  display_brand_and_model($dbbrand, $catidlist[$i], 0);

  // Subcategory of each category
  $subcat = $categoryDB->get_subcat($catidlist[$i]);
  for ($j = 0; $j < sizeof($subcat); ++$j)
  {
    $row = $subcat[$j];
    echo "<strong style=\"color:blue;margin-left:30px\">".$row["subcat"].":</strong><br/>";    

    display_brand_and_model($dbbrand, $catidlist[$i], $row["id"]);
  }
  echo "<br/>";
}
?>

<script>
<?php
// Add new brand
if (isset($_POST["Action"]) && $_POST["Action"] == "AddBrand")
{
  $dbbrand->addBrand($_POST["brand"], $_POST["cat"], $_POST["subcat"], $_POST["description"], $_POST["image"]);
  echo "window.location = 'productmodel.php'";
}

  // Add new model
if (isset($_POST["Action"]) && $_POST["Action"] == "AddModel")
{
  $dbbrand->addModel($_POST["model"], $_POST["brand"], $_POST["cat"], $_POST["subcat"], $_POST["description"], $_POST["image"]);
  echo "window.location = 'productmodel.php'";
}

function display_brand_and_model($dbbrand, $cat, $subcat)
{
  $brand_list = $dbbrand->getBrand($cat, $subcat);
  for ($k = 0; $k < sizeof($brand_list); $k++) {
    $brand = $brand_list[$k];
    echo "<strong style=\"margin-left:50px;color:purple\">+ ".$brand["brand"].":</strong><br/>";  

    // Model list of each brand
    $model_list = $dbbrand->getModel($cat, $subcat, $brand["id"]);
    for ($i = 0; $i < sizeof($model_list); $i++) {
      $model = $model_list[$i];
      echo "<strong style=\"margin-left:80px\">".$model["model"]."</strong><br/>";  
    }
  }
}
?>
</script>

<script>
function updatesubcategory(catid, subcatid)
{
  var cat = document.getElementById(catid).value;

  <?php
  for ($i = 0; $i < sizeof($catidlist); ++$i)
  {
    echo "if (cat == ".$catidlist[$i].")\n";
    echo "{\n";

    $catHTML = "";
    $subcat = $categoryDB->get_subcat($catidlist[$i]);
    for ($j = 0; $j < sizeof($subcat); $j++) 
    {
      $row = $subcat[$j];
      $catHTML .= "<option value=\"".$row["id"]."\">".$row["subcat"]."</option>;";
    }

    // There is no subcategory for the category like tablets
    if (sizeof($subcat) == 0)
    {
      $catHTML .= "<option value=\"0\">No subcategory</option>;";
      $catHTML .= "<option value=\"0\">No subcategory</option>;";
    }

    echo "document.getElementById(subcatid).innerHTML = '".$catHTML."';\n";
    echo "}\n\n";
  }
  ?>
}

function updatebrand(catid, subcatid, brand)
{
  var cat = document.getElementById(catid).value;
  var subcat = document.getElementById(subcatid).value;
  
  <?php
  for ($i = 0; $i < sizeof($catidlist); ++$i)
  {

    $subcat = $categoryDB->get_subcat($catidlist[$i]);
    for ($j = 0; $j < sizeof($subcat); ++$j)
    {
      $row = $subcat[$j];
      echo "if (cat == ".$catidlist[$i]." && subcat == ".$row["id"].")\n";
      echo "{\n";

      $catHTML = "";
      $brand_list = $dbbrand->getBrand($catidlist[$i], $row["id"]);
      for ($k = 0; $k < sizeof($brand_list); $k++) {
        $brand = $brand_list[$k];
        $catHTML .= "<option value=\"".$brand["id"]."\">".$brand["brand"]."</option>;";
      }

      echo "document.getElementById(brand).innerHTML = '".$catHTML."';\n";
      echo "}\n\n";
    }

    // This is the special case of no subcategory for the category
    echo "if (cat == ".$catidlist[$i]." && subcat == 0)\n";
    echo "{\n";

    $catHTML = "";
    $brand_list = $dbbrand->getBrand($catidlist[$i], 0);
    for ($k = 0; $k < sizeof($brand_list); $k++) {
      $brand = $brand_list[$k];
      $catHTML .= "<option value=\"".$brand["id"]."\">".$brand["brand"]."</option>;";
    }

    echo "document.getElementById(brand).innerHTML = '".$catHTML."';\n";
    echo "}\n\n";
  }

  ?>
}
</script>

<?php
include ('./footer.html');
?>
</body>
</html>