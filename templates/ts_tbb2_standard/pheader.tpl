<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de" xml:lang="de">
<head>
 <title>{CONFIG_BOARD_NAME}{TITLE_ADD}</title>
 <link rel="stylesheet" href="{STYLE_PATH}" type="text/css"></link>
</head>
<body>
<form method="post" action="index.php?faction=login&amp;doit=1&amp;{user_not_logged_in.MYSID}">
<table class="headertbl" border="0" cellpadding="3" cellspacing="1" width="100%">
<tr><td class="td1" align="center"><span class="big">{CONFIG_BOARD_NAME}</span></td></tr>
<!-- TPLBLOCK user_logged_in -->
 <tr><td class="td2" align="center"><span class="small"><a href="index.php?faction=editprofile&amp;{user_logged_in.MYSID}">{user_logged_in.LNG_MY_PROFILE}</a> | Hilfe | Private Nachrichten | Suche | Mitgliederliste | <a href="index.php?faction=viewwio&amp;{user_logged_in.MYSID}">{user_logged_in.LNG_WHO_IS_ONLINE}</a> | <a href="index.php?faction=logout&amp;{user_logged_in.MYSID}">{user_logged_in.LNG_LOGOUT}</a></span></td></tr>
 <tr><td class="td1">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr><td><span class="small">{user_logged_in.WELCOME_LOGGED_IN}</span></td></tr>
  </table>
 </td></tr>
<!-- /TPLBLOCK user_logged_in -->
<!-- TPLBLOCK user_not_logged_in --> 
 <tr><td class="td2" align="center"><span class="small"><a href="index.php?faction=register&amp;{user_not_logged_in.MYSID}">{user_not_logged_in.LNG_REGISTER}</a> | Hilfe | Suche | Mitgliederliste | <a href="index.php?faction=viewwio&amp;{user_not_logged_in.MYSID}">{user_not_logged_in.LNG_WHO_IS_ONLINE}</a> | <a href="index.php?faction=login&amp;{user_not_logged_in.MYSID}">{user_not_logged_in.LNG_LOGIN}</a></span></td></tr>
 <tr><td class="td1" valign="middle" align="center">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
   <td width="70%"><span class="small">{user_not_logged_in.WELCOME_NOT_LOGGED_IN}</span></td>
   <td width="30%" align="right"><span class="small"><b>{user_not_logged_in.LNG_LOGIN}</b><br />{user_not_logged_in.LNG_NICK} <input size="10" class="form_text" type="text" name="p_nick" />&nbsp;&nbsp;{user_not_logged_in.LNG_PW} <input size="10" class="form_text" type="password" name="p_pw" /><br /><input class="form_bbutton" type="submit" value="{user_not_logged_in.LNG_LOGIN}" /></span></td>
  </tr>
  </table>
 </td></tr>
<!-- /TPLBLOCK user_not_logged_in -->
</table></form>