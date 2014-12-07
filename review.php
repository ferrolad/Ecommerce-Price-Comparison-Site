<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="robots" content="noindex,nofollow"/>
	<style type="text/css">
	<!--
	body{
		margin-top:0px;
	}
	.style1 {
		color: #FFFFFF;
		font-family:Arial, Helvetica, sans-serif;
		font-size:18px;
		/*text-shadow: 0px 1px 1px #cccccc;*/
	}
	.style2 {
		color: #f17106;
		font-family:Arial, Helvetica, sans-serif;
		font-size:18px;
	}
	.text_form {
		color: #2f2f2f;
		font-family:Arial, Helvetica, sans-serif;
		font-size:13px;
		font-weight:bold;
	}
	.input_form {
		color: #545454;
		height:28px;
		line-height:28px;
		font-family:Arial, Helvetica, sans-serif;
		font-size:16px;
		border: 1px solid #ABADB3;
		vertical-align: middle;
		-webkit-border-radius: 2px;
		-moz-border-radius: 2px;
		border-radius: 2px;
		margin:3px 0;
		width:250px;
	}

	.radio {
		height: 25px;
		width: 18px;
		clear:left;
		float:left;
/*	margin: 0 0 3px;
padding: 0 0 0 26px;*/
background: url("/img/radio.png");
background-repeat:no-repeat;
cursor: default;
}
.checkbox input,.radio input {
	display: none;
}

/* setting the width and height of the SELECT element to match the replacing graphics */
select.select{
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#545454;
	position:relative;
	z-index:10;
	width:72px !important;
	height:28px !important;
	line-height:28px;
	margin-right:10px;
}
select.select_year{
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#545454;
	position:relative;
	z-index:10;
	width:90px !important;
	height:28px !important;
	line-height:28px;
	margin-right:0px;
}
/* all form DIVs have position property set to relative so we can easily position newly created SPAN */
form div{position:relative;} 
/* dynamically created SPAN, placed below the SELECT */
span.select{
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#545454;
	position:absolute;
	bottom:0;
	float:left;
	left:0;
	width:75px;
	height:28px;
	line-height:28px;
	text-indent:12px;
	background:url(/img/bg_select_birthday.png) no-repeat 0 0;
	cursor:default;
	z-index:1;
	margin-right:3px;
}
span.select_year{
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#545454;
	position:absolute;
	bottom:0;
	float:left;
	left:0;
	width:90px;
	height:28px;
	line-height:28px;
	text-indent:12px;
	background:url(/img/bg_select_birthday_year.png) no-repeat 0 0;
	cursor:default;
	z-index:1;
	margin-right:0px;
}
.sub_form {
	color: #FFFFFF;
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	font-weight:bold;
	background:url(/img/bg_signup_subscription.png) no-repeat;
	/*-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
	border-radius: 2px;*/
	width:150px;
	height:33px;
	border:none;
}
.shadow_style {
	color: #f17106;
	font-family:Arial, Helvetica, sans-serif;
	font-size:15px;
	/*text-shadow: 0px 1px 1px #cccccc;*/
}
.shadow_style1 {
	color: #FFFFFF;
	font-family:Arial, Helvetica, sans-serif;
	font-size:21px;
	/*text-shadow: 0px 1px 1px #cccccc;*/
}
-->
</style>
<script src="/js/jquery-1.8.2.js"></script>
<script src="/js/jquery.custom_radio_checkbox.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$(".radio").dgStyle();
	// select element styling
	/*$('select.select').each(function(){
		var title = $(this).attr('title');
		if( $('option:selected', this).val() != ''  ) title = $('option:selected',this).text();
		$(this)
			.css({'z-index':10,'opacity':0,'-khtml-appearance':'none'})
			.after('<span class="select">' + title + '</span>')
			.change(function(){
				val = $('option:selected',this).text();
				$(this).next().text(val);
				})
});*/	

	//select year
	$('select.select_year').each(function(){
		var title = $(this).attr('title');
		if( $('option:selected', this).val() != ''  ) title = $('option:selected',this).text();
		$(this)
		.css({'z-index':10,'opacity':0,'-khtml-appearance':'none'})
		.after('<span class="select_year">' + title + '</span>')
		.change(function(){
			val = $('option:selected',this).text();
			$(this).next().text(val);
		})
	});
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
</script>

<!-- Start Alexa Certify Javascript -->
<script type="text/javascript">
_atrk_opts = { atrk_acct:"UrfYh1aUXR00i8", domain:"oizoioi.vn",dynamic: true};
(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
</script>
<noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=UrfYh1aUXR00i8" style="display:none" height="1" width="1" alt="" /></noscript>
<!-- End Alexa Certify Javascript -->

</head>
<body>
	<div style="width: 60%; float:left">
		<form id="form1" name="f_subscription" method="post" action="">
			<table width="60%" border="0" cellpadding="0" cellspacing="0" style="margin-left:30px;">
				<!--<tr>
					<td colspan="6" align="left"><img src="/img/logo.png" width="188" style="margin-bottom:10px; margin-left:-2px;" /></td>
				</tr>-->
				<tr>
					<td colspan="6" id="result"><span class="shadow_style" >Review <?php echo $_GET["name"]; ?></span> </td>
				</tr>
				<tr id="name_form">
					<td width="70" class="text_form" id="name_text">Name</td>
					<td colspan="5">
						<label>
							<input name="name" type="text" class="input_form" id="name" size="30" width="250px;"  style="margin-left: 15px;margin-top:10px;" />
						</label>      </td>
					</tr>

					<tr id="title_form">
						<td width="70" class="text_form" id="title_text">Title</td>
						<td colspan="5">
							<label>
								<input name="title" type="text" class="input_form" id="title" size="30" width="250px;"  style="margin-left: 15px;margin-top:10px;" />
							</label>      </td>
						</tr>


						<tr id="content_form">
							<td class="text_form" id="content_text">Review</td>      
							<td colspan="5"><label>
								<!--<input name="content" type="text" class="input_form" id="content" size="30" style="margin-left:15px;height:70px;width:450px" />-->
								<textarea name="content" cols="80" rows="10" class="input_form" id="content" style="margin-left:15px;height:70px;width:450px">
								</textarea>
							</label></td>
						</tr>

						<tr width="70" id="rating_form">
							<td colspan="50" class="text_form">Rating
								<select name="rating" style="margin-left:17px">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select>
							</td>
						</tr>

						<tr>
							<td>&nbsp;</td>
							<td colspan="5"><input class="sub_form" type="button" name="button" id="button" value="Submit" onclick="submit_func()" style="margin-left:15px;margin-top:10px;" /></td>
						</tr>
					</table>
				</form>
			</div>
			<div style="width: 40%; float:right">
				<?php echo "<img src=\"".$_GET["image"]."\"/>"; ?>
			</div>
		</body>
		</html>
		<script type="text/javascript">
		function getParameterByName(name) {
			name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
			return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}

		function submit_func()
		{
			var flag = true;
			var name = $('#name').val();
			var title = $('#title').val();
			var content = $('#content').val();
			var rating = $("select").val();
			var id = getParameterByName("id");
			var website = getParameterByName("website");

			if(name == '')
			{
				$('#name_text').css("color" , "#F17106");
				$('#name').css("border-color" , "#F17106");
				flag = false;
			}

			if(title == '')
			{
				$('#title_text').css("color" , "#F17106");
				$('#title').css("border-color" , "#F17106");
				flag = false;
			}

			if(content == '')
			{
				$('#content_text').css("color" , "#F17106");
				$('#content').css("border-color" , "#F17106");
				flag = false;
			}

			if(flag)
			{
				$('#result').html('<span class="shadow_style">Thank you for your review on our product. Your opinion has been recorded.</span>');
				$('#name_form').css('display' , 'none');
				$('#title_form').css('display' , 'none');
				$('#content_form').css('display' , 'none');
				$('#rating_form').css('display' , 'none');

				var xmlhttp;
				if (window.XMLHttpRequest)
				{// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				}
				else
				{// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange=function()
				{
					if (xmlhttp.readyState==4 && xmlhttp.status==200)
					{
						document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
						$('#result').html(xmlhttp.responseText);
					}
				}
				xmlhttp.open("GET","/CMS/addReview.php?name="+name+"&title="+title+"&content="+content+"&rating="+rating+"&id="+id+"&website="+website,true);
				xmlhttp.send();
			}

			return flag;
		}
		</script>