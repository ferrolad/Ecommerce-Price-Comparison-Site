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

$mysmartprice = new mysmartprice();
$mysmartprice->SetConfig($default_hostname, $default_username, $default_password, $default_db);

// List of brand
$dbproductvideo = new DBProductVideo();
$dbproductvideo->SetConfig($default_hostname, $default_username, $default_password, $default_db);
?>

<div id="tabs"><a href="/catalog/category" class="item active">Categories</a></div> 

<div id="viewport_main">

  <div id="content">    
    <div class="headline"><h1><a href="/cart-ext/matrix-upload">Catalog</a> &gt; Categories</h1><div class="wrapper"></div></div>    
    
    <!-- Update Video from Mysmartprice -->
    <form method = "GET" action = "productvideo.php">
        Website:<select name = "website" />
        <?php
        foreach ($website_list as $website => $website_name) 
        {
            echo "<option value=\"".$website."\">".$website_name."</option>;\n";
        }
        ?>
        Page:<input type = "text" name = "page"/><br/>
        </select><br/>
        <input type="hidden" name="Action" value="UpdateVideoWebsite" />
        <input type="submit" value="UpdateVideoWebsite" />
    </form><br/>

    <!-- Add new video for model -->
    <form method = "GET" action = "productvideo.php">
      <strong>Add Video for Model:<br/></strong>
      Model ID:<input type = "text" name = "id"/><br/>
      Link1:<input type = "text" name = "link1" size="80"/><br/>
      Link2:<input type = "text" name = "link2" size="80"/><br/>
      <input type="hidden" name="Action" value="AddModelVideo" />
      <input type="submit" value="Add" />
    </form><br/>

    <!-- Add new video for product -->
    <form method = "GET" action = "productvideo.php">
      <strong>Add Video for Product:<br/></strong>
      Product ID:<input type = "text" name = "id"/><br/>
      Website:<input type = "text" name = "website"/><br/>
      Link1:<input type = "text" name = "link1" size="80"/><br/>
      Link2:<input type = "text" name = "link2" size="80"/><br/>
      <input type="hidden" name="Action" value="AddProductVideo" />
      <input type="submit" value="Add" />
    </form><br/>
  <br/>

  <?php
  if (isset($_GET["Action"]))
  {
    $action = $_GET["Action"];

    // Automatically update video for product
    if ($action == "UpdateVideoWebsite")
    {
      $page = $_GET["page"];
      if (empty($page) || !is_numeric($page) || $page <= 0)
      {
        $page = 1;
      }

      $result = $mysmartprice->updateVideo($_GET["website"], $page);

      if ($result > 0)
      {
        echo "<script>";
        echo "window.location = 'productvideo.php?Action=UpdateVideoWebsite&website=".$_GET["website"]."&page=".($page+1)."'";
        echo "</script>";
      }
    }

    // Add video for Model
    if ($action == "AddModelVideo")
    {
      $dbproductvideo->addModelVideo($_GET["id"], $_GET["link1"], $_GET["link2"]);
    }

    // Add video for Product
    if ($action == "AddProductVideo")
    {
      $dbproductvideo->addProductVideo($_GET["id"], $_GET["website"], $_GET["link1"], $_GET["link2"]);
    }
  }
  ?>

  <?php
  include ('./footer.html');
  ?>
</body>
</html>