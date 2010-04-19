<?xml version="1.0" encoding="{$LNG['html_encoding']}"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$LNG['html_direction']}" lang="{$LNG['html_language']}" xml:lang="{$LNG['html_language']}">
<head>
 <title>{$HEADER_TITLE}</title>
 <link rel="stylesheet" href="{$STYLE_PATH}" type="text/css"></link>
 <meta http-equiv="Content-Type" content="text/html; charset={$LNG['html_encoding']}" />
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
<table class="tablestd" style="border:1px #000000 solid;" border="0" cellpadding="3" cellspacing="1" width="100%">
<tr><td class="cellstd" align="center"><span class="big">{$board_banner}</span></td></tr>
<if:"{$USER_LOGGED_IN} == 1">
 <tr><td class="cellalt" align="center"><span class="fontsmall">
  <a href="index.php?faction=editprofile&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_myprofile.gif" alt="{$LNG['My_profile']}" border="0" /></a>
  <a href="index.php?faction=viewhelp&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_help.gif" alt="{$LNG['Help']}" border="0" /></a>
  <a href="index.php?faction=pms&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_pms.gif" alt="{$LNG['Private_messages']}" border="0" /></a>
  <a href="index.php?faction=search&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_search.gif" alt="{$LNG['Search']}" border="0" /></a>
  <a href="index.php?faction=memberlist&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_memberlist.gif" alt="{$LNG['Memberlist']}" border="0" /></a>
  <a href="index.php?faction=viewwio&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_wio.gif" alt="{$LNG['Who_is_online']}" border="0" /></a>
  <a href="index.php?faction=logout&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_logout.gif" alt="{$LNG['Logout']}" border="0" /></a>
 </span></td></tr>
 <tr><td class="cellstd">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr><td><span class="fontsmall">{$welcome_text}</span></td></tr>
  </table>
 </td></tr>
<else />
 <tr><td class="cellalt" align="center"><span class="fontsmall">
  <a href="index.php?faction=register&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_register.gif" alt="{$LNG['Register']}" border="0" /></a>
  <a href="index.php?faction=viewhelp&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_help.gif" alt="{$LNG['Help']}" border="0" /></a>
  <a href="index.php?faction=search&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_search.gif" alt="{$LNG['Search']}" border="0" /></a>
  <a href="index.php?faction=memberlist&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_memberlist.gif" alt="{$LNG['Memberlist']}" border="0" /></a>
  <a href="index.php?faction=viewwio&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_wio.gif" alt="{$LNG['Who_is_online']}" border="0" /></a>
  <a href="index.php?faction=login&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/images/button_login.gif" alt="{$LNG['Login']}" border="0" /></a>
 </span></td></tr>
 <tr><td class="cellstd" valign="middle" align="center">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
   <td width="70%"><span class="fontsmall">{$welcome_text}</span></td>
   <td width="30%" align="right"><span class="fontsmall"><b>{$LNG['Login']}</b><br />{$LNG['Nick']} <input size="10" class="form_text" type="text" name="p_nick" />&nbsp;&nbsp;{$LNG['PW']} <input size="10" class="form_text" type="password" name="p_pw" /><br /><input class="form_bbutton" type="submit" value="{$LNG['Login']}" /></span></td>
  </tr>
  </table>
 </td></tr>
</if>
</table></form>