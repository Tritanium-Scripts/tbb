{include file='AdminMenu.tpl'}
<!-- AdminRankIndex -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="4"><span class="fontTitle">{Language::getInstance()->getString('manage_ranks')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('user_rank')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('min_posts')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('stars')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $ranks as $curRank}
 <tr>
  <td class="cellStd"><span class="fontNorm">{$curRank[1]}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontNorm">{$curRank[2]}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontNorm">{for $i=1 to $curRank[4]}<img src="images/ranks/ystar.gif" alt="" />{/for}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=kill&amp;id={$curRank[0]}{$smarty.const.SID_AMPER}" onclick="return confirm('{Language::getInstance()->getString('really_delete_this_rank')}');"><img src="{Template::getInstance()->getTplDir()}images/icons/award_star_delete.png" alt="{Language::getInstance()->getString('delete')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=edit&amp;id={$curRank[0]}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/award_star_edit.png" alt="{Language::getInstance()->getString('edit')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('edit')}</a></span></td>
 </tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=new{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/award_star_add.png" alt="{Language::getInstance()->getString('add_new_rank')}" class="imageIcon" /> {Language::getInstance()->getString('add_new_rank')}</a></span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}