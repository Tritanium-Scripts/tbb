{include file='AdminMenu.tpl'}
<!-- AdminForumTopicPrefixes -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="3"><span class="fontTitle">{Language::getInstance()->getString('edit_topic_prefixes')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('prefix')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('color')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{* 0:prefixID - 1:prefix - 2:color *}
{foreach $topicPrefixes as $curPrefix}
 <tr>
  <td class="cellStd"><span class="fontNorm">{$curPrefix[1]}</span></td>
  <td class="cellAlt"><span class="fontNorm" style="color:{$curPrefix[2]};">{$curPrefix[2]}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=deleteTopicPrefix&amp;forum_id={$forumID}&amp;prefixId={$curPrefix[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=editTopicPrefix&amp;forum_id={$forumID}&amp;prefixId={$curPrefix[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="3" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_topic_prefixes')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newTopicPrefix&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_topic_prefix')}</a></span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}