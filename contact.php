<?php
include "./display/contact_display.php";      
include "./CMS/include/database_config.php";
$display = new contact_display();      
$current_language = $lang->current_language();
$display->SetConfig($default_hostname, $default_username, $default_password, $default_db, $current_language);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>    
  <meta name="robots" content="noindex">
  <title> Compare Prices of More Than 100,000 Products </title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content="You search for cheap offers and want to buy at the best prices? Then you found the right place at www.pricepony.com.ph. Compare the offers of numerous online retailers – from cheap tablets and electronics to home appliances to Sports and Fashion and everything for your entertainment"/>
  <meta name="keywords" content="price, comparison, reviews, buy"/>
  <meta name="robots" content="index, follow" />
  
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"> 

  <meta property="og:title" content="Contact Us - Pricepony Philippines"/>
  <meta property="og:description" content="You search for cheap offers and want to buy at the best prices? Then you found the right place at www.price.com.ph. Compare the offers of numerous online retailers – from cheap tablets and electronics to home appliances to Sports and Fashion and everything for your entertainment"/> 

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

    <script type="text/javascript" src="js/jquery.flexslider.js"></script>
    <script type="text/javascript" charset="utf-8">
    $(window).load(function() {
      $('.flexslider').flexslider();
    });
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
           <span class="youah"><a href="/">Home Page</a></span><span class="pathway separator"><img src="img/icon_arrow_grey.png" alt="" /></span>
           <span class="pathway last">Contact Us</span>
         </div>
       </div>
     </div>
     <div class="clear"></div>
     <div id="content">
      <div class="Products" style="margin:0">
       <div class="menuProducts">
        <div class="title" style="width:952px"><h1>Contact Us</h1></div>
      </div>
      <div class="itemsBox" style="height:1382px; width:958px; font-family:Arial, Helvetica, sans-serif;font-size:12px;line-height:18px; margin-bottom:10px;">
        <div style="float:left; width:380px; margin:25px 2px 20px 20px;">
          <span class="text_yellow" style="font-style:italic;font-weight:bold" id="message">Do you want to get your E-Commerce Store Listed at Pirce Pony? We Help you sell! Contact us at sales@pricepony.com.ph</span><br/><br/>
          <span class="text_yellow" style="font-style:italic;font-weight:normal" id="message">If you have any comments and complaints about Pirce Pony, please send them to us by filling out the form below</span>
          <form id="form1" name="form1" method="post" action="" style="margin-top:8px;">
            <table width="100%" border="1" cellspacing="10" cellpadding="0" id="form_content">
              <tr>
                <td class="text_form" id="ffullname_text">Name:</td>
              </tr>
              <tr>
                <td><label>
                  <input name="textfield" type="text" class="input_form" id="ffullname" size="45" />
                </label></td>
              </tr>
              <tr>
                <td class="text_form" id="femail_text">Email:</td>
              </tr>
              <tr>
                <td><label>
                  <input name="textfield2" type="text" class="input_form" id="femail" size="45" />
                </label></td>
              </tr>
              <tr>
                <td class="text_form" id="faddress_text">Address</td>
              </tr>
              <tr>
                <td><label>
                  <input name="textfield3" type="text" class="input_form" id="faddress" size="45" />
                </label></td>
              </tr>
              <tr>
                <td class="text_form" id="ftitle_text">Title</td>
              </tr>
              <tr>
                <td><label>
                  <input name="textfield4" type="text" class="input_form" id="ftitle" size="45" />
                </label></td>
              </tr>
              <tr>
                <td class="text_form" id="fdescription_text">Detail:</td>
              </tr>
              <tr>
                <td><label>
                  <textarea name="textarea" id="fdescription" cols="43" rows="5" class="area_form"></textarea>
                </label></td>
              </tr>
              <tr>
                <td><label>
                  <input type="button" name="button" id="button" value="Send" class="sub_form_contact" style="float:right; margin-right:1px;" onclick="submit_func()" />
                </label></td>
              </tr>
            </table>
          </form>
          <p>&nbsp;</p>
        </div>
        <div style="float:right; width:515px; margin:25px 20px 20px 2px;">
         <p class="text_yellow_uppercase" style="font-weight:bold; font-size:15px;">About Pricepony               	</p>
         <p class="text_yellow_uppercase">&nbsp;</p>
         <p>You search for cheap offers and want to buy at the best prices? Then you found the right place at www.pricepony.com.ph. Compare the offers of numerous online retailers – from cheap tablets and electronics to home appliances to Sports and Fashion and everything for your entertainment. Above that you find a lot of helpful information at our site to help you with your buying decision. We publish not only the product information but also Retailer ratings, product tests and customers review</p>
         <p>&nbsp;</p>
         <p>All of the listed retailers on www.pricepony.com.ph have been tested by a special team for their security and their service. It is very important for us that our partner retailers take maximum care for their customer satisfaction and the Quality of their shop. That’s why we check the actuality of their offers regularly. Should you have any problems though, please contact us on support@pricepony.com.ph</p>
         <p>&nbsp;</p>
         <p>A good online retailer is characterized by the following points:</p>
         <p>&nbsp;</p>
         <ul>
           <li>Fair Shipping Costs</li>
           <li>Secure Payment Methods</li>
           <li> Always available for customers</li>
           <li> Error-free ordering process in the online shop</li>
           <li> Short delivery times</li>
           <li> Comply with legal requirements</li>
           <li> Excellent Customer Service</li>
           <li> User-friendly store design</li>
         </ul>
         <p>&nbsp;</p>
         <p>Shop ratings by our visitors</p>
         <p>&nbsp;</p>
         <p>Last but not least we show you the ratings of other Pricepony users about the quality of the listed online shops. In our shop ratings you can see in detail which experiences the users made with the different online shops on pricepony.com.ph.</p>
         <p>&nbsp;</p>
         <p>Shop ratings</p>
         <p>The more stars a shop has been given, the better rating he got by our users. Please check as well how many positive user ratings the shops got and how actual these ratings are. The more users wrote down their positive experiences with the online shops the more you can rely on the online shops for delivery, payments and the order process. 
          Did you have good or bad experiences with online shops? With a few clicks you can share those impressions with our other users and make sure that we continue to only list the best online shops in the philippines on pricepony.com.ph</p>
          <p>&nbsp;</p>
          <p>Product Reviews and ratings</p>
          <p>We will also show you the ratings of Pricepony users who actually ordered the product before you. The more starts a product has the more positive user ratings a product got. User ratings and reviews are often the best way to see if a product has a high quality and will fit your needs since users are independent and often more honest in their product reviews then the brands and producers</p>
          <p>&nbsp;</p>
          <p>Secure Payment Methods</p>
          <p>&nbsp;</p>
          <p>Online Shopping is easy: You have a huge choice of products and with a few clicks you can fill your shopping basket. When its time to pay and the online shops list the multiple payment methods a lot of users cancel their shopping. Either they don't like the payment methods or maybe don't even know them. pricepony.com.ph brings light into the payment darkness - which attributes should a good payment method have?</p>
          <p>&nbsp;</p>
          <p>A payment system should be secure but easy and comfortable to use at the same time. As well its very important how often a payment system is offered online and how high the transaction volume is.</p>
          <p>&nbsp;</p>
          <p>We can recommend the following payment methods:</p>
          <ul>
            <li> Bank transfer</li>
            <li> Cash on delivery</li>
            <li> Credit Card</li>
            <li> Paypal</li>
          </ul>
          <p>Please take care - Some online retailers take an extra charge for cash on delivery.</p>
          <p>&nbsp;</p>
          <p>We can not recommend the payment method of paying before delivery.</p>
          <p>&nbsp;</p>
          <p>When you pay your order before delivery it means that the danger exists that you actually will never get your order delivered. As well a lot of smaller retailers collect orders before they order themselves at wholesalers. This can take some time and is less then comfortable for the customer. Last but not least, if the online retailer should go broke its often not possible to get back the money you transferred, so don't take the risk and only pay after you have got your order convenient at your home.</p>
          <p>&nbsp;</p>

        </div>
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
<script type="text/javascript">
function submit_func()
{
  var flag = true;

  var fullname = $('#ffullname').val();
  var email = $('#femail').val();		
  var address = $('#faddress').val();
  var title = $('#ftitle').val();
  var description = $('#fdescription').val();

  if(fullname == '')
  {
   $('#ffullname_text').css("color" , "#F17106");
   $('#ffullname').css("border-color" , "#F17106");
   flag = false;
 }

 if(address == '')
 {
   $('#faddress_text').css("color" , "#F17106");
   $('#faddress').css("border-color" , "#F17106");
   flag = false;
 }

 if(email == '')
 {
   $('#femail_text').css("color" , "#F17106");
   $('#femail').css("border-color" , "#F17106");
   flag = false;
 }
 else
 {
   var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
   var checker = pattern.test(email);
   if(!checker)
   {
    $('#femail_text').css("color" , "#F17106");
    $('#femail').css("border-color" , "#F17106");
    $('#femail').val('');
    flag = false;
  }			
}

if(title == '')
{
 $('#ftitle_text').css("color" , "#F17106");
 $('#ftitle').css("border-color" , "#F17106");
 flag = false;
}

if(description == '')
{
 $('#fdescription_text').css("color" , "#F17106");
 $('#fdescription').css("border-color" , "#F17106");
 flag = false;
}

if(flag)
{
 $('#form_content').css('display' , 'none');
 $('#message').html('Thank you for contacting us! We will review the message and try out best to get back to you as soon as we can.');		
}
}
</script>
