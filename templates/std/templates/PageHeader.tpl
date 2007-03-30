<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$Modules.Language->getString('html_direction')}" lang="{$Modules.Language->getString('html_language')}" xml:lang="{$Modules.Language->getString('html_language')}">
<head>
 <title>{$Modules.Navbar->parseElements(0)}</title>
 <link rel="stylesheet" href="templates/std/templates/styles/ts_tbb2_standard.css" type="text/css"></link>
 <meta http-equiv="Content-Type" content="text/html; charset={$Modules.Language->getString('html_encoding')}"/>
 <meta http-equiv="Content-Style-Type" content="text/css"/>
 <script src="templates/std/templates/jscripts.js" type="text/javascript"></script>
 <script src="templates/std/templates/ajax.js" type="text/javascript"></script>
 <script type="text/javascript">
 <!--
  if(0 == 1)
 	popup('{$IndexFile}?Action=PrivateMessages&Mode=newpmreceived&{$MySID}','newpmreceived','width=400,height=200,scrollbars=yes,toolbar=no');
 //-->
 </script>
</head>
<body>
<form method="post" Action="{$IndexFile}?Action=login&amp;doit=1&amp;{$MySID}">
<table style="border-spacing:0px; border:2px #000000 solid; width:100%; padding:1px; background-color:#FFFFFF;">
<tr><td>
 <table style="width:100%; border-spacing:0px; border:1px #ACACAC solid;">
 <tr><td style="background-color:#c5e8f9; background-image:url(http://helios/bck.jpg);"><!--<span class="big">{$BoardBanner}</span>--><img src="http://helios/test.jpg"/></td></tr>
  <tr><td align="center" style="background-color:#aec9d7; padding-bottom:4px;"><span class="FontSmall">
  {if $Modules.Auth->isLoggedIn() == 1}
   <a href="{$IndexFile}?Action=EditProfile&amp;{$MySID}"><img src="myprofile.png" class="ImageButton" alt="{$Modules.Language->getString('My_profile')}" border="0"/></a>
   <a href="{$IndexFile}?Action=ViewHelp&amp;{$MySID}"><img src="help.png" class="ImageButton" alt="{$Modules.Language->getString('Help')}" border="0"/></a>
   <a href="{$IndexFile}?Action=PrivateMessages&amp;{$MySID}"><img src="pms.png" class="ImageButton" alt="{$Modules.Language->getString('Private_messages')}" border="0"/></a>
   <a href="{$IndexFile}?Action=Search&amp;{$MySID}"><img src="search.png" class="ImageButton" alt="{$Modules.Language->getString('Search')}" border="0"/></a>
   <a href="{$IndexFile}?Action=MemberList&amp;{$MySID}"><img src="memberlist.png" class="ImageButton" alt="{$Modules.Language->getString('Memberlist')}" border="0"/></a>
   <a href="{$IndexFile}?Action=WhoIsOnline&amp;{$MySID}"><img src="whoisonline.png" class="ImageButton" alt="{$Modules.Language->getString('Who_is_online')}" border="0"/></a>
   <a href="{$IndexFile}?Action=Logout&amp;{$MySID}"><img src="logout.png" class="ImageButton" alt="{$Modules.Language->getString('Logout')}" border="0"/></a>
  {else}
   <a href="{$IndexFile}?Action=Register&amp;{$MySID}"><img src="register.png" class="ImageButton" alt="{$Modules.Language->getString('Register')}" border="0"/></a>
   <a href="{$IndexFile}?Action=ViewHelp&amp;{$MySID}"><img src="help.png" class="ImageButton" alt="{$Modules.Language->getString('Help')}" border="0"/></a>
   <a href="{$IndexFile}?Action=Search&amp;{$MySID}"><img src="search.png" class="ImageButton" alt="{$Modules.Language->getString('Search')}" border="0"/></a>
   <a href="{$IndexFile}?Action=MemberList&amp;{$MySID}"><img src="memberlist.png" class="ImageButton" alt="{$Modules.Language->getString('Memberlist')}" border="0"/></a>
   <a href="{$IndexFile}?Action=WhoIsOnline&amp;{$MySID}"><img src="whoisonline.png" class="ImageButton" alt="{$Modules.Language->getString('Who_is_online')}" border="0"/></a>
   <a href="{$IndexFile}?Action=Login&amp;{$MySID}"><img src="login.png" class="ImageButton" alt="{$Modules.Language->getString('Login')}" border="0"/></a>
  {/if}
  </span></td></tr>
 </table>
</td></tr>
</table>
</form>
<!--<br/>
<div class="DivInfoBox"><span class="FontInfoBox"><img src="{$Modules.Template->getTemplateDir()}/images/icons/Info.gif" class="ImageIcon" alt="" border="0"/>{$WelcomeText}</span></div>
-->
<br/>
<table class="TableNavbar" width="100%">
<tr>
 <td class="CellNavbar">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td><span class="FontNavbar">{$Modules.Navbar->parseElements()}</span></td>
    <td align="right"><span class="FontNavbar">{$Modules.Navbar->getRightArea()}</span></td>
   </tr>
  </table>
 </td>
</tr>
</table>
<br/>