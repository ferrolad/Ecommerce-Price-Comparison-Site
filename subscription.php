<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
	font-size:27px;
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
</head>
<body>  
	<form id="form1" name="f_subscription" method="post" action="">
		<table width="50%" border="0" cellpadding="0" cellspacing="0" style="margin-left:30px;">
			<tr>
				<td colspan="6" align="left"><img src="/img/logo.png" width="188" style="margin-bottom:10px; margin-left:-2px;" /></td>
			</tr>
			<tr>
				<td colspan="6"><span class="style1">To be one of the first to receive newsletter of big sales, campaigns and low price products.</span></td>
			</tr>
			<tr>
				<td colspan="6" id="result"><span class="shadow_style1" >Sign up for Oizoioi newsletter</span> <span class="shadow_style" style="font-size:27px; font-weight:bold">RIGHT NOW!</span></td>
			</tr>
			<tr id="first_name">
				<td width="70" class="text_form" id="f_name_text">First Name</td>
				<td colspan="5">
					<label>
						<input name="f_name" type="text" class="input_form" id="f_name" size="30" width="250px;"  style="margin-left: 15px;margin-top:10px;" />
					</label>      </td>
				</tr>
				<tr id="last_name">
					<td class="text_form" id="l_name_text">Last Name</td>
					<td colspan="5"><label>
						<input name="l_name" type="text" class="input_form" id="l_name" size="30" style="margin-left: 15px;" />
					</label>
				</td>
			</tr>
			<tr id="gender">
				<td class="text_form">Gender</td>
				<td width="20">
					<div class="radio" style="margin:5px 0px 5px 15px;"><input type="radio" name="gender_status" checked value="female"></div></td>
					<td width="42" class="style3"><label for="male"><span class="text_form">Female</span></label></td>
					<td width="20">
						<div class="radio"><input type="radio" name="gender_status" checked value="male"></div></td>
						<td width="42" class="style3"><label for="male"><span class="text_form">Male</span></label></td>
						<td></td>
					</tr>
					<tr id="email">
						<td class="text_form" id="email_text">Email</td>      
						<td colspan="5"><label>
							<input name="email" type="text" class="input_form" id="femail" size="30" style="margin-left: 15px;" />
						</label></td>
					</tr>
					<tr id="birthday">
						<td class="text_form" id="birth_text">Birthday</td>
						<td colspan="2">
							<div style="margin:5px 0px 5px 15px;">
								<select class="select" id="date">
									<option value="-1"></option>
									<?php
									for ($i = 1; $i <=31; $i++) {
										echo "<option>$i</option>\n";
									}
									?>
								</select>	
							</div>       
						</td>
						<td colspan="2">
							<div>
								<select class="select" id="month">
									<option value="-1"></option>
									<?php
									for ($i = 1; $i <=12; $i++) {
										echo "<option>$i</option>\n";
									}
									?>
								</select>	
							</div>      </td>
							<td>
								<div>
									<select class="select_year" id="year">
										<option value="-1"></option>
										<?php
										for ($i = 1900; $i <=2013; $i++) {
											echo "<option>$i</option>\n";
										}
										?>
									</select>	
								</div>       </td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td colspan="5"><input class="sub_form" type="button" name="button" id="button" value="Sign Up Now" onclick="submit_func()" style="margin-left:15px;margin-top:10px;" /></td>
							</tr>
						</table>
					</form>
				</body>
				</html>
				<script type="text/javascript">
				function submit_func()
				{
					var flag = true;
					var firstname = $('#f_name').val();
					var lastname = $('#l_name').val();
					var email = $('#femail').val();
					
					if(firstname == '')
					{
						$('#f_name_text').css("color" , "#F17106");
						$('#f_name').css("border-color" , "#F17106");
						flag = false;
					}
					
					if(lastname == '')
					{
						$('#l_name_text').css("color" , "#F17106");
						$('#l_name').css("border-color" , "#F17106");
						flag = false;
					}
					
					if(email == '')
					{
						$('#email_text').css("color" , "#F17106");
						$('#femail').css("border-color" , "#F17106");
						flag = false;
					}
					else
					{
						var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
						var checker = pattern.test(email);
						if(!checker)
						{
							$('#email_text').css("color" , "#F17106");
							$('#email').css("border-color" , "#F17106");
							$('#email').val('');
							flag = false;
						}			
					}	
					
					var date = $('#date').val();
					var month = $('#month').val();
					var year = $('#year').val();
					if(date == -1)
					{
						$('#birth_text').css("color" , "#F17106");
						$('#date').css("border-color" , "#F17106");
						flag = false;
					}
					
					if(month == -1)
					{
						$('#birth_text').css("color" , "#F17106");
						$('#month').css("border-color" , "#F17106");
						flag = false;
					}
					
					if(year == -1)
					{
						$('#birth_text').css("color" , "#F17106");
						$('#year').css("border-color" , "#F17106");
						flag = false;
					}
					
					var radios = document.getElementsByName("gender_status");
					var gender;
					for (var i = 0; i < radios.length; i++) {       
						if (radios[i].checked) {
							gender = radios[i].value;
							break;
						}
						if (i == radios.length-1) {
							flag = false;
						}
					}

					if(flag)
					{
						$('#result').html('<span class="style1">Thank you for contacting us! We will review the message and try out best to get back to you as soon as we can.</span>');
						$('#first_name').css('display' , 'none');
						$('#last_name').css('display' , 'none');
						$('#gender').css('display' , 'none');
						$('#email').css('display' , 'none');
						$('#birthday').css('display' , 'none');			
						$('#button').css('display' , 'none');

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

				xmlhttp.open("GET","/CMS/addCustomer.php?email="+email+"&firstname="+firstname+"&lastname="+lastname+"&gender="+gender+"&birthdate="+month+"/"+date+"/"+year,true);
				xmlhttp.send();
			}

			return flag;
		}
		</script>