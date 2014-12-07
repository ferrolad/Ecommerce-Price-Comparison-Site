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

$website_list = website_vietnamese_name();

// List of brand
$dbreview = new DBEditorialReview();
$dbreview->SetConfig($default_hostname, $default_username, $default_password, $default_db);
?>

<div id="tabs"><a href="/catalog/category" class="item active">Categories</a></div> 

<div id="viewport_main">

  <div id="content">    
    <div class="headline"><h1><a href="/cart-ext/matrix-upload">Catalog</a> &gt; Categories</h1><div class="wrapper"></div></div>    
    
    <!-- Add new editorial review for model -->
    <form method = "POST" action = "editorialreview.php">
      <strong>Add Review for Model:<br/></strong>
      Reviewer:<input type = "text" name = "reviewer"/><br/>
      Model ID:<input type = "text" name = "id"/><br/>
      Description:
      <br/>
      <textarea name = "description" rows="20" cols="200"></textarea>
      <br/>
      Rating:<input type = "text" name = "rating"/><br/>
      <input type="hidden" name="Action" value="AddReviewModel" />
      <input type="submit" value="Add" />
    </form><br/>

    <!-- Add new editorial review for product -->
    <form method = "POST" action = "editorialreview.php">
      <strong>Add Review for Product:<br/></strong>
      Reviewer:<input type = "text" name = "reviewer"/><br/>
      Product ID:<input type = "text" name = "id"/><br/>
      Website:<select name = "website" />
        <?php
        foreach ($website_list as $website => $website_name) 
        {
            echo "<option value=\"".$website."\">".$website_name."</option>;\n";
        }
        ?>
      </select><br/>
      Description:
      <br/>
      <textarea name = "description" rows="20" cols="200"></textarea>
      <br/>
      Rating:<input type = "text" name = "rating"/><br/>
      <input type="hidden" name="Action" value="AddReviewProduct" />
      <input type="submit" value="Add" />
    </form><br/>
  <br/>

  <?php
  if (isset($_POST["Action"]))
  {
    $action = $_POST["Action"];

    // Add review for Model
    if ($action == "AddReviewModel")
    {
      $dbreview->add($_POST["reviewer"], 1, "", $_POST["id"], $_POST["description"], 1, $_POST["rating"]);
    }

    // Add review for Product
    if ($action == "AddReviewProduct")
    {
      $dbreview->add($_POST["reviewer"], 0, $_POST["website"], $_POST["id"], $_POST["description"], 1, $_POST["rating"]);
    }
  }
  ?>

  <?php
  include ('./footer.html');
  ?>
</body>
</html>