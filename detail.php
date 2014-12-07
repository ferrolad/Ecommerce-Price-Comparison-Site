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
      $id = $params[sizeof($params)-1];
      $website = $params[sizeof($params)-2];

      /* If it is the old URL, do a 301 redirect to new URL */
      if (!is_numeric($website)) {
        $params[sizeof($params)-2] = translate_website_to_code($website);
        $newURL = implode("-", $params);
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location: /".$newURL.".html"); 
        exit();
      }
    }
    // If it is a product model
    else
    {
      $id = $params[sizeof($params)-2]; 
      $website = "modelproduct";
    }
  } else {
    $fgmembersite->RedirectToURL("/"); 
  }
} else {
  $fgmembersite->RedirectToURL("/");
}

/* If no product id or website get passed in then redirected back to homepage */
if (!is_numeric($id)) {
  $fgmembersite->RedirectToURL("/");
}

include "./display/detail_display.php";      
$display = new detail_display();   
/* Get whether the prodcut exists or not */
$result = $display->SetConfig($default_hostname, $default_username, $default_password, $default_db, $id, $website, $lang);

// Signal to redirect from the product page to the model page
if (strpos("$result", "RedirectTo:") !== false)
{
  header("HTTP/1.1 301 Moved Permanently"); 
  header("Location: /".substr($result, strlen("RedirectTo:"))); 
  exit();
}
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
  <link rel="stylesheet" type="text/css" href="/css/styles.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="/css/thickbox.css" />
  <link rel="stylesheet" type="text/css" href="/css/jquery-ui-1.9.0.custom.css" />
  <link href="/index_css/style.css" rel="stylesheet" type="text/css" />
  <link href="/index_css/tabcontent.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" media="screen" href="/css/thickbox.css" />
  <link href="/css/lightbox.css" rel="stylesheet" />

  <script type="text/javascript" src="/js/jquery.flexslider.js"></script>
  <script type="text/javascript" src="/index_js/tabcontent.js"></script>
  <script src="/js/jquery-1.8.2.js"></script>
  <script src="/js/lightbox.js"></script>
  <script src="/js/jquery-ui-1.9.0.custom.js"></script>
  <script src="/js/custom-form-elements.js"></script>
  <script src="/js/jquery.flexslider.js"></script>
  <script type="text/javascript" src="/js/thickbox.js"></script>
  <script type="text/javascript" src="/js/custom-function.js"></script>
  <script type="text/javascript" src="/js/general_script.js"></script>

  <!--[if IE]>
  <link href="css/ie.css" rel="stylesheet" type="text/css" />
  <![endif]-->


  <?php
  include('./menu_autofit.php');
  ?>

  <script type="text/javascript">

  <?php
  echo "var _gaq=_gaq||[];_gaq.push(['_setAccount','UA-".$l_detail[$curlang]["ga_account"]."-1']);\n";
  echo "_gaq.push(['_setDomainName','".$l_detail[$curlang]["domain"]."']);\n";
  ?>

  _gaq.push(['_trackPageview']);
  (function(){
    var ga=document.createElement('script');
    ga.type='text/javascript';ga.async=true;
    ga.src=('https:'==document.location.protocol?'https://ssl':'http://www')+'.google-analytics.com/ga.js';var s=document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga,s);})();
    </script>

    <script type="text/javascript">function trackOutboundLink(link,category,action){try{_gaq.push(['_trackEvent',category,action]);}catch(err){}
    setTimeout(function(){window.open(link.href);},100);}</script>

    <script>
    function recordVote(value, id, website) {
      $.ajax({
          url:'/CMS/recordVote.php?vote='+value+'&id='+id+'&website='+website,
          complete: function (response) {
              location.reload();
              // var numRating = parseInt(response.responseText);
              // document.getElementById("num_rating").innerHTML = numRating + " ratings";
          },
          error: function () {
              $('#output').html('Bummer: there was an error!');
          },
      });
      return false;
    }
    </script>

      </head>
      <body>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=286001391479170";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

        <?php
      // If there is no product, redirect to the homepage
        if($result == false) {
          echo "<script>";
          echo "window.location = \"/\";";
          echo "</script>";
        }

      // If the URL is malformed, redirect to the correct url 
        if ($result <> $_GET["param"].".html") {
          echo "<script>";
          echo "window.location = \"/".$result.".html\";";
          echo "</script>";
        }
        ?>

        <div id="main">
         <div id="header">
          <?php
          include ('./top.php');
          ?>
          <div id="box_MenuLeft" style="height:325px;display:none;" onmouseout="close_menu(event);" onmouseover="show_menu(event);">
            <ul id="product_categories">

              <?php
              /* Display dropdown menu in detail page */
              $display->display_product_categories();
              ?>

            </ul>
          </div>
        </div>
        <div class="details_holder">
         <div id="breadcrumb" style="padding-top: 10px">

          <?php
          /* Display category list at the top of the detail page */
          $display->display_category_list();
          ?>

        </div>

        <div class="main_product_holder">
         <div id="img_border">

          <?php
          $display->display_product_image();
          ?>

        </div>  
        <div class="product_content">
          <?php
          $display->display_product_detail();
          ?>
        </div>
        <div class="related_items" style="height:350px; overflow:hidden">
          <?php
          $display->display_related_items();
          ?>
        </div>
      </div>

      <?php
      $display->display_ad(1);
      ?>

      <div class="product_review">
        <div class="shadetabs">
          <ul id="countrytabs">
            <li><a href="#" rel="country1"><?php echo $l_detail[$curlang]["tab1"]; ?></a></li>
            <li><a href="#" rel="country2"><?php echo $l_detail[$curlang]["tab2"]; ?></a></li>
            <!--<li><a href="#" rel="country3">NHẬN XÉT</a></li>-->
          </ul>
        </div>

        <div class="tab_content_holder">  
          <div id="country1">
           <div class="compare_content">
            <div class="table_header">
              <strong>
                <?php
                $display->display_text_above_price_comparison();
                ?>
              </strong>
            </div>
            <div class="table_header">
             <div class="text1"><?php echo $l_detail[$curlang]["price_tab1"]; ?></div>
             <div class="text2"><?php echo $l_detail[$curlang]["price_tab2"]; ?></div>
             <div class="text3"><?php echo $l_detail[$curlang]["price_tab3"]; ?></div>
           </div>
           <?php
           $display->display_price_comparison();
           ?>

           <!--<div class="more"><a href="#">... &nbsp;xem thêm</a> &nbsp;&nbsp;</div>-->
           <br clear="all" />
           <div class="subscription_box">
             <div class="text_holder"><?php echo $l_detail[$curlang]["grey_signup"]; ?></div>
             <div class="search_box">
               <div class="textbox"><input name="" type="text" /></div>
               <div class="btn_holder"><a class="thickbox" href="subscription.html?height=370&width=920&KeepThis=true&TB_iframe=true"><img src="/index_images/search-btn1.png" alt="" /></a></div>
             </div>
             <br clear="all" />
           </div>
         </div>
       </div>
       <div id="country2">

        <?php
        $display->display_seo_text();
        ?>

      </div>
    </div>
  </div>

  <?php
  $display->display_summary_information();
  $display->display_video_review();
  $display->display_editorial_review();
  $display->display_customer_reviews();
  $display->display_ad(2);
  ?>

  <div class="releated_search"><strong><?php echo $l_detail[$curlang]["related_keywords"]; ?>:</strong>
    <?php
    $display->display_related_search();
    ?>

  </div>


  <div class="footer">
   <div class="footer_link">
     <h2><?php echo $l_detail[$curlang]["join_us"]; ?></h2>
     <ul>
       <li><img src="/index_images/ico1.png" alt="Facebook" /> <a href=<?php echo "\"".$l_detail[$curlang]["fb_link"]."\""; ?> rel="nofollow" target="_blank">Facebook</a></li>
       <li><img src="/index_images/ico2.png" alt="Google Plus" /> <a href=<?php echo "\"".$l_detail[$curlang]["gplus_link"]."\""; ?> rel="nofollow" target="_blank">Google+</a></li>
       <li><img src="/index_images/ico4.png" alt="Twitter" /> <a href="https://twitter.com/OIzoioi1" rel="nofollow" target="_blank">Twitter</a></li>
       <li><img src="/index_images/ico5.png" alt="Blog" /> <a href="/blog/" target="_blank">Blog</a></li>
     </ul>
   </div>
   <div class="footer_link">
     <h2><?php echo $l_detail[$curlang]["categories"]; ?></h2>
     <ul>

      <?php
      echo "<li><a href=\"/".strtolower(remove_sign($l_detail[$curlang]["mobile"]))."\">".$l_detail[$curlang]["mobile"]."</a></li>\n";
      echo "<li><a href=\"/".strtolower(remove_sign($l_detail[$curlang]["tablet"]))."\">".$l_detail[$curlang]["tablet"]."</a></li>\n";
      echo "<li><a href=\"/".strtolower(remove_sign($l_detail[$curlang]["computer"]))."\">".$l_detail[$curlang]["computer"]."</a></li>\n";
      echo "</ul>\n";
      echo "<ul><li><a href=\"/".strtolower(remove_sign($l_detail[$curlang]["camera"]))."\">".$l_detail[$curlang]["camera"]."</a></li>\n";
      echo "<li><a href=\"/".strtolower(remove_sign($l_detail[$curlang]["tv"]))."\">".$l_detail[$curlang]["tv"]."</a></li>\n";
      echo "<li><a href=\"/".strtolower(remove_sign($l_detail[$curlang]["audio"]))."\">".$l_detail[$curlang]["audio"]."</a></li>\n";
      echo "</ul>\n";
      echo "<ul>\n";
      echo "<li><a href=\"/".strtolower(remove_sign($l_detail[$curlang]["accessory"]))."\">".$l_detail[$curlang]["accessory"]."</a></li>\n";
      echo "<li><a href=\"/".strtolower(remove_sign($l_detail[$curlang]["women_clothing"]))."\">".$l_detail[$curlang]["women_clothing"]."</a></li>\n";
      echo "<li><a href=\"/".strtolower(remove_sign($l_detail[$curlang]["men_clothing"]))."\">".$l_detail[$curlang]["men_clothing"]."</a></li>\n";
      echo "</ul>\n";
      ?>
    </ul>
  </div>
  <div class="footer_link">
   <h2><?php echo $l_detail[$curlang]["top_brand"]; ?></h2>
   <ul>
     <li><a href="/search.php?term=apple">Apple</a></li>
     <li><a href="/search.php?term=samsung">Samsung</a></li>
     <li><a href="/search.php?term=Nokia">Nokia</a></li>
     <li><a href="/search.php?term=Lenovo">Lenovo</a></li>
     <li><a href="/search.php?term=Sony">Sony</a></li>
   </ul>
 </div>
 <div class="footer_link">
  <h2><?php echo $l_detail[$curlang]["information"]; ?></h2>
  <ul>
    <?php
    echo "<li><a href=\"/contact.html\">".$l_detail[$curlang]["contact"]."</a></li>\n";
    echo "<li><a href=\"/privacy_policy.html\">".$l_detail[$curlang]["privacy_policy"]."</a></li>\n";
    echo "<li><a href=\"/term_condition.html\">".$l_detail[$curlang]["term_condition"]."</a></li>\n"; 
    ?>
  </ul>
</div>

<div class="signup_form">
 <div class="title"><?php echo $l_detail[$curlang]["dont_miss_deal"]; ?></div>
 <div class="textbox"><input name="" type="text" /></div>
 <div class="btn_holder"><a class="thickbox" href="subscription.html?height=370&width=920&KeepThis=true&TB_iframe=true"><img src="/index_images/signup.png" alt="" /></a> <a class="thickbox" href="subscription.html?height=370&width=920&KeepThis=true&TB_iframe=true"><?php echo $l_detail[$curlang]["more_detail"]; ?></a></div>
 <div class="find_more"><a class="thickbox" href="subscription.html?height=370&width=920&KeepThis=true&TB_iframe=true"><?php echo $l_detail[$curlang]["signup"]; ?></a></div>
</div>
</div>
</div>
</div>
<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
(function() {
  var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
  po.src = 'https://apis.google.com/js/platform.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>

<script type="text/javascript">
var countries=new ddtabcontent("countrytabs")
countries.setpersist(true)
  countries.setselectedClassTarget("link") //"link" or "linkparent"
  countries.init()
  </script>
  <div class="copyRight">© 2012 Pricepony. All Rights Reserved </div>
  <!-- Register popup -->
  <!--<script src="/js/javascript.js"></script>-->
  <script type="text/javascript">
  setTimeout(function(){var a=document.createElement("script");
    var b=document.getElementsByTagName("script")[0];
    a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0018/5820.js?"+Math.floor(new Date().getTime()/3600000);
    a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
  </script>
</body>
</html>
