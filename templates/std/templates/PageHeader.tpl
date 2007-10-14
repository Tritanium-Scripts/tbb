<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$modules.Language->getString('html_direction')}" lang="{$modules.Language->getString('html_language')}" xml:lang="{$modules.Language->getString('html_language')}">
<head>
 <title>{$modules.Navbar->parseElements(0)}</title>
 <link rel="stylesheet" href="{$modules.Template->getTD()}/styles/ts_tbb2_standard.css" type="text/css"></link>
 <meta http-equiv="Content-Type" content="text/html; charset={$modules.Language->getString('html_encoding')}"/>
 <meta http-equiv="Content-Style-Type" content="text/css"/>
 <script src="{$modules.Template->getTD()}/scripts/jscripts.js" type="text/javascript"></script>
 <script src="{$modules.Template->getTD()}/scripts/ajax.js" type="text/javascript"></script>
 <script src="{$modules.Template->getTD()}/scripts/posting.js" type="text/javascript"></script>
 <script type="text/javascript">
 <!--
  if(0 == 1)
 	popup('{$indexFile}?action=PrivateMessages&mode=newpmreceived&{$mySID}','newpmreceived','width=400,height=200,scrollbars=yes,toolbar=no');
 //-->
 </script>
</head>
<body>
<div align="center"><div align="left" style="width:1024px;">
<form method="post" action="{$indexFile}?action=login&amp;doit=1&amp;{$mySID}">
<table style="border-spacing:0px; border:2px #000000 solid; width:100%; padding:1px; background-color:#FFFFFF;">
<tr><td>
 <table style="width:100%; border-spacing:0px; border:1px #ACACAC solid;" cellspacing="0">
 <tr><td style="background-color:#c5e8f9; background-image:url(images/bck.jpg);"><!--<span class="big">{$boardBanner}</span>--><img src="images/test.jpg"/></td></tr>
  <tr><td align="center" style="background-color:#aec9d7; padding-bottom:4px;"><span class="FontSmall">
  {if $modules.Auth->isLoggedIn() == 1}
   <a href="{$indexFile}?action=EditProfile&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/myprofile.png" class="ImageButton" alt="{$modules.Language->getString('My_profile')}" border="0"/></a>
   <a href="{$indexFile}?action=ViewHelp&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/help.png" class="ImageButton" alt="{$modules.Language->getString('Help')}" border="0"/></a>
   <a href="{$indexFile}?action=PrivateMessages&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/pms.png" class="ImageButton" alt="{$modules.Language->getString('Private_messages')}" border="0"/></a>
   <a href="{$indexFile}?action=Search&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/search.png" class="ImageButton" alt="{$modules.Language->getString('Search')}" border="0"/></a>
   <a href="{$indexFile}?action=MemberList&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/memberlist.png" class="ImageButton" alt="{$modules.Language->getString('Memberlist')}" border="0"/></a>
   <a href="{$indexFile}?action=WhoIsOnline&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/whoisonline.png" class="ImageButton" alt="{$modules.Language->getString('Who_is_online')}" border="0"/></a>
   <a href="{$indexFile}?action=Logout&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/logout.png" class="ImageButton" alt="{$modules.Language->getString('Logout')}" border="0"/></a>
  {else}
   <a href="{$indexFile}?action=Register&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/register.png" class="ImageButton" alt="{$modules.Language->getString('Register')}" border="0"/></a>
   <a href="{$indexFile}?action=ViewHelp&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/help.png" class="ImageButton" alt="{$modules.Language->getString('Help')}" border="0"/></a>
   <a href="{$indexFile}?action=Search&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/search.png" class="ImageButton" alt="{$modules.Language->getString('Search')}" border="0"/></a>
   <a href="{$indexFile}?action=MemberList&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/memberlist.png" class="ImageButton" alt="{$modules.Language->getString('Memberlist')}" border="0"/></a>
   <a href="{$indexFile}?action=WhoIsOnline&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/whoisonline.png" class="ImageButton" alt="{$modules.Language->getString('Who_is_online')}" border="0"/></a>
   <a href="{$indexFile}?action=Login&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/login.png" class="ImageButton" alt="{$modules.Language->getString('Login')}" border="0"/></a>
  {/if}
  </span></td></tr>
 </table>
</td></tr>
</table>
</form>

<br/>
<div class="DivInfoBox"><span class="FontInfoBox"><img src="{$modules.Template->getTemplateDir()}/images/icons/Info.png" class="ImageIcon" alt="" border="0"/>{$welcomeText}</span></div>

<br/>
<table class="TableNavbar" width="100%">
<tr>
 <td class="CellNavbar">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td><span class="FontNavbar">{$modules.Navbar->parseElements()}</span></td>
    <td align="right"><span class="FontNavbar">{$modules.Navbar->getRightArea()}</span></td>
   </tr>
  </table>
 </td>
</tr>
</table>
<br/>