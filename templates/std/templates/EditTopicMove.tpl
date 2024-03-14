<!-- EditTopicMove -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=topic{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('move_topic')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_MOVE_TOPIC_FORM_START}
 <tr>
  <td class="td1">
   <span class="norm">{$title|string_format:Language::getInstance()->getString('where_move_topic_x')}</span><br />
   <select name="target_forum" size="1">{foreach $cats as $curCat}
    <option value="" style="background-color:#333333; color:#FFFFFF;">--{$curCat[1]}</option>
    {foreach $forums as $curForum}{if $curForum.catID == $curCat[0]}<option value="{$curForum.forumID}">{$curForum.forumName}</option>{/if}{/foreach}
    <option value=""></option>{/foreach}
   </select><br />
   <input type="checkbox" id="isLinked" name="isLinked" value="true" style="vertical-align:middle;"{if $isLinked} checked="checked"{/if} /> <label for="isLinked" class="norm">{Language::getInstance()->getString('link_to_moved_topic')}</label><br />
   <input type="checkbox" id="isNewest" name="isNewest" value="true" style="vertical-align:middle;"{if $isNewest} checked="checked"{/if} /> <label for="isNewest" class="norm">{Language::getInstance()->getString('mark_as_newest_topic')}</label>
  </td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_MOVE_TOPIC_FORM_END}
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('move_topic')}" />{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_MOVE_TOPIC_BUTTONS}</p>
<input type="hidden" name="forum_id" value="{$forumID}" />
<input type="hidden" name="move" value="yes" />
<input type="hidden" name="topic_id" value="{$topicID}" />
<input type="hidden" name="mode" value="move" />
</form>