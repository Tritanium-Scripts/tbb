{include file='AdminMenu.tpl'}
<!-- AdminDeleteOld -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_killposts{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="15%" />
  <col width="85%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('delete_old_topics')}</span></th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('forum_colon')}</span></td>
  <td class="cellAlt">
   <select class="formSelect" name="target_forum" size="1">
    <option value="all">{Language::getInstance()->getString('all_forums')}</option>{foreach $cats as $curCat}
    <option value="" style="background-color:#333333; color:#FFFFFF;">--{$curCat[1]}</option>
    {foreach $forums as $curForum}{if $curForum.catID == $curCat[0]}<option value="{$curForum.forumID}"{if $curForum.forumID == $deleteFromForumID} selected="selected"{/if}>{$curForum.forumName}</option>{/if}{/foreach}
    <option value=""></option>{/foreach}
   </select>
  </td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('delete_topics_older_than_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm"><select class="formSelect" name="topic_age"><option value="15"{if $topicAge == 15} selected="selected"{/if}>{15|string_format:Language::getInstance()->getString('x_days')}</option><option value="30"{if $topicAge == 30} selected="selected"{/if}>{Language::getInstance()->getString('one_month')}</option><option value="60"{if $topicAge == 60} selected="selected"{/if}>{2|string_format:Language::getInstance()->getString('x_months')}</option><option value="90"{if $topicAge == 90} selected="selected"{/if}>{3|string_format:Language::getInstance()->getString('x_months')}</option><option value="180"{if $topicAge == 180} selected="selected"{/if}>{6|string_format:Language::getInstance()->getString('x_months')}</option></select></span></td>
 </tr>
 <tr><td class="cellInfoBox" colspan="2" style="font-weight:bold;"><span class="fontNorm"><img src="{Template::getInstance()->getTplDir()}images/icons/info.png" alt="" class="imageIcon" /> {Language::getInstance()->getString('delete_warning')}</span></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('delete_old_topics')}" /></p>
<input type="hidden" name="mode" value="kill" />
</form>
{include file='AdminMenuTail.tpl'}