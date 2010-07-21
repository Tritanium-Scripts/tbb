<!-- FAQRankTable -->
<table cellpadding="3" cellspacing="0">
 <tr><th>{$modules.Language->getString('user_rank')}</th><th>{$modules.Language->getString('min_posts')}</th><th>{$modules.Language->getString('max_posts')}</th><th>{$modules.Language->getString('stars')}</th></tr>
{foreach $ranks as $curRank} <tr><td class="td1"><span class="norm">{$curRank[1]}</span></td><td class="td2" style="text-align:center;"><span class="norm">{$curRank[2]}</span></td><td class="td1" style="text-align:center;"><span class="norm">{if $curRank[3] == '-1'}&infin;{else}{$curRank[3]}{/if}</span></td><td class="td2" style="text-align:center;"><span class="norm">{$curRank[4]}</span></td></tr>{/foreach}
 <tr><td class="td1"><span class="norm">{$modules.Language->getString('moderator')}</span></td><td class="td2" style="text-align:center;"><span class="norm">-</span></td><td class="td1" style="text-align:center;"><span class="norm">-</span></td><td class="td2" style="text-align:center;"><span class="norm" style="color:{$modules.Config->getCfgVal('wio_color_mod')}">{$modules.Config->getCfgVal('stars_mod')}</span></td></tr>
 <tr><td class="td1"><span class="norm">{$modules.Language->getString('super_moderator')}</span></td><td class="td2" style="text-align:center;"><span class="norm">-</span></td><td class="td1" style="text-align:center;"><span class="norm">-</span></td><td class="td2" style="text-align:center;"><span class="norm" style="color:{$modules.Config->getCfgVal('wio_color_smod')}">{$modules.Config->getCfgVal('stars_smod')}</span></td></tr>
 <tr><td class="td1"><span class="norm">{$modules.Language->getString('administrator')}</span></td><td class="td2" style="text-align:center;"><span class="norm">-</span></td><td class="td1" style="text-align:center;"><span class="norm">-</span></td><td class="td2" style="text-align:center;"><span class="norm" style="color:{$modules.Config->getCfgVal('wio_color_admin')}">{$modules.Config->getCfgVal('stars_admin')}</span></td></tr>
</table>