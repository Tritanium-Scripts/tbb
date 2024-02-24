{include file='AdminMenu.tpl'}
<!-- AdminForumIndex -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="4"><span class="fontTitle">{Language::getInstance()->getString('manage_forums')}</span></th></tr>
{foreach $catTable as $curCatID => $curCatName}{if $curCatID > 0}
 <tr>
  <th class="cellCat" style="width:1em;"></th>
  <th class="cellCat"><span class="fontCat">-- {$curCatName}</span></th>
  <th class="cellCat"><span class="fontCat"></span></th>
  <th class="cellCat" style="text-align:right;"><span class="fontCatSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=chgkg&amp;id={$curCatID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newforum&amp;kg={$curCatID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_forum')}</a></span></th>
 </tr>
{foreach $forums as $curForum}{if $curForum.catID == $curCatID}
 <tr onmouseover="setRowCellsClass(this, 'cellHighlight');" onmouseout="restoreRowCellsClass(this);">
  <td class="cellStd" style="text-align:center;">{if !empty($curForum.image)}<img src="{$curForum.image}" alt="" style="height:1em; width:1em;" />{/if}</td>
  <td class="cellStd"><span class="fontNorm">---- {$curForum.name}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=moveforum{if $curForum@first}down&amp;id={$curForum.id}{$smarty.const.SID_AMPER}">&d{elseif $curForum@last}up&amp;id={$curForum.id}{$smarty.const.SID_AMPER}">&u{else}down&amp;id={$curForum.id}{$smarty.const.SID_AMPER}">&darr;</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=moveforumup&amp;id={$curForum.id}{$smarty.const.SID_AMPER}">&u{/if}arr;</a></span></td>
  <td class="cellStd" style="text-align:right;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=editTopicPrefixes&amp;forum_id={$curForum.id}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit_topic_prefixes')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=edit_forum_rights&amp;forum_id={$curForum.id}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit_special_rights')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;ad_forum_id={$curForum.id}&amp;mode=change{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit')}</a></span></td>
 </tr>{/if}
{foreachelse}
 <tr><td class="cellStd" colspan="4" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_forums_available')}</span></td></tr>
{/foreach}{/if}
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('no_category')}</span></th></tr>
{foreach $forums as $curForum}{if $curForum.catID == -1}
 <tr onmouseover="setRowCellsClass(this, 'cellHighlight');" onmouseout="restoreRowCellsClass(this);">
  <td class="cellStd"><span class="fontNorm">{$curForum.name}</span></td>
  <td class="cellStd" style="text-align:right;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=editTopicPrefixes&amp;forum_id={$curForum.id}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit_topic_prefixes')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=edit_forum_rights&amp;forum_id={$curForum.id}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit_special_rights')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;ad_forum_id={$curForum.id}&amp;mode=change{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit')}</a></span></td>
 </tr>{/if}
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newkg{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_category')}</a></span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newforum{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_forum')}</a></span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}