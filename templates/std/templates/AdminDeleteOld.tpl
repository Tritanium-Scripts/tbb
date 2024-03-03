<!-- AdminDeleteOld -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_killposts{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('delete_old_topics')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_DELETE_OLD_FORM_START}
 <tr>
  <td class="td1" style="font-weight:bold; width:10%;"><span class="norm">{Language::getInstance()->getString('forum_colon')}</span></td>
  <td class="td1" style="width:90%;">
   <select name="target_forum" size="1">
    <option value="all">{Language::getInstance()->getString('all_forums')}</option>{foreach $cats as $curCat}
    <option value="" style="background-color:#333333; color:#FFFFFF;">--{$curCat[1]}</option>
    {foreach $forums as $curForum}{if $curForum.catID == $curCat[0]}<option value="{$curForum.forumID}"{if $curForum.forumID == $deleteFromForumID} selected="selected"{/if}>{$curForum.forumName}</option>{/if}{/foreach}
    <option value=""></option>{/foreach}
   </select>
  </td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:10%;"><span class="norm">{Language::getInstance()->getString('delete_topics_older_than_colon')}</span></td>
  <td class="td1" style="width:90%;"><span class="norm"><select name="topic_age"><option value="15"{if $topicAge == 15} selected="selected"{/if}>{15|string_format:Language::getInstance()->getString('x_days')}</option><option value="30"{if $topicAge == 30} selected="selected"{/if}>{Language::getInstance()->getString('one_month')}</option><option value="60"{if $topicAge == 60} selected="selected"{/if}>{2|string_format:Language::getInstance()->getString('x_months')}</option><option value="90"{if $topicAge == 90} selected="selected"{/if}>{3|string_format:Language::getInstance()->getString('x_months')}</option><option value="180"{if $topicAge == 180} selected="selected"{/if}>{6|string_format:Language::getInstance()->getString('x_months')}</option></select></span></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_DELETE_OLD_FORM_END}{if Config::getInstance()->getCfgVal('tspacing') < 1}
 <tr><td class="td1" colspan="2"><hr /></td></tr>{/if}
 <tr><td class="td1" colspan="2" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('delete_warning')}</span></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('delete_old_topics')}" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_DELETE_OLD_BUTTONS}</p>
<input type="hidden" name="mode" value="kill" />
</form>