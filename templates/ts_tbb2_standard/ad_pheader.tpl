<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de" xml:lang="de">
<head>
 <title>{$CONFIG['board_name']}{$title_add}</title>
 <link rel="stylesheet" href="{$style_path}" type="text/css"></link>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 <meta http-equiv="Content-Style-Type" content="text/css" />
 <script type="text/javascript">
 <!--
  if({$STATS['new_pm']} == 1)
 	popup('index.php?faction=pms&amp;mode=newpmreceived&amp;{$MYSID}','newpmreceived','width=400,height=200,scrollbars=yes,toolbar=no');
 //-->
 </script>
</head>
<body style="margin:0px;">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td width="15%" valign="top">
  <table class="tbl" border="0" cellpadding="1" cellspacing="0" width="100%">
  <tr><th class="thsmall"><span class="thsmall">{$lng['Navigation']}</span></th></tr>
  <template:navrow>
   <tr><td class="td1" align="right"><span class="small">{navrow.$akt_nav_link}</span></td></tr>
  </template:navrow>
  </table>
 </td>
 <td width="85%" valign="top">
