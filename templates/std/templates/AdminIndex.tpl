<!-- AdminIndex -->
{if $isNewVersion}<div id="updateFrame">
 <table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
  <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('news_from_tritanium_scripts')}</span></th></tr>
  <tr><td class="td1"><span class="norm">{$versionNews}</span></td></tr>
 </table>
 <br />
</div>{/if}
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('administration')}</span></th></tr>
 <tr>
  <td class="td1" style="vertical-align:top; width:50%;">
   <span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('forums_categories')}</a></span><br />
   <span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=forumview{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('manage_forums')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newforum{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_forum')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=viewkg{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('manage_categories')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newkg{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_category')}</a></span>
  </td>
  <td class="td1" style="vertical-align:top; width:50%;">
   <span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_user{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('members')}</a></span><br />
   <span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_user{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('member_search')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=new{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_member')}</a></span>
  </td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top; width:50%;">
   <span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_groups{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('groups')}</a></span><br />
   <span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_groups{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('manage_groups')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=new{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_group')}</a></span>
  </td>
  <td class="td1" style="vertical-align:top; width:50%;">
   <span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_rank{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('user_ranks')}</a></span><br />
   <span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_rank{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('manage_ranks')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=new{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_rank')}</a></span>
  </td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top; width:50%;"><span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_censor{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('censorships')}</a></span><br />
   <span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_censor{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('manage_censorships')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=new{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_censorship')}</a></span>
  </td>
  <td class="td1" style="vertical-align:top; width:50%;"><span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_ip{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('ip_blocks')}</a></span><br />
   <span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_ip{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('manage_ips')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_ip&amp;mode=new{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_ip')}</a></span>
  </td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top; width:50%;"><span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('smilies')}</a></span><br />
   <span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_smilies{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('manages_smilies_post_icons')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=new{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_smiley')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=newt{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_post_icon')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=newa{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_asmiley')}</a></span>
  </td>
  <td class="td1" style="vertical-align:top; width:50%;"><span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_settings{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('board_settings')}</a></span><br />
   <span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_settings{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit_settings')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=readsetfile{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('reset_settings')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=recalculateCounters{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('recalculate_counters')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=rebuildTopicIndex{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('rebuild_topic_index')}</a><br />
   &nbsp;&nbsp;&nbsp;&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=clearCache{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('clear_cache')}</a></span>
  </td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top; width:50%;"><span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=adminTemplate{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('templates')}</a></span><br /><span class="small">{Language::getInstance()->getString('templates_text')}</span></td>
  <td class="td1" style="vertical-align:top; width:50%;"><span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('logfiles')}</a></span><br /><span class="small">{Language::getInstance()->getString('logfiles_text')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top; width:50%;"><span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_news{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('forum_news')}</a></span><br /><span class="small">{Language::getInstance()->getString('forum_news_text')}</span></td>
  <td class="td1" style="vertical-align:top; width:50%;"><span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_newsletter{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('newsletter')}</a></span><br /><span class="small">{Language::getInstance()->getString('newsletter_text')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top; width:50%;"><span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_emailist{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('email_list')}</a></span><br /><span class="small">{Language::getInstance()->getString('email_list_text')}</span></td>
  <td class="td1" style="vertical-align:top; width:50%;"><span class="norm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_killposts{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('delete_old_topics')}</a></span><br /><span class="small">{Language::getInstance()->getString('delete_old_topics_text')}</span></td>
 </tr>
</table>
<br />
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <colgroup>
  <col width="50%" />
  <col width="50%" />
 </colgroup>
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('system_information')}</span></th></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('tbb_version_colon')}</span></td><td class="td1"><span class="norm">{$smarty.const.VERSION_PRIVATE|rtrim:'.0'}</span></td></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('template_engine_colon')}</span></td><td class="td1"><span class="norm">{$smarty.version}</span></td></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('target_version_language_colon')}</span></td><td class="td1"><span class="norm">{Language::getInstance()->getString('TARGET_VERSION')|rtrim:'.0'}</span></td></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('target_version_template_colon')}</span></td><td class="td1"><span class="norm">{$smarty.config.targetVersion|rtrim:'.0'}</span></td></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('server_colon')}</span></td><td class="td1"><span class="norm">{$smarty.server.SERVER_SOFTWARE}</span></td></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('php_version_colon')}</span></td><td class="td1"><span class="norm">{$smarty.const.PHP_VERSION}</span></td></tr>
</table>