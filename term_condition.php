<?php
include "./display/term_condition_display.php";      
include "./CMS/include/database_config.php";
$display = new term_condition_display();       
$current_language = $lang->current_language();
$display->SetConfig($default_hostname, $default_username, $default_password, $default_db, $current_language);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>    
  <title> Compare Prices of More Than 100,000 Products </title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content="You search for cheap offers and want to buy at the best prices? Then you found the right place at www.pricepony.com.ph. Compare the offers of numerous online retailers – from cheap tablets and electronics to home appliances to Sports and Fashion and everything for your entertainment"/>
  <meta name="keywords" content=""/>
  <meta name="robots" content="index, follow" />
  
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"> 
  <link rel="canonical" href="http://www.pricepony.com.ph/privacy_policy.html"/>

  <meta property="og:title" content="Terms and Conditions - Pricepony Philippines"/>
  <meta property="og:description" content="You search for cheap offers and want to buy at the best prices? Then you found the right place at www.pricepony.com.ph. Compare the offers of numerous online retailers – from cheap tablets and electronics to home appliances to Sports and Fashion and everything for your entertainment"/> 

  <link rel="stylesheet" type="text/css" href="css/reset.css" />
  <link rel="stylesheet" type="text/css" href="css/styles.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="css/thickbox.css" />
    <!--[if IE]>
    <link href="css/ie.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <script src="js/jquery-1.8.2.js"></script>
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

	  //function submit from search
	  function sub(){
      return true;
    }
    </script>

    <!-- Script to autofit categories height-->
    <script src="js/custom-form-elements.js"></script>
    <script src="js/jquery.flexslider.js"></script>
    <script type="text/javascript" charset="utf-8">
    $(window).load(function() {
        //Flex slider
        $('.flexslider').flexslider();    
        //Custom form elements
        Custom.init;    
        //Edit css popup
        $('.thickbox').click(function(){
          $('#TB_window').css('margin-left', '-480px');
        });
        var product_categories_height = 315;
        var product_categories = jQuery('#product_categories').children();
        if(product_categories.length < 12){
          for(var i = 0; i < product_categories.length; i ++ ){
            jQuery(product_categories[i]).css('height', (product_categories_height/product_categories.length - 9) + 'px');
          }
        }
      });
    </script>

    <script type="text/javascript" src="js/jquery.flexslider.js"></script>
    <script type="text/javascript" charset="utf-8">
    $(window).load(function() {
      $('.flexslider').flexslider();
    });
    </script>

    <!-- Script to autofit categories height-->      
    <?php
    include('./menu_autofit.php');
    ?>
    <script type="text/javascript" src="js/thickbox.js"></script>
    <script type="text/javascript" src="js/custom-function.js"></script>
  </head>

  <body>
    <div id="wrapper">
      <div id="header">
        <?php
        include ('./top.php');
        ?>
        <div id="box_MenuLeft" style="height:325px;display:none;" onmouseout="close_menu(event);" onmouseover="show_menu(event);">
          <ul id="product_categories">
            <?php
            /* Display product categories */
            $display->display_product_categories();
            ?>

          </ul>
        </div>
        <div id="content_page_detail">
         <div id="breadcrumb">
          <span class="youah"><a href="/">Homepage</a></span><span class="pathway separator"><img src="img/icon_arrow_grey.png" alt="" /></span>
           <span class="pathway last">Terms and Conditions</span>
         </div>
       </div>
     </div>
     <div class="clear"></div>
     <div id="content">
      <div class="Products" style="margin:0">
       <div class="menuProducts">
        <div class="title" style="width:952px">
          <h1>Terms and Conditions</h1>
        </div>
      </div>
      <div class="itemsBox" style=" width:938px; height:auto;padding:10px 10px 0px 10px; margin-bottom:10px; font-family:Arial, Helvetica, sans-serif; font-size:12px;color:#545454;">
        <p>Welcome to Pricepony. These terms and conditions ("Terms and Conditions") apply to the Site and all of its divisions, subsidiaries, and affiliate operated Internet sites which reference these Terms and Conditions.              </p>
        <p>&nbsp;</p>
        <p>You confirm your understanding of the Terms and Conditions by accessing this Site. You shall not use this website if you disagree with the Terms and Conditions of use. The Site reserves the right, to change, modify, add, or remove portions of these Terms and Conditions of use at any time. Changes will be effective when posted on the Site with no other notice provided. Please check these Terms and Conditions of use regularly for updates. Your continued use of the Site following the posting of changes to these Terms and Conditions of use constitutes your acceptance of those changes.              </p>
        <p>&nbsp;</p>
        <h1><span class="text_yellow_uppercase">USE OF THE SITE</span></h1>
        <p>&nbsp;</p>
        <p>You must be at least 18 years of age or access the Site under the supervision of a parent or legal guardian.              </p>
        <p>&nbsp;</p>
        <p>We grant you a non-transferable and revocable license to use the Site, under the Terms and Conditions described, for the purpose of procuring personal items sold on the Site. Commercial use or use on behalf of any third party is forbidden, except as explicitly permitted by us in advance. Any breach of these Terms and Conditions shall result in the immediate revocation of the license granted in this paragraph without notice to you.              </p>
        <p>&nbsp;</p>
        <p>Content provided on this site is solely for informational purposes. Product representations expressed on this Site are those of the vendor and are not made by us. Submissions or opinions expressed on this Site are those of the individual posting such content and may not reflect our opinions.              </p>
        <p>&nbsp;</p>
        <p>Certain services and related features that may be made available on the Site may require registration or subscription. Should you choose to register or subscribe for any such services or related features, you agree to provide accurate and current information about yourself, and to promptly update such information if there are any changes. Every user of the Site is solely responsible for keeping passwords and other account identifiers safe and secure. The account owner is entirely responsible for all activities that occur under such password or account. Furthermore, you must notify us of any unauthorized use of your password or account. The Site shall not be responsible or liable, directly or indirectly, in any way for any loss or damage of any kind incurred as a result of, or in connection with, your failure to comply with this section.              </p>
        <p>&nbsp;</p>
        <p>During the registration process you agree to receive promotional emails from the Site. You can subsequently opt out of receiving such promotional e-mails by clicking on the link at the bottom of any promotional email.              </p>
        <p>&nbsp;</p>
        <p><span class="text_yellow_uppercase">USER SUBMISSIONS</span></p>
        <p>&nbsp;</p>
        <p>Anything that you submit to the Site and/or provide to us, including but not limited to, questions, reviews, comments, and suggestions (collectively, "Submissions") will become our sole and exclusive property and shall not be returned to you. In addition to the rights applicable to any Submission, when you post comments or reviews to the Site, you also grant us the right to use the name that you submit, in connection with such review, comment, or other content. You shall not use a false e-mail address, pretend to be someone other than yourself or otherwise mislead us or third parties as to the origin of any Submissions. We may, but shall not be obligated to, remove or edit any Submissions.</p>
        <p>&nbsp;</p>
        <p><span class="text_yellow_uppercase">ORDER ACCEPTANCE AND PRICING</span></p>
        <p>&nbsp;</p>
        <p>Please note that there are cases when an order cannot be processed for various reasons. The Site reserves the right to refuse or cancel any order for any reason at any given time. You may be asked to provide additional verifications or information, including but not limited to phone number and address, before we accept the order.                </p>
        <p>&nbsp;</p>
        <p>We are determined to provide the most accurate pricing information on the Site to our users; however, errors may still occur, such as cases when the price of an item is not displayed correctly on the website. As such, we reserve the right to refuse or cancel any order. In the event that an item is mispriced, we may, at our own discretion, either contact you for instructions or cancel your order and notify you of such cancellation. We shall have the right to refuse or cancel any such orders whether or not the order has been confirmed and your credit card charged. </p>
        <p>&nbsp;</p>
        <p><span class="text_yellow_uppercase">TRADEMARKS AND COPYRIGHTS</span></p>
        <p>&nbsp;</p>
        <p>All intellectual property rights, whether registered or unregistered, in the Site, information content on the Site and all the website design, including, but not limited to, text, graphics, software, photos, video, music, sound, and their selection and arrangement, and all software compilations, underlying source code and software shall remain our property. The entire contents of the Site also are protected by copyright as a collective work under Philippine copyright laws and international conventions. All rights are reserved.                </p>
        <p>&nbsp;</p>
        <p><span class="text_yellow_uppercase">APPLICABLE LAW AND JURISDICTION</span></p>
        <p>&nbsp;</p>
        <p>These Terms and Conditions shall be interpreted and governed by the laws in force in Manila, Philippines. Subject to the Arbitration section below, each party hereby agrees</p>
        <p>&nbsp;</p>
      </div>
    </div>
    <!--close Products-->
  </div>  <!--close content-->  
  <div class="clear"></div>
</div>
<!--close page-->
<?php
include ('./footer.php');
?>
</body>
</html>
