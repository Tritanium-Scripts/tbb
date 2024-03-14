<!-- EditTopicMove -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=topic{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('move_topic')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_MOVE_TOPIC_FORM_START}
 <tr>
  <td class="cellStd">
   <span class="fontNorm">{$title|string_format:Language::getInstance()->getString('where_move_topic_x')}</span><br />
   <select class="formSelect" name="target_forum" size="8">{foreach $cats as $curCat}
    <option value="" style="background-color:#333333; color:#FFFFFF;">--{$curCat[1]}</option>
    {foreach $forums as $curForum}{if $curForum.catID == $curCat[0]}<option value="{$curForum.forumID}">{$curForum.forumName}</option>{/if}{/foreach}
    <option value=""></option>{/foreach}
   </select>
  </td>
 </tr>
 <tr><td class="cellCat"><span class="fontCat">{Language::getInstance()->getString('options')}</span></td></tr>
 <tr>
  <td class="cellStd">
   <input type="checkbox" id="isLinked" name="isLinked" value="true"{if $isLinked} checked="checked"{/if} /> <label for="isLinked" class="fontNorm">{Language::getInstance()->getString('link_to_moved_topic')}</label><br />
   <input type="checkbox" id="isNewest" name="isNewest" value="true"{if $isNewest} checked="checked"{/if} /> <label for="isNewest" class="fontNorm">{Language::getInstance()->getString('mark_as_newest_topic')}</label>
  </td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_MOVE_TOPIC_FORM_END}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('move_topic')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_MOVE_TOPIC_BUTTONS}</p>
<input type="hidden" name="forum_id" value="{$forumID}" />
<input type="hidden" name="move" value="yes" />
<input type="hidden" name="topic_id" value="{$topicID}" />
<input type="hidden" name="mode" value="move" />
</form>