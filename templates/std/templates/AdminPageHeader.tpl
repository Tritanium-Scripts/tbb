<?xml version="1.0" encoding="{$modules.Language->getString('html_encoding')}" standalone="no" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$modules.Language->getString('html_direction')}" lang="{$modules.Language->getString('html_language')}" xml:lang="{$modules.Language->getString('html_language')}">
<head>
 <title>{$modules.Navbar->parseElements(0)}</title>
 <meta name="author" content="Tritanium Scripts"/>
 <meta name="copyright" content="Tritanium Scripts"/>
 <meta http-equiv="content-language" content="{$modules.Language->getString('html_language')}"/>
 <meta http-equiv="content-type" content="application/xhtml+xml; charset={$modules.Language->getString('html_encoding')}"/>
 <meta http-equiv="content-style-type" content="text/css"/>
 <meta http-equiv="content-script-type" content="text/javascript"/>
 <link rel="stylesheet" media="all" href="{$modules.Template->getTD()}/styles/ts_tbb2_standard.css"/>
 <link rel="shortcut icon" type="image/x-icon" href="{$modules.Template->getTD()}/images/favicon.ico"/>
 <script src="{$modules.Template->getTD()}/scripts/jscripts.js" type="text/javascript"></script>
 <script src="{$modules.Template->getTD()}/scripts/ajax.js" type="text/javascript"></script>
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
  <table class="TableStd" width="100%">
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