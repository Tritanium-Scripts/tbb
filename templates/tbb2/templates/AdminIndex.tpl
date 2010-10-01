{include file='AdminMenu.tpl'}
<!-- AdminIndex -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellCat"><span class="fontCat">{$modules.Language->getString('administration')}</span></th></tr>
 <tr>
  <td class="cellStd">
   <table style="width:100%;">
    <colgroup>
     <col width="50%" />
     <col width="50%" />
    </colgroup>
    <tr>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum{$smarty.const.SID_AMPER}">{$modules.Language->getString('forums_categories')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=forumview{$smarty.const.SID_AMPER}">{$modules.Language->getString('manage_forums')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newforum{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_forum')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=viewkg{$smarty.const.SID_AMPER}">{$modules.Language->getString('manage_categories')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newkg{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_category')}</a></span>
     </td>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=ad_user{$smarty.const.SID_AMPER}">{$modules.Language->getString('members')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_user{$smarty.const.SID_AMPER}">{$modules.Language->getString('member_search')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=new{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_member')}</a></span>
     </td>
    </tr>
    <tr>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=ad_groups{$smarty.const.SID_AMPER}">{$modules.Language->getString('groups')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_groups{$smarty.const.SID_AMPER}">{$modules.Language->getString('manage_groups')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=new{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_group')}</a></span>
     </td>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=ad_rank{$smarty.const.SID_AMPER}">{$modules.Language->getString('user_ranks')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_rank{$smarty.const.SID_AMPER}">{$modules.Language->getString('manage_ranks')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=new{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_rank')}</a></span>
     </td>
    </tr>
    <tr>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=ad_censor{$smarty.const.SID_AMPER}">{$modules.Language->getString('censorships')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_censor{$smarty.const.SID_AMPER}">{$modules.Language->getString('manage_censorships')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=new{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_censorship')}</a></span>
     </td>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=ad_ip{$smarty.const.SID_AMPER}">{$modules.Language->getString('ip_blocks')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_ip{$smarty.const.SID_AMPER}">{$modules.Language->getString('manage_ips')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_ip&amp;mode=new{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_ip')}</a></span>
     </td>
    </tr>
    <tr>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies{$smarty.const.SID_AMPER}">{$modules.Language->getString('smilies')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_smilies{$smarty.const.SID_AMPER}">{$modules.Language->getString('manages_smilies_post_icons')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=new{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_smiley')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=newt{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_post_icon')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=newa{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_asmiley')}</a></span>
     </td>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=ad_settings{$smarty.const.SID_AMPER}">{$modules.Language->getString('board_settings')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_settings{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit_settings')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=readsetfile{$smarty.const.SID_AMPER}">{$modules.Language->getString('reset_settings')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=recalculateCounters{$smarty.const.SID_AMPER}">{$modules.Language->getString('recalculate_counters')}</a></span><br />
      <span class="fontSmall">- <a href="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=clearCache{$smarty.const.SID_AMPER}">{$modules.Language->getString('clear_cache')}</a></span>
     </td>
    </tr>
    <tr>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=adminTemplate{$smarty.const.SID_AMPER}">{$modules.Language->getString('templates')}</a></span><br />
      <span class="fontSmall">{$modules.Language->getString('templates_text')}</span>
     </td>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile{$smarty.const.SID_AMPER}">{$modules.Language->getString('logfiles')}</a></span><br />
      <span class="fontSmall">{$modules.Language->getString('logfiles_text')}</span>
     </td>
    </tr>
    <tr>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=ad_news{$smarty.const.SID_AMPER}">{$modules.Language->getString('forum_news')}</a></span><br />
      <span class="fontSmall">{$modules.Language->getString('forum_news_text')}</span>
     </td>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=ad_newsletter{$smarty.const.SID_AMPER}">{$modules.Language->getString('newsletter')}</a></span><br />
      <span class="fontSmall">{$modules.Language->getString('newsletter_text')}</span>
     </td>
    </tr>
    <tr>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=ad_emailist{$smarty.const.SID_AMPER}">{$modules.Language->getString('email_list')}</a></span><br />
      <span class="fontSmall">{$modules.Language->getString('email_list_text')}</span>
     </td>
     <td style="padding:10px; vertical-align:top;">
      <span class="fontBig"><a href="{$smarty.const.INDEXFILE}?faction=ad_killposts{$smarty.const.SID_AMPER}">{$modules.Language->getString('delete_old_topics')}</a></span><br />
      <span class="fontSmall">{$modules.Language->getString('delete_old_topics_text')}</span>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="50%" />
  <col width="50%" />
 </colgroup>
 <tr><th class="cellCat" colspan="2"><span class="fontCat">{$modules.Language->getString('system_information')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm">{$modules.Language->getString('tbb_version_colon')}</span></td><td class="cellAlt"><span class="fontNorm">{$smarty.const.VERSION_PRIVATE|rtrim:'.0'}</span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm">{$modules.Language->getString('smarty_version_colon')}</span></td><td class="cellAlt"><span class="fontNorm">{$smarty.version}</span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm">{$modules.Language->getString('target_version_language_colon')}</span></td><td class="cellAlt"><span class="fontNorm">{$modules.Language->getString('TARGET_VERSION')|rtrim:'.0'}</span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm">{$modules.Language->getString('target_version_template_colon')}</span></td><td class="cellAlt"><span class="fontNorm">{$smarty.config.targetVersion|rtrim:'.0'}</span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm">{$modules.Language->getString('server_colon')}</span></td><td class="cellAlt"><span class="fontNorm">{$smarty.server.SERVER_SOFTWARE}</span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm">{$modules.Language->getString('php_version_colon')}</span></td><td class="cellAlt"><span class="fontNorm">{$smarty.const.PHP_VERSION}</span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}