<?php
ob_start();
require_once("./include/membersite_config.php"); 

if(isset($_POST['submitted']))
{
   if($fgmembersite->Login())
   {
        $fgmembersite->RedirectToURL("/CMS/homepage.php");
   }
}
ob_end_flush();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />        <title>Bob</title>        <link href="/CMS/styles/reset.css" media="screen" rel="stylesheet" type="text/css" />
<link href="/CMS/styles/datepicker.css" media="screen" rel="stylesheet" type="text/css" />
<link href="/CMS/styles/styles.css" media="screen" rel="stylesheet" type="text/css" />        <script type="text/javascript" src="/js/jquery/jquery.js"></script>
<script type="text/javascript" src="/js/sites/datepicker.js"></script>
<script type="text/javascript" src="/js/sites/general.js"></script>                <script type="text/javascript">var NREUMQ=NREUMQ||[];NREUMQ.push(["mark","firstbyte",new Date().getTime()]);</script>
    </head>
    <body>
                    
            <div id="header">
    
                <a href="/" id="logo">
                    <img alt="" src="/CMS/img/logo.png" />
                </a>
    
                    
            </div>
    
    
                
    <div id="viewport_main">
    
            <div id="content" class="noTabs">    
                    
                <div id="main">
                   <h1>Login</h1>
<div class="spacer"></div>
<form enctype="application/x-www-form-urlencoded" class="onerow_form" method="post" action=""><dl class="zend_form">
<input type='hidden' name='submitted' id='submitted' value='1'/>
<input type="hidden" name="ID2822dfec6f0e71d83bb4ec63d5c85e50" value="2822dfec6f0e71d83bb4ec63d5c85e50" id="ID2822dfec6f0e71d83bb4ec63d5c85e50" />
<dt id="username-label"><label for="username" class="required">Username</label></dt>
<dd id="username-element">
<input type="text" name="username" id="username" value="" /></dd>
<dt id="password-label"><label for="password" class="required">Password</label></dt>
<dd id="password-element">
<input type="password" name="password" id="password" value="" /></dd>
<dt id="login-label">&#160;</dt><dd id="login-element">
<input type="submit" name="login" id="login" value="login" position="rightBottom" /></dd></dl></form>                    <div class="wrapper"><!-- &nbsp; --></div>
                </div>
    
                    
            </div>    
            <div id="closing" class="hidden">
                <a href="#header" class="generalIco icoPageUp"><!-- &nbsp; --></a>
                <div class="wrapper"><!-- &nbsp; --></div>
            </div>
    
            <div id="footer">
                <div class="pagewrap">
                    <div class="hints">
                        Environment: live                        &nbsp;&nbsp;&nbsp;&nbsp;
                        Version: 3.2.2                        &nbsp;&nbsp;&nbsp;&nbsp;
                        Condition: STABLE                    </div>
                    <div class="mark">08/10/2012 | 02:24 &nbsp; <font>&#149;</font> &nbsp; You are logged out                    &nbsp; <font>&#149;</font> &nbsp;
                                        </div>
                </div>
            </div>
        </div>
        
                
        
                <script type="text/javascript">
                        bob.state.page.module = 'auth';
            bob.state.page.controller = 'index';
            bob.state.page.action = 'login';
        </script>
        <script type="text/javascript">if(!NREUMQ.f){NREUMQ.f=function(){NREUMQ.push(["load",new Date().getTime()]);var e=document.createElement("script");e.type="text/javascript";e.async=true;e.src="https://d1ros97qkrwjf5.cloudfront.net/41/eum/rum.js";document.body.appendChild(e);if(NREUMQ.a)NREUMQ.a();};NREUMQ.a=window.onload;window.onload=NREUMQ.f;};NREUMQ.push(["nrf2","beacon-1.newrelic.com","82f5363730",39202,"b11VYkMCDxBVURZdV1YXdENCFw4OG1YHUllNVEMZWA0FBkwdC1pcXUA=",0,92,new Date().getTime()]);</script>
        
    </body>
</html>
