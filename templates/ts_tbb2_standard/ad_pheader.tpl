<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de" xml:lang="de">
<head>
 <title>{$HEADER_TITLE}</title>
 <link rel="stylesheet" href="{$STYLE_PATH}" type="text/css"></link>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 <meta http-equiv="Content-Style-Type" content="text/css" />
 <script src="{$TEMPLATE_PATH}/jscripts.js" type="text/javascript"></script>
 <script type="text/javascript">
 <!--
  if({$STATS['new_pm']} == 1)
 	popup('index.php?faction=pms&amp;mode=newpmreceived&amp;{$MYSID}','newpmreceived','width=400,height=200,scrollbars=yes,toolbar=no');
 //-->
 </script>
</head>
<body style="margin:0px;">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
 <td width="200" valign="top">
  <table class="tablenav" border="0" cellpadding="1" cellspacing="0" width="100%">
  <tr><td class="celltitle" align="center"><span class="fonttitle">{$LNG['Navigation']}</span></td></tr>
  <template:navrow>
   <tr><td class="cellnav_inactive" onmouseover="change_class(this,'cellnav_hover');" onmouseout="change_class(this,'cellnav_inactive');" align="right"><span class="fontnorm">{$akt_nav_link}</span></td></tr>
  </template>
  </table>
 </td>
 <td width="20">&nbsp;</td>
 <td width="85%" valign="top">
