<!-- EditTopicMove -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=topic{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('move_topic')}</span></th></tr>
 <tr>
  <td class="cellStd">
   <span class="fontNorm">{$title|string_format:$modules.Language->getString('where_move_topic_x')}</span><br />
   <select class="formSelect" name="target_forum" size="8">{foreach $cats as $curCat}
    <option value="" style="background-color:#333333; color:#FFFFFF;">--{$curCat[1]}</option>
    {foreach $forums as $curForum}{if $curForum.catID == $curCat[0]}<option value="{$curForum.forumID}">{$curForum.forumName}</option>{/if}{/foreach}
    <option value=""></option>{/foreach}
   </select>
  </td>
 </tr>
 <tr><td class="cellCat"><span class="fontCat">{$modules.Language->getString('options')}</span></td></tr>
 <tr>
  <td class="cellStd">
   <input type="checkbox" id="isLinked" name="isLinked" value="true"{if $isLinked} checked="checked"{/if} /> <label for="isLinked" class="fontNorm">{$modules.Language->getString('link_to_moved_topic')}</label><br />
   <input type="checkbox" id="isNewest" name="isNewest" value="true"{if $isNewest} checked="checked"{/if} /> <label for="isNewest" class="fontNorm">{$modules.Language->getString('mark_as_newest_topic')}</label>
  </td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('move_topic')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="forum_id" value="{$forumID}" />
<input type="hidden" name="move" value="yes" />
<input type="hidden" name="topic_id" value="{$topicID}" />
<input type="hidden" name="mode" value="move" />
</form>