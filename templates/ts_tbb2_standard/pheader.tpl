<?xml version="1.0" encoding="{$lng['html_encoding']}"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$lng['html_direction']}" lang="{$lng['html_language']}" xml:lang="{$lng['html_language']}">
<head>
 <title>{$HEADER_TITLE}</title>
 <link rel="stylesheet" href="{$STYLE_PATH}" type="text/css"></link>
 <meta http-equiv="Content-Type" content="text/html; charset={$lng['html_encoding']}" />
 <meta http-equiv="Content-Style-Type" content="text/css" />
 <script src="{$TEMPLATE_PATH}/jscripts.js" type="text/javascript"></script>
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
<if:"{$USER_LOGGED_IN} == 1">
 <tr><td class="td2" align="center"><span class="small">
  <a href="index.php?faction=editprofile&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_myprofile.gif" alt="{$lng['My_profile']}" border="0" /></a>
  <a href="index.php?faction=viewhelp&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_help.gif" alt="{$lng['Help']}" border="0" /></a>
  <a href="index.php?faction=pms&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_pms.gif" alt="{$lng['Private_messages']}" border="0" /></a>
  <a href="index.php?faction=search&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_search.gif" alt="{$lng['Search']}" border="0" /></a>
  <a href="index.php?faction=userslist&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_memberlist.gif" alt="{$lng['Memberlist']}" border="0" /></a>
  <a href="index.php?faction=viewwio&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_wio.gif" alt="{$lng['Who_is_online']}" border="0" /></a>
  <a href="index.php?faction=logout&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_logout.gif" alt="{$lng['Logout']}" border="0" /></a>
 </span></td></tr>
 <tr><td class="td1">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr><td><span class="small">{$welcome_text}</span></td></tr>
  </table>
 </td></tr>
<else />
 <tr><td class="td2" align="center"><span class="small">
  <a href="index.php?faction=register&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_register.gif" alt="{$lng['Register']}" border="0" /></a>
  <a href="index.php?faction=viewhelp&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_help.gif" alt="{$lng['Help']}" border="0" /></a>
  <a href="index.php?faction=search&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_search.gif" alt="{$lng['Search']}" border="0" /></a>
  <a href="index.php?faction=userslist&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_memberlist.gif" alt="{$lng['Memberlist']}" border="0" /></a>
  <a href="index.php?faction=viewwio&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_wio.gif" alt="{$lng['Who_is_online']}" border="0" /></a>
  <a href="index.php?faction=login&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_login.gif" alt="{$lng['Login']}" border="0" /></a>
 </span></td></tr>
 <tr><td class="td1" valign="middle" align="center">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
   <td width="70%"><span class="small">{$welcome_text}</span></td>
   <td width="30%" align="right"><span class="small"><b>{$lng['Login']}</b><br />{$lng['Nick']} <input size="10" class="form_text" type="text" name="p_nick" />&nbsp;&nbsp;{$lng['PW']} <input size="10" class="form_text" type="password" name="p_pw" /><br /><input class="form_bbutton" type="submit" value="{$lng['Login']}" /></span></td>
  </tr>
  </table>
 </td></tr>
</if>
</table></form>