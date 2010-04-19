<?xml version="1.0" encoding="{$lng['html_encoding']}"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$lng['html_direction']}" lang="{$lng['html_language']}" xml:lang="{$lng['html_language']}">
<head>
 <title>{$title_add}</title>
 <link rel="stylesheet" href="{$style_path}" type="text/css"></link>
 <meta http-equiv="Content-Type" content="text/html; charset={$lng['html_encoding']}" />
 <meta http-equiv="Content-Style-Type" content="text/css" />
 <script src="{$template_path}/jscripts.js" type="text/javascript"></script>
 <script type="text/javascript">
 <!--
  if({$STATS['new_pm']} == 1)
 	popup('index.php?faction=pms&mode=newpmreceived&{$MYSID}','newpmreceived','width=400,height=200,scrollbars=yes,toolbar=no');
 //-->
 </script>
</head>
<body>
<form method="post" action="index.php?faction=login&amp;doit=1&amp;{$MYSID}">
<table class="headertbl" border="0" cellpadding="3" cellspacing="1" width="100%">
<tr><td class="td1" align="center"><span class="big">{$board_banner}</span></td></tr>
<template:user_logged_in>
 <tr><td class="td2" align="center"><span class="small">
  <a href="index.php?faction=editprofile&amp;{user_logged_in.$MYSID}"><img src="{user_logged_in.$template_path}/images/button_myprofile.gif" alt="{user_logged_in.$lng['My_profile']}" border="0" /></a>
  <a href="index.php?faction=viewhelp&amp;{user_logged_in.$MYSID}"><img src="{user_logged_in.$template_path}/images/button_help.gif" alt="{user_logged_in.$lng['Help']}" border="0" /></a>
  <a href="index.php?faction=pms&amp;{user_logged_in.$MYSID}"><img src="{user_logged_in.$template_path}/images/button_pms.gif" alt="{user_logged_in.$lng['Private_messages']}" border="0" /></a>
  <a href="index.php?faction=search&amp;{user_logged_in.$MYSID}"><img src="{user_logged_in.$template_path}/images/button_search.gif" alt="{user_logged_in.$lng['Search']}" border="0" /></a>
  <img src="{user_logged_in.$template_path}/images/button_memberlist.gif" alt="{user_logged_in.$lng['Memberlist']}" border="0" />
  <a href="index.php?faction=viewwio&amp;{user_logged_in.$MYSID}"><img src="{user_logged_in.$template_path}/images/button_wio.gif" alt="{user_logged_in.$lng['Who_is_online']}" border="0" /></a>
  <a href="index.php?faction=logout&amp;{user_logged_in.$MYSID}"><img src="{user_logged_in.$template_path}/images/button_logout.gif" alt="{user_logged_in.$lng['Logout']}" border="0" /></a>
 </span></td></tr>
 <tr><td class="td1">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr><td><span class="small">{user_logged_in.$welcome_text}</span></td></tr>
  </table>
 </td></tr>
</template:user_logged_in>
<template:user_not_logged_in> 
 <tr><td class="td2" align="center"><span class="small">
  <a href="index.php?faction=register&amp;{user_not_logged_in.$MYSID}"><img src="{user_not_logged_in.$template_path}/images/button_register.gif" alt="{user_not_logged_in.$lng['Register']}" border="0" /></a>
  <a href="index.php?faction=viewhelp&amp;{user_not_logged_in.$MYSID}"><img src="{user_not_logged_in.$template_path}/images/button_help.gif" alt="{user_not_logged_in.$lng['Help']}" border="0" /></a>
  <a href="index.php?faction=search&amp;{user_not_logged_in.$MYSID}"><img src="{user_not_logged_in.$template_path}/images/button_search.gif" alt="{user_not_logged_in.$lng['Search']}" border="0" /></a>
  <img src="{user_not_logged_in.$template_path}/images/button_memberlist.gif" alt="{user_not_logged_in.$lng['Memberlist']}" border="0" />
  <a href="index.php?faction=viewwio&amp;{user_not_logged_in.$MYSID}"><img src="{user_not_logged_in.$template_path}/images/button_wio.gif" alt="{user_not_logged_in.$lng['Who_is_online']}" border="0" /></a>
  <a href="index.php?faction=login&amp;{user_not_logged_in.$MYSID}"><img src="{user_not_logged_in.$template_path}/images/button_login.gif" alt="{user_not_logged_in.$lng['Login']}" border="0" /></a>
 </span></td></tr>
 <tr><td class="td1" valign="middle" align="center">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
   <td width="70%"><span class="small">{user_not_logged_in.$welcome_text}</span></td>
   <td width="30%" align="right"><span class="small"><b>{user_not_logged_in.$lng['Login']}</b><br />{user_not_logged_in.$lng['Nick']} <input size="10" class="form_text" type="text" name="p_nick" />&nbsp;&nbsp;{user_not_logged_in.$lng['PW']} <input size="10" class="form_text" type="password" name="p_pw" /><br /><input class="form_bbutton" type="submit" value="{user_not_logged_in.$lng['Login']}" /></span></td>
  </tr>
  </table>
 </td></tr>
</template:user_not_logged_in>
</table></form>