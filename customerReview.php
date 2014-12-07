<?php    
include "./CMS/include/membersite_config.php";
include "./display/general_display_function.php";
$id = "";
$website = "";
$l_detail = $lang->term_detail();

/* Check if the param is well-formed and get id & website parameters out of the url */
if (isset($_GET["param"]) && $_GET["param"] <> "") {
  $param = $_GET["param"];
  $params = explode("-", $_GET["param"]);
  if (sizeof($params) >= 3) {
    // If it is specific product, not a model
    if ($params[sizeof($params)-1] != "model")
    {
      $id = $params[sizeof($params)-2];
      $website = $params[sizeof($params)-1];
    }
    // If it is a product model
    else
    {
      $id = $params[sizeof($params)-2]; 
      $website = "modelproduct";
    }
  } else {
    //$fgmembersite->RedirectToURL("/"); 
  }
} else {
  //$fgmembersite->RedirectToURL("/");
}

/* If no product id or website get passed in then redirected back to homepage */
if (!is_numeric($id)) {
  //$fgmembersite->RedirectToURL("/");
}

include "./display/review_display.php";      
$display = new review_display();   
/* Get whether the prodcut exists or not */
$display->SetConfig($default_hostname, $default_username, $default_password, $default_db, $id, $website, $lang);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <?php
     $display->display_title_keyword_description_header();
  ?> 

  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"> 
  <link rel="stylesheet" type="text/css" href="/css/reset.css" />
  <link rel="stylesheet" type="text/css" href="/css/review.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="/css/thickbox.css" />
  <link rel="stylesheet" type="text/css" href="/css/jquery-ui-1.9.0.custom.css" />
  <link href="/index_css/style.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="/index_js/tabcontent.js"></script>
  <link href="/index_css/tabcontent.css" rel="stylesheet" type="text/css" />
  <link href="/css/lightbox.css" rel="stylesheet" />
    
</head>
<body>
  <div id="main">
   <div id="header">

<?php
$display->display_customer_reviews();
?>
  </div>
  </div>
</body>
</html>
