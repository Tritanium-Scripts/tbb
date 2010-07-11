<?xml version="1.0" encoding="{$modules.Language->getString('html_encoding')}" standalone="no" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$modules.Language->getLangCode()}" xml:lang="{$modules.Language->getLangCode()}">
 <head>
  <title>{$modules.Config->getCfgVal('forum_name')}</title>
  <meta name="author" content="Chrissyx" />
  <meta name="copyright" content="&copy; 2010 Tritanium Scripts" />
{*  <meta name="keywords" content="" />
  <meta name="description" content="" />*}
  <meta name="revisit-after" content="7 days" />
  <meta name="generator" content="Notepad 4.10.1998" />
  <meta http-equiv="Content-Language" content="{$modules.Language->getLangCode()}" />
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset={$modules.Language->getString('html_encoding')}" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
  <meta http-equiv="X-UA-Compatible" content="IE=8" />
  <link rel="stylesheet" media="all" href="{$modules.Template->getTplDir()}{$modules.Config->getCfgVal('css_file')}" />
  <link rel="shortcut icon" type="image/x-icon" href="{$modules.Template->getTplDir()}favicon.ico" />
  <style type="text/css">
   img
   {
    border:none;
   }
  </style>
 </head>
 <body style="padding-top:1em;">
  <div id="main">
  <!-- Header -->
  <table cellspacing="0" cellpadding="0" style="text-align:center; vertical-align:middle; width:100%;">
   <colgroup width="50%"></colgroup>
   <tr>
    {if $modules.Config->getCfgVal('forum_logo')}
    <td><a href="{$smarty.const.INDEXFILE}{$smarty.const.SID_QMARK}"><img src="{$modules.Config->getCfgVal('forum_logo')}" alt="{$modules.Config->getCfgVal('forum_name')}" /></a></td>
    {/if}
    <td>
     <span class="finfo">{$modules.Config->getCfgVal('forum_name')}</span><br />
     <span class="tbar">{if $modules.Auth->isLoggedIn()}
     <a href="{$smarty.const.INDEXFILE}?faction=profile&amp;mode=edit&amp;profile_id={$modules.Auth->getUserID()}{$smarty.const.SID_AMPER}">{$modules.Language->getString('my_profile')}</a> | 
     <a href="{$smarty.const.INDEXFILE}?faction=faq{$smarty.const.SID_AMPER}">{$modules.Language->getString('faq')}</a> | 
     <a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=overview{$smarty.const.SID_AMPER}">{$modules.Language->getString('private_messages')}</a> | 
     <a href="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}">{$modules.Language->getString('search')}</a> | 
     {if $modules.Config->getCfgVal('wio') == 1}<a href="{$smarty.const.INDEXFILE}?faction=wio{$smarty.const.SID_AMPER}">{$modules.Language->getString('who_is_online')}</a> | {/if}
     {if $modules.Config->getCfgVal('activate_mlist') == 1}<a href="{$smarty.const.INDEXFILE}?faction=mlist{$smarty.const.SID_AMPER}">{$modules.Language->getString('member_list')}</a> | {/if}
     <a href="{$smarty.const.INDEXFILE}?faction=logout{$smarty.const.SID_AMPER}">{$modules.Language->getString('logout')}</a>
     {else}
     <a href="{$smarty.const.INDEXFILE}?faction=register{$smarty.const.SID_AMPER}">{$modules.Language->getString('register')}</a> | 
     <a href="{$smarty.const.INDEXFILE}?faction=faq{$smarty.const.SID_AMPER}">{$modules.Language->getString('faq')}</a> | 
     <a href="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}">{$modules.Language->getString('search')}</a> | 
     {if $modules.Config->getCfgVal('wio') == 1}<a href="{$smarty.const.INDEXFILE}?faction=wio{$smarty.const.SID_AMPER}">{$modules.Language->getString('who_is_online')}</a> | {/if}
     {if $modules.Config->getCfgVal('activate_mlist') == 1}<a href="{$smarty.const.INDEXFILE}?faction=mlist{$smarty.const.SID_AMPER}">{$modules.Language->getString('member_list')}</a> | {/if}
     <a href="{$smarty.const.INDEXFILE}?faction=login{$smarty.const.SID_AMPER}">{$modules.Language->getString('login')}</a>
     {/if}</span>
    </td>
   </tr>
  </table>
  <br /><br />
  <!-- NavBar -->
  <table class="navbar" cellspacing="0" cellpadding="0" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
   <tr><td class="navbar"><span class="navbar">&nbsp;{foreach from=$modules.NavBar->getNavBar() item=curElement name=navBar}{if !empty($curElement[1])}<a href="{$curElement[1]}" class="navbar">{$curElement[0]}</a>{else}{$curElement[0]}{/if}{if !$smarty.foreach.navBar.last}&nbsp;&#187;&nbsp;{/if}{/foreach}</span></td></tr>
  </table><br />