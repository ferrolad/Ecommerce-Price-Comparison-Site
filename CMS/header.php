<?php
try {
  require("./include/membersite_config.php");
  require("./include/category_config.php");
  require("./include/database_config.php");
  require("../display/general_display_function.php");
  foreach (glob("./crawler/*.php") as $filename)
  {
  	if ($filename != "./crawler/general_crawler_function.php" && $filename != "./crawler/base_crawler.php" && $filename != "./crawler/generate_category.php") 
  	{
  	    include $filename;
  	}
  }

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

  include ('./menu.php');
}
catch (Exception $e)
{
  echo "Some dependency error header.php";
}
?>