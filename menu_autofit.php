<script src="/js/custom-form-elements.js"></script>
<script src="/js/jquery.flexslider.js"></script>
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
            jQuery(product_categories[i]).css('line-height', '35px');
          }
        }
      });
</script>