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

$amazon = new amazon();
$amazon->SetConfig($default_hostname, $default_username, $default_password, $default_db);
?>

<div id="tabs"><a href="/catalog/category" class="item active">Categories</a></div> 

<div id="viewport_main">

  <div id="content">    
    <div class="headline"><h1><a href="/cart-ext/matrix-upload">Catalog</a> &gt; Categories</h1><div class="wrapper"></div></div>    
    
    <!-- Update comment from Amazon -->
    <form method = "GET" action = "productcomment.php">
        Website:<select name = "website" />
        <?php
        foreach ($website_list as $website => $website_name) 
        {
            echo "<option value=\"".$website."\">".$website_name."</option>;\n";
        }
        ?>
        Page:<input type = "text" name = "page"/><br/>
        </select><br/>
        <input type="hidden" name="Action" value="UpdateCommentWebsite" />
        <input type="submit" value="UpdateCommentWebsite" />
    </form><br/>
  <br/>

  <?php
  if (isset($_GET["Action"]))
  {
    $action = $_GET["Action"];

    // Automatically update comment for product
    if ($action == "UpdateCommentWebsite")
    {
      $page = $_GET["page"];
      if (empty($page) || !is_numeric($page) || $page <= 0)
      {
        $page = 1;
      }

      $result = $amazon->updateComment($_GET["website"], $page);

      if ($result > 0)
      {
        echo "<script>";
        echo "window.location = 'productcomment.php?Action=UpdateCommentWebsite&website=".$_GET["website"]."&page=".($page+1)."'";
        echo "</script>";
      }
    }
  }
  ?>

  <?php
  include ('./footer.html');
  ?>
</body>
</html>