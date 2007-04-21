<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$modules.Language->getString('html_direction')}" lang="{$modules.Language->getString('html_language')}" xml:lang="{$modules.Language->getString('html_language')}">
<head>
 <title>{$modules.Navbar->parseElements(0)}</title>
 <link rel="stylesheet" href="{$modules.Template->getTD()}/styles/ts_tbb2_standard.css" type="text/css"></link>
 <meta http-equiv="Content-Type" content="text/html; charset={$modules.Language->getString('html_encoding')}"/>
 <meta http-equiv="Content-Style-Type" content="text/css"/>
 <script src="{$modules.Template->getTD()}/jscripts.js" type="text/javascript"></script>
 <script src="{$modules.Template->getTD()}/ajax.js" type="text/javascript"></script>
 <script type="text/javascript">
 <!--
  if(0 == 1)
 	popup('{$indexFile}?action=PrivateMessages&mode=newpmreceived&{$mySID}','newpmreceived','width=400,height=200,scrollbars=yes,toolbar=no');
 //-->
 </script>
</head>
<body style="background-color:#DCDCDC;">
<div align="center"><div align="left" style="width:1024px;">
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
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
 <td valign="top" width="200">
  <table class="TableStd" cellspacing="0" width="100%">
  <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Navigation')}</span></td></tr>
  {foreach from=$navigation item=curNav}
   {if $curNav.0 == '-'}
    <tr><td class="CellNavNone"><hr class="LineNav"/></td></tr>
   {else}
    <tr><td class="{if $smarty.get.action == $curNav.2}CellNavActive{else}CellNav{/if}" onclick="goTo('{$curNav.0}');"><a class="FontNav" href="{$curNav.0}">{$curNav.1}</a></td></tr>
   {/if}
  {/foreach}
  </table>
 </td>
 <td valign="top" width="10">&nbsp;</td>
 <td valign="top">