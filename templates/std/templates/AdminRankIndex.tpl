<!-- AdminRankIndex -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('user_rank')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('min_posts')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('max_posts')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('stars')}</span></th>
  <th class="thsmall"></th>
 </tr>
{foreach $ranks as $curRank}
 <tr>
  <td class="td1"><span class="norm">{$curRank[1]}</span></td>
  <td class="td2" style="text-align:center;"><span class="norm">{$curRank[2]}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm">{if $curRank@last}&infin;{else}{$curRank[3]}{/if}</span></td>
  <td class="td2" style="text-align:center;"><span class="norm">{$curRank[4]}</span></td>
  <td class="td1" style="text-align:center;"><span class="small"><a href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=edit&amp;id={$curRank[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=kill&amp;id={$curRank[0]}{$smarty.const.SID_AMPER}" onclick="return confirm('{Language::getInstance()->getString('really_delete_this_rank')}');">{Language::getInstance()->getString('delete')}</a></span></td>
 </tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=new{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_rank')}</a></p>