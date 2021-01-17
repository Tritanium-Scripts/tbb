<!-- AdminForumIndex -->
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newforum{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_forum')}</a></p>
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('name')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('description')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('moderators')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('category')}</span></th>
  <th class="thsmall" colspan="2"><span class="thsmall">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $forums as $curForum}
 <tr>
  <td class="td1" style="vertical-align:top;"><span class="small">{$curForum.name}</span></td>
  <td class="td2" style="vertical-align:top;"><span class="small">{$curForum.descr}</span></td>
  <td class="td1" style="vertical-align:top;"><span class="small">{if is_array($curForum.mods)}{', '|implode:$curForum.mods}{else}{$curForum.mods}{/if}</span></td>
  <td class="td2" style="vertical-align:top;"><span class="small">{if isset($catTable[$curForum.catID])}{$catTable[$curForum.catID]}{else}{$catTable[-1]}{/if}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=moveforum{if $curForum@first}down&amp;id={$curForum.id}{$smarty.const.SID_AMPER}">&d{elseif $curForum@last}up&amp;id={$curForum.id}{$smarty.const.SID_AMPER}">&u{else}down&amp;id={$curForum.id}{$smarty.const.SID_AMPER}">&darr;</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=moveforumup&amp;id={$curForum.id}{$smarty.const.SID_AMPER}">&u{/if}arr;</a></span></td>
  <td class="td2" style="text-align:center;"><span class="small"><a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;ad_forum_id={$curForum.id}&amp;mode=change{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="6" style="text-align:center;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('no_forums_available')}</span></td></tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newforum{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_forum')}</a></p>