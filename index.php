<?php
include "./display/index_display.php";      
include "./CMS/include/database_config.php";
$display = new index_display();  
$display->SetConfig($default_hostname, $default_username, $default_password, $default_db, $lang);
$l_general = $lang->general_term();

// seconds, minutes, hours, days
$expires = 60*60*24*14;
header("Pragma: public");
header("Cache-Control: maxage=".$expires);
header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="google-site-verification" content="bzakq02T2XprntzCFhJC1hry_Ah2TvoGQnlLs78G-No" />
  <?php
    $display->display_head();
    include('./menu_autofit.php');
  ?>
  
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45634301-1', 'pricepony.com.ph');
  ga('send', 'pageview');

</script>
</head>

<body>
  <div id="wrapper">
    <!--<script src="/js/popup.js" type="text/javascript"></script>-->

    <?php
    $display->display_floating_banner();
    ?>
    <script type="text/javascript">
    $('.floating-banner-left').css('left', $('#wrapper').offset().left - 200);
    $('.floating-banner-left').css('top', $('#wrapper').offset().top + 130);
    $('.floating-banner-right').css('left', $('#wrapper').offset().left + 950);
    $('.floating-banner-right').css('top', $('#wrapper').offset().top + 130);

    </script>

    <div id="header">
      <?php
      include ('./top.php');
      ?>
      <div id="box_MenuLeft_Homepage">
        <ul id="product_categories">

          <?php
          /* Display product categories */
          $display->display_product_categories();
          ?>

        </ul>
      </div>
      <div id="boxCenter">
        <div class="slideShow">
          <div class="flexslider" id="flexslider" style="width:550px">
            <ul class="slides">

              <?php
              /* Display slider images */
              $display->display_slider_images();
              ?>

            </ul>
          </div>
        </div>

        <?php
          $display->display_registration();
        ?>

      </div>
      <div id="boxRightAD">
        <ul>

          <?php
          /* Display ad on homepage first top right */
              //$display->display_ad(1);

          /* Display ad on homepage second top right */
              //$display->display_ad(2);
          ?>
          <li style="height:316px; width:205px">
            <!-- <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FOizoioi%2F298270840275554%3Ffref%3Dts&amp;width=200&amp;height=316&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;show_border=true&amp;header=true&amp;appId=286001391479170" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:316px;" allowTransparency="true"></iframe> -->
             <a href="http://www.lazada.com.ph/#Q1811C5n14.g418" rel="nofollow"><img src="http://www.pricepony.com.ph/banner.jpg" height="316" width="205" border="0" alt="Lazada 300x250 2" /></a>
          </li>

        </ul>
      </div>
    </div>
    <div class="clear"></div>
    <div id="content">
      <div class="Products">
        
          <?php
          /* Display new products */
          $display->display_new_product();
          ?>

        </div> <!--close itemsBox-->


      </div> <!--close Products-->
      <div class=" clear"></div>
      <div class="Products">

          <?php
          /* Display popular and hot deals products */
          $display->display_popular_and_hot_deals();
          ?>

        </div> <!--close itemsBox-->           
      </div> <!--close Products-->
      <div class="AD">

        <?php
        /* Display ad on homepage first bottom */
        $display->display_ad(3);

        /* Display ad on homepage second bottom */
        $display->display_ad(4);
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