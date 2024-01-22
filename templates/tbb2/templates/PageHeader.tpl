<?xml version="1.0" encoding="{Language::getInstance()->getString('html_encoding')}" standalone="no" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{Language::getInstance()->getString('html_direction')}" lang="{Language::getInstance()->getLangCode()}" xml:lang="{Language::getInstance()->getLangCode()}">
 <head>
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset={Language::getInstance()->getString('html_encoding')}" />
  <meta http-equiv="Content-Language" content="{Language::getInstance()->getLangCode()}" />
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="author" content="Tritanium Scripts" />
  <meta name="copyright" content="&copy; 2010&ndash;{$smarty.const.COPYRIGHT_YEAR} Tritanium Scripts" />
  <meta name="description" content="{sprintf(Language::getInstance()->getString('html_description'), Config::getInstance()->getCfgVal('site_name'), $smarty.const.VERSION_PUBLIC)}" />
  <meta name="generator" content="Notepad 4.10.1998" />
  <meta name="keywords" content="TBB,Tritanium,Tritanium Scripts,TBB {$smarty.const.VERSION_PUBLIC},Tritanium Bulletin Board,{Config::getInstance()->getCfgVal('site_name')},{','|implode:NavBar::getInstance()->getNavBar(false)}" />
  <meta name="revisit-after" content="7 days" />
  <meta name="robots" content="all" />
  <link href="{Template::getInstance()->getTplDir()}images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <link href="{Template::getInstance()->getTplDir()}{Auth::getInstance()->getUserStyle()}" media="all" rel="stylesheet" />
  <link href="{$smarty.const.INDEXFILE}?faction=rssFeed" rel="alternate" title="{Config::getInstance()->getCfgVal('forum_name')|string_format:Language::getInstance()->getString('x_rss_feed')}" type="application/rss+xml" />
  <script src="{Template::getInstance()->getTplDir()}scripts/scripts.js" type="text/javascript"></script>
  <title>{$smarty.config.navBarDelim|implode:NavBar::getInstance()->getNavBar(false)}</title>
{plugin_hook hook=PlugIns::HOOK_TPL_PAGE_HEADER_HTML_HEAD}
 </head>
 <body{if PrivateMessage::getInstance()->isRemind() && $unreadPMs > 0} onload="if(confirm('{if $unreadPMs == 1}{Language::getInstance()->getString('you_have_one_new_pm')}{elseif $unreadPMs > 1}{$unreadPMs|string_format:Language::getInstance()->getString('you_have_x_new_pms')}{/if}')) document.location='{$smarty.const.INDEXFILE}?faction=pm';"{/if}>
  <div id="mainBox" style="width:{Config::getInstance()->getCfgVal('twidth')};">

  <!-- Header -->
  <div id="headerBox">
   <div id="headerInnerBox">
    <a href="{$smarty.const.INDEXFILE}{$smarty.const.SID_QMARK}" onfocus="this.blur();"><img src="{if Config::getInstance()->getCfgVal('forum_logo')}{Config::getInstance()->getCfgVal('forum_logo')}" alt="{Config::getInstance()->getCfgVal('forum_name')}{else}{Template::getInstance()->getTplDir()}images/logo.jpg" alt="{/if}" /></a>
    <div id="headerNavigationBox">{if Auth::getInstance()->isLoggedIn()}
     <a href="{$smarty.const.INDEXFILE}?faction=profile&amp;mode=edit&amp;profile_id={Auth::getInstance()->getUserID()}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/my_profile.png" class="imageButton" alt="{Language::getInstance()->getString('my_profile')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=faq{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/faq.png" class="imageButton" alt="{Language::getInstance()->getString('faq')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=overview{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/pms.png" class="imageButton" alt="{Language::getInstance()->getString('private_messages')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/search.png" class="imageButton" alt="{Language::getInstance()->getString('search')}" /></a>{if Config::getInstance()->getCfgVal('wio') != 0}
     <a href="{$smarty.const.INDEXFILE}?faction=wio{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/wio.png" class="imageButton" alt="{Language::getInstance()->getString('who_is_online')}" /></a>{/if}{if Config::getInstance()->getCfgVal('activate_mlist') != 0}
     <a href="{$smarty.const.INDEXFILE}?faction=mlist{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/member_list.png" class="imageButton" alt="{Language::getInstance()->getString('member_list')}" /></a>{/if}
     <a href="{$smarty.const.INDEXFILE}?faction=todaysPosts{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/todays_posts.png" class="imageButton" alt="{Language::getInstance()->getString('todays_posts')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=newsletter{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/newsletter.png" class="imageButton" alt="{Language::getInstance()->getString('newsletter_archive')}" /></a>{if Config::getInstance()->getCfgVal('activate_calendar') != 0}
     <a href="{$smarty.const.INDEXFILE}?faction=calendar{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/calendar.png" class="imageButton" alt="{Language::getInstance()->getString('calendar')}" /></a>{/if}
{plugin_hook hook=PlugIns::HOOK_TPL_PAGE_HEADER_TOOLBAR_LOGGED_IN}
     <a href="{$smarty.const.INDEXFILE}?faction=logout{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/logout.png" class="imageButton" alt="{Language::getInstance()->getString('logout')}" /></a>
{else}
     <a href="{$smarty.const.INDEXFILE}?faction=register{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/register.png" class="imageButton" alt="{Language::getInstance()->getString('register')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=faq{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/faq.png" class="imageButton" alt="{Language::getInstance()->getString('faq')}" /></a>
     <a href="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/search.png" class="imageButton" alt="{Language::getInstance()->getString('search')}" /></a>{if Config::getInstance()->getCfgVal('wio') == 1}
     <a href="{$smarty.const.INDEXFILE}?faction=wio{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/wio.png" class="imageButton" alt="{Language::getInstance()->getString('who_is_online')}" /></a>{/if}{if Config::getInstance()->getCfgVal('activate_mlist') == 1}
     <a href="{$smarty.const.INDEXFILE}?faction=mlist{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/member_list.png" class="imageButton" alt="{Language::getInstance()->getString('member_list')}" /></a>{/if}
     <a href="{$smarty.const.INDEXFILE}?faction=todaysPosts{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/todays_posts.png" class="imageButton" alt="{Language::getInstance()->getString('todays_posts')}" /></a>{if Config::getInstance()->getCfgVal('activate_calendar') != 0}
     <a href="{$smarty.const.INDEXFILE}?faction=calendar{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/calendar.png" class="imageButton" alt="{Language::getInstance()->getString('calendar')}" /></a>{/if}
{plugin_hook hook=PlugIns::HOOK_TPL_PAGE_HEADER_TOOLBAR_LOGGED_OUT}
     <a href="{$smarty.const.INDEXFILE}?faction=login{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/login.png" class="imageButton" alt="{Language::getInstance()->getString('login')}" /></a>{/if}
    </div>
   </div>
  </div>

  <p id="headerInfoBox"><img src="{Template::getInstance()->getTplDir()}images/icons/info.png" alt="" class="imageIcon" /> {if Auth::getInstance()->isLoggedIn()}{sprintf(Language::getInstance()->getString('hello_x_time'), Auth::getInstance()->getUserNick(), $currentTime)}. {if $unreadPMs > 0}<a href="{$smarty.const.INDEXFILE}?faction=pm{$smarty.const.SID_AMPER}" style="font-weight:bold;">{Language::getInstance()->getString('new_pms_received')}</a>{else}{Language::getInstance()->getString('no_new_pms')}{/if}{else}{Config::getInstance()->getCfgVal('forum_name')|string_format:$smarty.config.langWelcome}{/if}</p>

  <!-- NavBar -->
  <table class="tableNavBar">
   <tr>
    <td class="cellNavBarBig">
     <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
      <tr>
       <td><span class="fontNavBar">&nbsp;{foreach NavBar::getInstance()->getNavBar() as $curElement}{if !$curElement@last}<a href="{$curElement[1]}">{$curElement[0]}</a>{$smarty.config.navBarDelim}{else}{$curElement[0]}{/if}{/foreach}</span></td>
       <td style="text-align:right; white-space:nowrap;"><span class="fontNavBar">{if $smarty.config.debug}&nbsp;{$action} &ndash; {$subAction}{/if}{if $subAction == 'ForumIndex'}<a href="{$smarty.const.INDEXFILE}?mode=markAll{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('mark_all_forums_as_read')}</a>{elseif ($action == 'Forum' || $action == 'PostNew' || $action == 'Posting') && $subAction != 'ForumIndex' && $subAction != 'ViewTodaysPosts' && $subAction != 'Message'}<a href="{$smarty.const.INDEXFILE}?faction=newtopic&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/new_topic.png" alt="" class="imageButton" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=newpoll&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/new_poll.png" alt="" class="imageButton" /></a>{if $subAction == 'ViewTopic'}&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=reply&amp;forum_id={$forumID}&amp;thread_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/new_reply.png" alt="" class="imageButton" /></a>{/if}{elseif $action == 'PrivateMessage' && $subAction != 'Message'}<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=send{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/new_pm.png" alt="" class="imageButton" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=overview&amp;box=out{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/pm_outbox.png" alt="" class="imageButton" /></a>{if $subAction == 'PrivateMessageViewPM' || $subAction == 'PrivateMessageConfirmDelete'}{if !$isOutbox}&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=reply&amp;pm_id={$pmID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/pm_new_reply.png" alt="" class="imageButton" /></a>{/if}&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=kill&amp;pm_id={$pmID}{$urlSuffix}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/pm_delete.png" alt="" class="imageButton" /></a>{/if}{else}&nbsp;{/if}</span></td>
      </tr>
     </table>
    </td>
   </tr>
  </table>
  <br />
