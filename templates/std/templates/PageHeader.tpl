<?xml version="1.0" encoding="{$modules.Language->getString('html_encoding')}" standalone="no" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$modules.Language->getString('html_direction')}" lang="{$modules.Language->getLangCode()}" xml:lang="{$modules.Language->getLangCode()}">
 <head>
  <title>{$smarty.config.navBarDelim|implode:$modules.NavBar->getNavBar(false)}</title>
  <meta name="author" content="Chrissyx" />
  <meta name="copyright" content="&copy; 2010 Tritanium Scripts" />
  <meta name="keywords" content="TBB,Tritanium,Tritanium Scripts,TBB {$smarty.const.VERSION_PUBLIC},Tritanium Bulletin Board,{$modules.Config->getCfgVal('site_name')},{','|implode:$modules.NavBar->getNavBar(false)}" />
  <meta name="description" content="{sprintf($modules.Language->getString('html_description'), $modules.Config->getCfgVal('site_name'), $smarty.const.VERSION_PUBLIC)}" />
  <meta name="revisit-after" content="7 days" />
  <meta name="generator" content="Notepad 4.10.1998" />
  <meta http-equiv="Content-Language" content="{$modules.Language->getLangCode()}" />
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset={$modules.Language->getString('html_encoding')}" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
  <meta http-equiv="X-UA-Compatible" content="IE=8" />
  <link rel="stylesheet" media="all" href="{$modules.Template->getTplDir()}{$modules.Config->getCfgVal('css_file')}" />
  <link rel="shortcut icon" type="image/x-icon" href="{$modules.Template->getTplDir()}images/favicon.ico" />
  <script src="{$modules.Template->getTplDir()}scripts/fader.js" type="text/javascript"></script>
  <style type="text/css">
   img
   {
    border:none;
   }

   label
   {
    cursor:pointer;
   }

   .fade
   {
    filter:alpha(opacity=0);
    left:0;
    opacity:0.0;
    position:absolute;
    text-align:center;
    top:0;
    width:100%;
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
    <td><a href="{$smarty.const.INDEXFILE}{$smarty.const.SID_QMARK}" onfocus="this.blur();"><img src="{$modules.Config->getCfgVal('forum_logo')}" alt="{$modules.Config->getCfgVal('forum_name')}" /></a></td>
    {/if}
    <td>
     <span class="finfo">{if $modules.Auth->isLoggedIn()}{sprintf($modules.Language->getString('hello_x_time'), $modules.Auth->getUserNick(), $smarty.now|date_format:$modules.Language->getString('TIMEFORMAT'))}</span><br />
     <span class="tbar">
     <a href="{$smarty.const.INDEXFILE}?faction=profile&amp;mode=edit&amp;profile_id={$modules.Auth->getUserID()}{$smarty.const.SID_AMPER}">{$modules.Language->getString('my_profile')}</a> | 
     <a href="{$smarty.const.INDEXFILE}?faction=faq{$smarty.const.SID_AMPER}">{$modules.Language->getString('faq')}</a> | 
     <a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=overview{$smarty.const.SID_AMPER}">{$modules.Language->getString('private_messages')}</a> | 
     <a href="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}">{$modules.Language->getString('search')}</a> | {if $modules.Config->getCfgVal('wio') == 1}
     <a href="{$smarty.const.INDEXFILE}?faction=wio{$smarty.const.SID_AMPER}">{$modules.Language->getString('who_is_online')}</a> | {/if}{if $modules.Config->getCfgVal('activate_mlist') == 1}
     <a href="{$smarty.const.INDEXFILE}?faction=mlist{$smarty.const.SID_AMPER}">{$modules.Language->getString('member_list')}</a> | {/if}
     <a href="{$smarty.const.INDEXFILE}?faction=logout{$smarty.const.SID_AMPER}">{$modules.Language->getString('logout')}</a>
     {else}{$modules.Config->getCfgVal('forum_name')}</span><br />
     <span class="tbar">
     <a href="{$smarty.const.INDEXFILE}?faction=register{$smarty.const.SID_AMPER}">{$modules.Language->getString('register')}</a> | 
     <a href="{$smarty.const.INDEXFILE}?faction=faq{$smarty.const.SID_AMPER}">{$modules.Language->getString('faq')}</a> | 
     <a href="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}">{$modules.Language->getString('search')}</a> | {if $modules.Config->getCfgVal('wio') == 1}
     <a href="{$smarty.const.INDEXFILE}?faction=wio{$smarty.const.SID_AMPER}">{$modules.Language->getString('who_is_online')}</a> | {/if}{if $modules.Config->getCfgVal('activate_mlist') == 1}
     <a href="{$smarty.const.INDEXFILE}?faction=mlist{$smarty.const.SID_AMPER}">{$modules.Language->getString('member_list')}</a> | {/if}
     <a href="{$smarty.const.INDEXFILE}?faction=login{$smarty.const.SID_AMPER}">{$modules.Language->getString('login')}</a>
     {/if}</span>
    </td>
   </tr>
  </table>
  <br /><br />

  <!-- NavBar -->
  <table class="navbar" cellspacing="0" cellpadding="0" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
   <tr>
    <td class="navbar"><span class="navbar">&nbsp;{foreach from=$modules.NavBar->getNavBar() item=curElement name=navBar}{if !$smarty.foreach.navBar.last}<a href="{$curElement[1]}" class="navbar">{$curElement[0]}</a>{$smarty.config.navBarDelim}{else}{$curElement[0]}{/if}{/foreach}</span></td>
{* todo: pmID *}
    <td class="navbar" style="text-align:right;"><span class="navbar">{if $action == 'Forum' && $subAction != 'Message'}<a href="{$smarty.const.INDEXFILE}?faction=newtopic&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/newtopic.gif" alt="" style="vertical-align:middle;" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=newpoll&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/newpoll.gif" alt="" style="vertical-align:middle;" /></a>{if $subAction == 'ViewTopic'}&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=reply&amp;forum_id={$forumID}&amp;thread_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/newreply.gif" alt="" style="vertical-align:middle;" /></a>{/if}{elseif $action == 'PrivateMessage' && $subAction != 'Message'}<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=send{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/newpm.png" alt="" style="vertical-align:middle;" /></a>{if !empty($pmID)}&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=reply&amp;pm_id={$pmID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/newreply.gif" alt="" style="vertical-align:middle;" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=kill&amp;pm_id={$pmID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/delete.png" alt="" style="vertical-align:middle;" /></a>{/if}{else}&nbsp;{/if}{if $smarty.config.debug}{$action}{/if}</span></td>
   </tr>
  </table>
  <br />
