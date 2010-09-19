{include file='AdminMenu.tpl'}
<!-- AdminRankIndex -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="4"><span class="fontTitle">{$modules.Language->getString('manage_ranks')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('user_rank')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('min_posts')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('stars')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $ranks as $curRank}
 <tr>
  <td class="cellStd"><span class="fontNorm">{$curRank[1]}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontNorm">{$curRank[2]}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontNorm">{for $i=1 to $curRank[4]}<img src="images/ranks/ystar.gif" alt="" />{/for}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=kill&amp;id={$curRank[0]}{$smarty.const.SID_AMPER}" onclick="return confirm('{$modules.Language->getString('really_delete_this_rank')}');"><img src="{$modules.Template->getTplDir()}images/icons/award_star_delete.png" alt="" style="vertical-align:middle;" /> {$modules.Language->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=edit&amp;id={$curRank[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=new{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/award_star_add.png" alt="" class="imageIcon" /> {$modules.Language->getString('add_new_rank')}</a></span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}