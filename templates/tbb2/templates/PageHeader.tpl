<?xml version="1.0" encoding="{$modules.Language->getString('html_encoding')}" standalone="no" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$modules.Language->getString('html_direction')}" lang="{$modules.Language->getLangCode()}" xml:lang="{$modules.Language->getLangCode()}">
 <head>
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset={$modules.Language->getString('html_encoding')}" />
  <meta http-equiv="Content-Language" content="{$modules.Language->getLangCode()}" />
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="author" content="Tritanium Scripts" />
  <meta name="copyright" content="&copy; 2010&ndash;2015 Tritanium Scripts" />
  <meta name="description" content="{sprintf($modules.Language->getString('html_description'), $modules.Config->getCfgVal('site_name'), $smarty.const.VERSION_PUBLIC)}" />
  <meta name="generator" content="Notepad 4.10.1998" />
  <meta name="keywords" content="TBB,Tritanium,Tritanium Scripts,TBB {$smarty.const.VERSION_PUBLIC},Tritanium Bulletin Board,{$modules.Config->getCfgVal('site_name')},{','|implode:$modules.NavBar->getNavBar(false)}" />
  <meta name="revisit-after" content="7 days" />
  <meta name="robots" content="all" />
  <link href="{$modules.Template->getTplDir()}images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <link href="{$modules.Template->getTplDir()}{$modules.Auth->getUserStyle()}" media="all" rel="stylesheet" />
  <link href="{$smarty.const.INDEXFILE}?faction=rssFeed" rel="alternate" title="{$modules.Config->getCfgVal('forum_name')|string_format:$modules.Language->getString('x_rss_feed')}" type="application/rss+xml" />
  <script src="{$modules.Template->getTplDir()}scripts/scripts.js" type="text/javascript"></script>
  <title>{$smarty.config.navBarDelim|implode:$modules.NavBar->getNavBar(false)}</title>
 </head>
 <body{if $modules.PrivateMessage->isRemind() && $unreadPMs > 0} onload="if(confirm('{if $unreadPMs == 1}{$modules.Language->getString('you_have_one_new_pm')}{elseif $unreadPMs > 1}{$unreadPMs|string_format:$modules.Language->getString('you_have_x_new_pms')}{/if}')) document.location='{$smarty.const.INDEXFILE}?faction=pm';"{/if}>
  <div id="mainBox" style="width:{$modules.Config->getCfgVal('twidth')};">

  <!-- Header -->
  <div id="headerBox">
   <div id="headerInnerBox">
    <a href="{$smarty.const.INDEXFILE}{$smarty.const.SID_QMARK}" onfocus="this.blur();"><img src="{if $modules.Config->getCfgVal('forum_logo')}{$modules.Config->getCfgVal('forum_logo')}" alt="{$modules.Config->getCfgVal('forum_name')}{else}{$modules.Template->getTplDir()}images/logo.jpg" alt="{/if}" /></a>
    <div id="headerNavigationBox">{if $modules.Auth->isLoggedIn()}
     <a href="{$smarty.const.INDEXFILE}?faction=profile&amp;mode=edit&amp;profile_id={$modules.Auth->getUserID()}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/my_profile.png" class="imageButton" alt="{$modules.Language->getString('my_profile')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=faq{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/faq.png" class="imageButton" alt="{$modules.Language->getString('faq')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=overview{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/pms.png" class="imageButton" alt="{$modules.Language->getString('private_messages')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/search.png" class="imageButton" alt="{$modules.Language->getString('search')}" /></a>{if $modules.Config->getCfgVal('wio') == 1}
     <a href="{$smarty.const.INDEXFILE}?faction=wio{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/wio.png" class="imageButton" alt="{$modules.Language->getString('who_is_online')}" /></a>{/if}{if $modules.Config->getCfgVal('activate_mlist') != 0}
     <a href="{$smarty.const.INDEXFILE}?faction=mlist{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/member_list.png" class="imageButton" alt="{$modules.Language->getString('member_list')}" /></a>{/if}
     <a href="{$smarty.const.INDEXFILE}?faction=todaysPosts{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/todays_posts.png" class="imageButton" alt="{$modules.Language->getString('todays_posts')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=newsletter{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/newsletter.png" class="imageButton" alt="{$modules.Language->getString('newsletter_archive')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=logout{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/logout.png" class="imageButton" alt="{$modules.Language->getString('logout')}" /></a>
{else}
     <a href="{$smarty.const.INDEXFILE}?faction=register{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/register.png" class="imageButton" alt="{$modules.Language->getString('register')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=faq{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/faq.png" class="imageButton" alt="{$modules.Language->getString('faq')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/search.png" class="imageButton" alt="{$modules.Language->getString('search')}" /></a>{if $modules.Config->getCfgVal('wio') == 1}
     <a href="{$smarty.const.INDEXFILE}?faction=wio{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/wio.png" class="imageButton" alt="{$modules.Language->getString('who_is_online')}" /></a>{/if}{if $modules.Config->getCfgVal('activate_mlist') == 1}
     <a href="{$smarty.const.INDEXFILE}?faction=mlist{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/member_list.png" class="imageButton" alt="{$modules.Language->getString('member_list')}" /></a>{/if}
     <a href="{$smarty.const.INDEXFILE}?faction=todaysPosts{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/todays_posts.png" class="imageButton" alt="{$modules.Language->getString('todays_posts')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=login{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/login.png" class="imageButton" alt="{$modules.Language->getString('login')}" /></a>{/if}
    </div>
   </div>
  </div>

  <p id="headerInfoBox"><img src="{$modules.Template->getTplDir()}images/icons/info.png" alt="" class="imageIcon" /> {if $modules.Auth->isLoggedIn()}{sprintf($modules.Language->getString('hello_x_time'), $modules.Auth->getUserNick(), $currentTime)}. {if $unreadPMs > 0}<a href="{$smarty.const.INDEXFILE}?faction=pm{$smarty.const.SID_AMPER}" style="font-weight:bold;">{$modules.Language->getString('new_pms_received')}</a>{else}{$modules.Language->getString('no_new_pms')}{/if}{else}{$modules.Config->getCfgVal('forum_name')|string_format:$smarty.config.langWelcome}{/if}</p>

  <!-- NavBar -->
  <table class="tableNavBar">
   <tr>
    <td class="cellNavBarBig">
     <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
      <tr>
       <td><span class="fontNavBar">&nbsp;{foreach $modules.NavBar->getNavBar() as $curElement}{if !$curElement@last}<a href="{$curElement[1]}">{$curElement[0]}</a>{$smarty.config.navBarDelim}{else}{$curElement[0]}{/if}{/foreach}</span></td>
       <td style="text-align:right; white-space:nowrap;"><span class="fontNavBar">{if $smarty.config.debug}&nbsp;{$action} &ndash; {$subAction}{/if}{if $subAction == 'ForumIndex'}<a href="{$smarty.const.INDEXFILE}?mode=markAll{$smarty.const.SID_AMPER}">{$modules.Language->getString('mark_all_forums_as_read')}</a>{elseif ($action == 'Forum' || $action == 'PostNew' || $action == 'Posting') && $subAction != 'ForumIndex' && $subAction != 'ViewTodaysPosts' && $subAction != 'Message'}<a href="{$smarty.const.INDEXFILE}?faction=newtopic&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/new_topic.png" alt="" class="imageButton" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=newpoll&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/new_poll.png" alt="" class="imageButton" /></a>{if $subAction == 'ViewTopic'}&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=reply&amp;forum_id={$forumID}&amp;thread_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/new_reply.png" alt="" class="imageButton" /></a>{/if}{elseif $action == 'PrivateMessage' && $subAction != 'Message'}<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=send{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/new_pm.png" alt="" class="imageButton" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=overview&amp;box=out{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/pm_outbox.png" alt="" class="imageButton" /></a>{if $subAction == 'PrivateMessageViewPM' || $subAction == 'PrivateMessageConfirmDelete'}{if !$isOutbox}&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=reply&amp;pm_id={$pmID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/pm_new_reply.png" alt="" class="imageButton" /></a>{/if}&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=kill&amp;pm_id={$pmID}{$urlSuffix}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/buttons/pm_delete.png" alt="" class="imageButton" /></a>{/if}{else}&nbsp;{/if}</span></td>
      </tr>
     </table>
    </td>
   </tr>
  </table>
  <br />
