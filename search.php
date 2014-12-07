<?php
include "./display/search_display.php";      
include "./CMS/include/database_config.php";
$l_general = $lang->general_term();
$display = new search_display();       

$page = 1;
$term = "";
$category = "";
$subcategory = "";
$brand = "";
$lower_price = "";
$upper_price = "";
$website = "";
$rating = "";
$order = 0;
$outofstock = "false";

if (isset($_GET["page"]) && $_GET["page"] <> "" && is_numeric($_GET["page"])) {
  $page = strip_tags($_GET["page"]);
}

if (isset($_GET["outofstock"]) && $_GET["outofstock"] == "true") {
  $outofstock = "true";
}

if (isset($_GET["term"]) && $_GET["term"] <> "") {
  $term = strip_tags($_GET["term"]);
}

if (isset($_GET["category"]) && $_GET["category"] <> "") {
  $category = strip_tags($_GET["category"]);
}

if (isset($_GET["subcategory"]) && $_GET["subcategory"] <> "") {
  $subcategory = strip_tags($_GET["subcategory"]);
}

/*if (isset($_GET["brand"]) && $_GET["brand"] <> "") {
  $brand = $_GET["brand"];
}*/

if (isset($_GET["lower_price"]) && $_GET["lower_price"] <> "") {
  $lower_price = strip_tags(str_replace(".","",$_GET["lower_price"]));
}

if (isset($_GET["upper_price"]) && $_GET["upper_price"] <> "") {
  $upper_price = strip_tags(str_replace(".","",$_GET["upper_price"]));
}

if (isset($_GET["website"]) && $_GET["website"] <> "") {
  $website = strip_tags($_GET["website"]);
}

/*if (isset($_GET["rating"]) && $_GET["rating"] <> "") {
  $rating = $_GET["rating"];
}*/

if (isset($_GET["order"]) && $_GET["order"] <> "") {
  $order = strip_tags($_GET["order"]);
}

$display->SetConfig($default_hostname, $default_username, $default_password, $default_db, $term, $category, $subcategory, $brand, $lower_price, $upper_price, $website, $rating, $order, $page, $outofstock, $lang);

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
    <!--[if IE]>
    <link href="/css/ie.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <script src="/js/jquery-1.8.2.js"></script>
    <script type="text/javascript" src="/js/custom-function.js"></script>
    <script type="text/javascript">
    var active_menu_id = 'home';
    function detect_active(event){
      $('.active').attr('class', '');
      $(event.target).attr('class', 'active');
    }
    function remove_active(event){
      $('.active').attr('class', '');
      $('#'+active_menu_id).attr('class', 'active');
    }

    function change_order() {
      var url = window.location.href;
      var indexorder = url.indexOf("order=");

      var e = document.getElementById("order");
      var order = e.options[e.selectedIndex].value;

      if (indexorder > 0) {
        var firstpart = url.substring(0,indexorder+6);
        var secondpart = url.substring(indexorder+7);
        url = firstpart + order + secondpart;
      } else {
        if (url.indexOf("?") > 0) {
          url += "&order="+order; 
        } else {
          if (url.indexOf("/") > 0) {
            if (url.indexOf("&") > 0) {
              url += "&order="+order;
            } else {
              url += "/&order="+order;
            }
          } else {
            url += "?order="+order;
          }
        }
      }
      window.location = url;
    }


    //function submit from search
    function sub(){
      return true;
    }
    $(document).ready(function(){ 

      if (!$.browser.opera) {

      // select element styling
      $('select.select').each(function(){
        var title = $(this).attr('title');
        if( $('option:selected', this).val() != ''  ) title = $('option:selected',this).text();
        $(this)
       .css({'z-index':10,'opacity':0,'-khtml-appearance':'none'})
       .after('<span class="select">' + title + '</span>')
       .change(function(){
        val = $('option:selected',this).text();
        $(this).next().text(val);
      })
     });

    };
    
  });


    function StarRatingSelectedDefaule(){      
      catArrayStarId = new Array('star-rating-small-cat-1', 'star-rating-small-cat-2', 'star-rating-small-cat-3', 'star-rating-small-cat-4', 'star-rating-small-cat-5', 'star-rating-small-cat-6', 'star-rating-small-rating-1');
      for(var i = 0; i < catArrayStarId.length; i++){
        click_star_small(catArrayStarId[i], 3);
      }
      click_star_small('star-rating-small-rating-2', 2);
    }
    </script>

    <script type="text/javascript" src="/js/jquery.flexslider.js"></script>
    <script type="text/javascript" charset="utf-8">
    $(window).load(function() {
      $('.flexslider').flexslider();
      $('.thickbox').click(function(){
        $('#TB_window').css('margin-left', '-480px');
      });
    });
    </script>

    <!-- Script to autofit categories height-->
    <?php
    include('./menu_autofit.php');
    ?>

    <script type="text/javascript" src="/js/thickbox.js"></script>
    
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  <?php
    echo "ga('create', 'UA-".$l_general[$curlang]["ga_account"]."-1', '".$l_general[$curlang]["domain"]."');\n";
  ?>

  ga('send', 'pageview');

</script>

    <script type="text/javascript">
    function trackOutboundLink(link, category, action) { 
      try { 
      _gaq.push(['_trackEvent', category , action]); 
      } catch(err){}
       
      setTimeout(function() {
      document.location.href = link.href;
      }, 100);
    }
    </script>

    <script type="text/javascript">
    $(window).load(function() {
      var $theDiv = $('#box_MenuLeft.box_where_search');
      <?php $display->display_menu_height(); ?>
    });
    </script>

  </head>

  <body onload="StarRatingSelectedDefaule();">
    <div id="wrapper">
      <div id="header">
        <?php
        include ('./top.php');
        ?>
        <div id="left_page">
          <div id="box_MenuLeft" style="height:325px;display:none;" onmouseout="close_menu(event);" onmouseover="show_menu(event);">
            <ul id="product_categories">

              <?php
              /* Display dropdown menu in detail page */
              $display->display_product_categories();
              ?>

            </ul>
          </div>
          <div id="box_MenuLeft" style="height:auto; border-bottom:none">
            <ul>

              <?php
              /* Display the list of categories and subcategories on the left of the search page */
              $display->display_category();
              ?>

            </ul>
          </div>
          <div class="box_where_search" id="box_MenuLeft">
           <ul>
             <li class="first"><a>Website</a></li>

             <?php
             /* Display the list of website on the left of the search page */
             $display->display_website();
             ?>


          

             <?php
             /* Display price range to the left of the search page */
             $display->display_price_range();
             ?>

             <!--<li class="first"><a href="#">Rating</a></li>

             <?php
             /* Display the list of website on the left of the search page */
             $display->display_rating_list();
             ?>-->

           </ul>
         </div>

         <?php
         $display->display_ad(5);
         ?>

       </div>
       <div id="right_page">
         <div id="box_Right">
          <div id="breadcrumb">

            <?php
            $display->display_bread_crumb();
            ?>

         </div>
         <div class="clear"></div>
         <div id="text_result" style="height:46px;">
           <div style="float:left;margin:7px;"><img src="/img/button_result_search.png" alt="" /></div>

           <?php
           /* Display the top bar of the search result */
           $display->display_top_bar();
           ?>

         </div>
         <div class="clear"></div>
         <div style="margin:14px 0 5px 0px; height:32px;">
           <div id="sort_box">
            <form method="post" action="">  
             <div>
              <select id="order" class="select" onchange="change_order()">

               <?php
               /* Display order option: price increasing, decreasing, website, name */
               $display->display_select_order();
               ?>

             </select>  
           </div>
         </form>
       </div>

       <?php
          $display->display_registration();
        ?>

   </div>

   <?php
   $page = 1;
   if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
    $page = $_GET["page"];
  }
  $display->display_search_result($page);
  ?>


  <div class="line"></div>
  <!--pagination-->
  <div style="margin:5px 0 5px 0; height:30px;">
   <div class="pagination">
    <ul class="pagination-digg">

     <?php
     /* Display page navigation at the bottom of the search page */
     $display->display_page_navigation();
     ?>

   </ul> 
 </div>
</div>
</div>
</div>
<div class="clear"></div>
<div id="content">
  <div class="Products">
    <div class="menuProducts">
      <!-- <div class="menuProductsCornerLeft"></div> -->

      <?php
      /* Display suggested products */
      $display->display_suggested_product();
      ?>
      
    </div> <!--close items-->

  </div> <!--close itemsBox-->           
</div> <!--close Products-->
<div class="AD">

  <?php
  /* Display two advertisements at the bottom of the search page */
  $display->display_ad(6);
  $display->display_ad(7);
  ?>

  <div class="clear"></div>
</div>
</div><!--close Products-->

</div>  <!--close content-->  
<div class="clear"></div>
</div>
<!--close page-->
<?php
include ('./footer.php');
?>
<script type="text/javascript">
  setTimeout(function(){var a=document.createElement("script");
    var b=document.getElementsByTagName("script")[0];
    a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0018/5820.js?"+Math.floor(new Date().getTime()/3600000);
    a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
  </script>
</body>
</html>
<script type="text/javascript">
function format_blur()
{
  if ($('#From').val() == '') {$('#From').val('From');}
  if ($('#To').val() == '') {$('#To').val('To');}


  var from = $('#From').val();    
  from = new String(from);
  from = from.split('.').join('');    
  from = parseInt(from);                
  if($.isNumeric(from))
  {
   var amount = new String(from);
   amount = amount.split("").reverse();

   var output = "";
   for ( var i = 0; i <= amount.length-1; i++ )
   {
    output = amount[i] + output;
    if ((i+1) % 3 == 0 && (amount.length-1) !== i)output = '.' + output;
  }
  $('#From').val(output);
}
else
{
 $('#From').val('From');
}


var to = $('#To').val();
to = new String(to);
to = to.split('.').join('');  
to = parseInt(to);      
if($.isNumeric(to))
{
 if(to > 0)
 {
  var amount1 = new String(to);
  amount1 = amount1.split("").reverse();

  var output1 = "";
  for ( var i = 0; i <= amount1.length-1; i++ )
  {
   output1 = amount1[i] + output1;
   if ((i+1) % 3 == 0 && (amount1.length-1) !== i)output1 = '.' + output1;
 }
 $('#To').val(output1); 
}
else
{
  $('#To').val('To');
}
}
else
{
 $('#To').val('To');
}

}

function format_onfocus(selector,str)
{
  if ($(selector).val() == str){$(selector).val('');}
  var data = $(selector).val();   
    /*data = data.replace("." , "");
    //document.writeln(data);
    data = parseInt(data);
    if($.isNumeric(data))
    {
      var amount = new String(data);
      amount = amount.split("").reverse();
    
      var output = "";
      for ( var i = 0; i <= amount.length-1; i++ )
      {
        output = amount[i] + output;
        if ((i+1) % 3 == 0 && (amount.length-1) !== i)output = '.' + output;
      }
      $(selector).val(output);
    }*/
    if ($(selector).val() != str){
      $(selector).val(data);
    }
  }
  </script>
