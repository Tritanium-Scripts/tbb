<!-- AdminForumTopicPrefixes -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('prefix')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('color')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_TOPIC_PREFIXES_TABLE_HEAD}
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{* 0:prefixID - 1:prefix - 2:color *}
{foreach $topicPrefixes as $curPrefix}
 <tr>
  <td class="td1"><span class="norm">{$curPrefix[1]}</span></td>
  <td class="td2"><span class="norm" style="color:{$curPrefix[2]};">{$curPrefix[2]}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_TOPIC_PREFIXES_TABLE_BODY}
  <td class="td1" style="text-align:center;"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=deleteTopicPrefix&amp;forum_id={$forumID}&amp;prefixId={$curPrefix[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('delete')}</a></span>&nbsp;|&nbsp;<a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=editTopicPrefix&amp;forum_id={$forumID}&amp;prefixId={$curPrefix[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="3" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_topic_prefixes')}</span></td></tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newTopicPrefix&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_topic_prefix')}</a>{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_TOPIC_PREFIXES_OPTIONS}</p>