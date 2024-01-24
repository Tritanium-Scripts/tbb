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
  <style type="text/css">
   input[type=radio]
   {
    vertical-align:middle;
   }

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
  <script type="text/javascript">
/* <![CDATA[ */
/**
 * Negates the checked state of stated "delete" checkboxes.
 *
 * @author Chrissyx
 */
function negateBoxes(id)
{
	for(var i=0,boxes; i<(boxes = document.getElementsByTagName('input')).length; i++)
		if(boxes[i].name.substring(0, 6+id.length) == 'delete' + id)
			boxes[i].checked = boxes[i].checked == false ? true : false;
};
/* ]]> */
  </script>
  <title>{$smarty.config.navBarDelim|implode:NavBar::getInstance()->getNavBar(false)}</title>
 </head>
 <body style="padding-top:1em;"{if PrivateMessage::getInstance()->isRemind() && $unreadPMs > 0} onload="if(confirm('{if $unreadPMs == 1}{Language::getInstance()->getString('you_have_one_new_pm')}{elseif $unreadPMs > 1}{$unreadPMs|string_format:Language::getInstance()->getString('you_have_x_new_pms')}{/if}')) document.location='{$smarty.const.INDEXFILE}?faction=pm';"{/if}>
  <div id="main">

  <!-- Header -->
  <table cellspacing="0" cellpadding="0" style="text-align:center; vertical-align:middle; width:100%;">
   <colgroup width="50%"></colgroup>
   <tr>
    {if Config::getInstance()->getCfgVal('forum_logo')}
    <td><a href="{$smarty.const.INDEXFILE}{$smarty.const.SID_QMARK}" onfocus="this.blur();"><img src="{Config::getInstance()->getCfgVal('forum_logo')}" alt="{Config::getInstance()->getCfgVal('forum_name')}" /></a></td>
    {/if}
    <td>
     <span class="finfo">{if Auth::getInstance()->isLoggedIn()}{sprintf(Language::getInstance()->getString('hello_x_time'), Auth::getInstance()->getUserNick(), $currentTime)}</span><br />
     <span class="tbar">
     <a href="{$smarty.const.INDEXFILE}?faction=profile&amp;mode=edit&amp;profile_id={Auth::getInstance()->getUserID()}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('my_profile')}</a> |
     <a href="{$smarty.const.INDEXFILE}?faction=faq{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('faq')}</a> |
     <a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=overview{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('private_messages')}</a> |
     <a href="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('search')}</a> | {if Config::getInstance()->getCfgVal('wio') != 0}
     <a href="{$smarty.const.INDEXFILE}?faction=wio{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('who_is_online')}</a> | {/if}{if Config::getInstance()->getCfgVal('activate_mlist') != 0}
     <a href="{$smarty.const.INDEXFILE}?faction=mlist{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('member_list')}</a> | {/if}
     <a href="{$smarty.const.INDEXFILE}?faction=todaysPosts{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('todays_posts')}</a> |
     <a href="{$smarty.const.INDEXFILE}?faction=newsletter{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('newsletter_archive')}</a> | {if Config::getInstance()->getCfgVal('activate_calendar') != 0}
     <a href="{$smarty.const.INDEXFILE}?faction=calendar{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('calendar')}</a> | {/if}
     <a href="{$smarty.const.INDEXFILE}?faction=logout{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('logout')}</a>
     {else}{Config::getInstance()->getCfgVal('forum_name')}</span><br />
     <span class="tbar">
     <a href="{$smarty.const.INDEXFILE}?faction=register{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('register')}</a> |
     <a href="{$smarty.const.INDEXFILE}?faction=faq{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('faq')}</a> |
     <a href="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('search')}</a> | {if Config::getInstance()->getCfgVal('wio') == 1}
     <a href="{$smarty.const.INDEXFILE}?faction=wio{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('who_is_online')}</a> | {/if}{if Config::getInstance()->getCfgVal('activate_mlist') == 1}
     <a href="{$smarty.const.INDEXFILE}?faction=mlist{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('member_list')}</a> | {/if}
     <a href="{$smarty.const.INDEXFILE}?faction=todaysPosts{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('todays_posts')}</a> | {if Config::getInstance()->getCfgVal('activate_calendar') != 0}
     <a href="{$smarty.const.INDEXFILE}?faction=calendar{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('calendar')}</a> | {/if}
     <a href="{$smarty.const.INDEXFILE}?faction=login{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('login')}</a>
     {/if}</span>
    </td>
   </tr>
  </table>
  <br /><br />

  <!-- NavBar -->
  <table class="navbar" cellspacing="0" cellpadding="0" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
   <tr>
    <td class="navbar"><span class="navbar">&nbsp;{foreach NavBar::getInstance()->getNavBar() as $curElement}{if !$curElement@last}<a href="{$curElement[1]}" class="navbar">{$curElement[0]}</a>{$smarty.config.navBarDelim}{else}{$curElement[0]}{/if}{/foreach}</span></td>
    <td class="navbar" style="text-align:right;"><span class="navbar">{if $smarty.config.debug}&nbsp;{$action} &ndash; {$subAction}{/if}{if $subAction == 'ForumIndex'}<a href="{$smarty.const.INDEXFILE}?mode=markAll{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('mark_all_forums_as_read')}</a>{elseif ($action == 'Forum' || $action == 'PostNew' || $action == 'Posting') && $subAction != 'ForumIndex' && $subAction != 'ViewTodaysPosts' && $subAction != 'Message'}<a href="{$smarty.const.INDEXFILE}?faction=newtopic&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/newtopic.gif" alt="" style="vertical-align:middle;" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=newpoll&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/newpoll.gif" alt="" style="vertical-align:middle;" /></a>{if $subAction == 'ViewTopic'}&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=reply&amp;forum_id={$forumID}&amp;thread_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/newreply.gif" alt="" style="vertical-align:middle;" /></a>{/if}{elseif $action == 'PrivateMessage' && $subAction != 'Message'}<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=send{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/newpm.png" alt="" style="vertical-align:middle;" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=overview&amp;box=out{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/outbox.png" alt="" style="vertical-align:middle;" /></a>{if $subAction == 'PrivateMessageViewPM' || $subAction == 'PrivateMessageConfirmDelete'}{if !$isOutbox}&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=reply&amp;pm_id={$pmID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/newreply.gif" alt="" style="vertical-align:middle;" /></a>{/if}&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=kill&amp;pm_id={$pmID}{$urlSuffix}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/delete.png" alt="" style="vertical-align:middle;" /></a>{/if}{else}&nbsp;{/if}</span></td>
   </tr>
  </table>
  <br />
