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

//function jquery UI
$(function() {
  $( "#tabs" ).tabs();
});

function StarRatingSelectedDefaule(){
  catArrayStarId = new Array('star-rating-1', 'star-rating-2', 'star-rating-3', 'star-rating-4', 'star-rating-5');
  for(var i = 0; i < catArrayStarId.length; i++){
    click_star(catArrayStarId[i], 3);
  }
  catArrayStarId = new Array('star-rating-small-1', 'star-rating-greatproduct-1', 'star-rating-greatproduct-2', 'star-rating-greatproduct-3', 'star-rating-review-1');
  for(var i = 0; i < catArrayStarId.length; i++){
    click_star_small(catArrayStarId[i], 3);
  }
}

function record(id, website) {
  $.ajax({ url: "record.php?id="+id+"&website="+website});

}

$(window).load(function() {
  // The slider being synced must be initialized first
  $('#carousel').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    itemWidth: 46,
    itemMargin: 5,
    asNavFor: '#slider',  
    directionNav: false
  });

  $('#slider').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    sync: "#carousel",
    directionNav: false
  });

  //edit css popup
  $('.thickbox').click(function(){
    $('#TB_window').css('margin-left', '-480px');
  });
});

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

var _vwo_code=(function(){
  var account_id=57919,
  settings_tolerance=2000,
  library_tolerance=2500,
  use_existing_jquery=false,
f=false,d=document;return{use_existing_jquery:function(){return use_existing_jquery;},library_tolerance:function(){return library_tolerance;},finish:function(){if(!f){f=true;var a=d.getElementById('_vis_opt_path_hides');if(a)a.parentNode.removeChild(a);}},finished:function(){return f;},load:function(a){var b=d.createElement('script');b.src=a;b.type='text/javascript';b.innerText;b.onerror=function(){_vwo_code.finish();};d.getElementsByTagName('head')[0].appendChild(b);},init:function(){settings_timer=setTimeout('_vwo_code.finish()',settings_tolerance);this.load('//dev.visualwebsiteoptimizer.com/j.php?a='+account_id+'&u='+encodeURIComponent(d.URL)+'&r='+Math.random());var a=d.createElement('style'),b='body{opacity:0 !important;filter:alpha(opacity=0) !important;background:none !important;}',h=d.getElementsByTagName('head')[0];a.setAttribute('id','_vis_opt_path_hides');a.setAttribute('type','text/css');if(a.styleSheet)a.styleSheet.cssText=b;else a.appendChild(d.createTextNode(b));h.appendChild(a);return settings_timer;}};}());_vwo_settings_timer=_vwo_code.init();
