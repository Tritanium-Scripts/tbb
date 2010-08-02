<!-- AdminRankIndex -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('user_rank')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('min_posts')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('max_posts')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('stars')}</span></th>
  <th class="thsmall"></th>
 </tr>
{foreach $ranks as $curRank}
 <tr>
  <td class="td1"><span class="norm">{$curRank[1]}</span></td>
  <td class="td2" style="text-align:center;"><span class="norm">{$curRank[2]}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm">{if $curRank@last}&infin;{else}{$curRank[3]}{/if}</span></td>
  <td class="td2" style="text-align:center;"><span class="norm">{$curRank[4]}</span></td>
  <td class="td1" style="text-align:center;"><span class="small"><a href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=edit&amp;id={$curRank[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=kill&amp;id={$curRank[0]}{$smarty.const.SID_AMPER}" onclick="return confirm('{$modules.Language->getString('really_delete_this_rank')}');">{$modules.Language->getString('delete')}</a></span></td>
 </tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=new{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_rank')}</a></p>