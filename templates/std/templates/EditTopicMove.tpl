<!-- EditTopicMove -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=topic{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('move_topic')}</span></th></tr>
 <tr>
  <td class="td1">
   <span class="norm">{$title|string_format:$modules.Language->getString('where_move_topic_x')}</span><br />
   <select name="target_forum" size="1">{foreach $cats as $curCat}
    <option value="" style="background-color:#333333; color:#FFFFFF;">--{$curCat[1]}</option>
    {foreach $forums as $curForum}{if $curForum.catID == $curCat[0]}<option value="{$curForum.forumID}">{$curForum.forumName}</option>{/if}{/foreach}
    <option value=""></option>{/foreach}
   </select><br />
   <input type="checkbox" id="isLinked" name="isLinked" value="true" style="vertical-align:middle;"{if $isLinked} checked="checked"{/if} /> <label for="isLinked" class="norm">{$modules.Language->getString('link_to_moved_topic')}</label><br />
   <input type="checkbox" id="isNewest" name="isNewest" value="true" style="vertical-align:middle;"{if $isNewest} checked="checked"{/if} /> <label for="isNewest" class="norm">{$modules.Language->getString('mark_as_newest_topic')}</label>
  </td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('move_topic')}" /></p>
<input type="hidden" name="forum_id" value="{$forumID}" />
<input type="hidden" name="move" value="yes" />
<input type="hidden" name="topic_id" value="{$topicID}" />
<input type="hidden" name="mode" value="move" />
</form>