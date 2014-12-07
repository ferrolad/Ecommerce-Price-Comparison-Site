////////Register form//////
function popupRegister(){
  jQuery('body').append('<div id="popup-register"><div class="register-form"><p class="btn-close"><a href="javascript:void(0);"></a></p><div class="register-form-inner"><div class="popcontainer"><form id="formSub-reg" method="post" class="formreg" action=""><p id="error-reg" class="error"></p><p><input type="text" class="inputbox" id="fullname-reg" name="fullname-reg" onfocus="if (this.value ==\'Full name\') this.value=\'\'" onblur="if (this.value ==\'Full name\') this.value=\'\'" value="Full name"><input type="text" id="email-reg" name="email-reg" onfocus="if (this.value ==\'Email...\') this.value=\'\'" onblur="if (this.value ==\'Email...\') this.value=\'\'" value="Email..." class="inputbox"> <select id="gender-reg" name="gender-reg"> <option value="" disabled="disabled" selected="selected">Gender</option> <option value="female">Female</option> <option value="male">Male</option> </select>  </p> <p class="btn"><input type="button" name="btn-subscribe" title="Sign Up" value="Sign Up" id="btn-subscribe" onclick="checkFormPopup();" class="button"><img alt="" style="display:none" class="loading" name="imgLoading" id="imgLoading-reg" src="http://oizoioi.vn/img/loading_icon_small.gif"></p>  </form></div></div></div><div class="register-button open"></div>');

  $('.hpsbnlbx').css('display', 'none');

  var $closeBox = function() { 
    $('.register-form').animate({
      height: '0px',
    }, 600, function() {
            // Animation complete.
            $('.register-button').removeClass('open');
            // set cookie
            setCookie('popupRegister', 'close');            
          });
  };

  var $openBox = function() {
    $('.register-form').animate({ 
      height: '271px'
    }, 600, function() {
            // Animation complete.
            $('.register-button').addClass('open');
            // set cookie
            setCookie('popupRegister', 'open');
            $("#banner-close").trigger('click');
          });	
  };

  $('.register-button').click(function(){	
    if ( $(this).hasClass('open') ){
      $closeBox();
    } else {
      $openBox();
    }
  });

  $('.register-form .btn-close').find('a').click(function(){
    $closeBox();	
  });

  if (getCookie('popupRegister') == 'close') {
    $closeBox();
  } else {
    $openBox();
  }
}

/////////////
function checkFormPopup() {   
  var fullname= jQuery("#fullname-reg").val();
  var gender = jQuery("#gender-reg").val();
  var emailVal = jQuery("#email-reg").val();

  jQuery("#error-reg").show();
  if(fullname == "" || fullname =="Full Name"){          
    jQuery("#error-reg").html("Please fill in your full name!");
    jQuery("#fullname-reg").focus();
    return false;
  }

  if(emailVal == ""){
   jQuery("#error-reg").html("Please fill in your Email!");
   jQuery("#email-reg").focus();
   return false;
 } else { 
  var email = jQuery("#email-reg");
  var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  if (!filter.test(email.val())) {
    jQuery("#error-reg").html("Wrong Email format!");                
    email.focus;
    return false;
  }
}
if(gender == ""){
  jQuery("#error-reg").html("Please fill in your Gender!");
  jQuery("#gender-reg").focus();
  return false;
}
var purl     = "http://oizoioi.ph/CMS/addCustomer.php?firstname=" + fullname + "&gender=" + gender + "&email=" + emailVal + "&lastname=empty&callback=?";
var obj = document.getElementById("imgLoading-reg");        
var objRegister = document.getElementById("btn-subscribe");

obj.style.display="block";
objRegister.disabled = true;
jQuery('#btn-subscribe').addClass('disabled');
jQuery("#error-reg").html('');
jQuery.getJSON(purl,
 function(data){
  if (data.val == '1' || data.val == 1){ 
     obj.style.display="none";                      
     jQuery("#formSub-reg").html('<div style="font-size:12px; text-align:center; padding-top:10px"><a alt="Đóng cửa sổ này" class="btn-close" href="javascript:void(0);" onclick="closeForm()"></a><p style="font-size: 12px;">Thank you for signing up with us!</p></div>');
     setCookie('Success-register', 1);
     setTimeout(function(){
      $('.register-form').animate({
       height: '0px',
     }, 1000, function() {
       $('.register-button').css('display', 'none');								
     });
    },1200);

   }else {
     jQuery("#error-reg").show();
     jQuery("#error-reg").html(data);
     jQuery('#btn-subscribe').removeClass('disabled');
     objRegister.disabled = false;
     obj.style.display="none";                    
   }
 });      
}

function setCookie(c_name,value)
{
	document.cookie=c_name + "=" + value;
}
function getCookie(c_name)
{
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	{
   x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
   y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
   x=x.replace(/^\s+|\s+$/g,"");
   if (x==c_name)
   {
     return unescape(y);
   }
 }
}
//////////////
$(document).ready(function(){	
	/* register form */
	var isRegister = getCookie("Success-register");
	if (isRegister == "" || isRegister == null) {
		popupRegister();
	}

});