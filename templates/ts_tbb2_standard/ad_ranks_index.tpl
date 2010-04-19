<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="4"><span class="thnorm">{$lng['Normal_ranks']}</span></td></tr>
<template:nrankrow>
<tr>
 <td class="td1"><span class="norm">{$akt_rank['rank_name']}</span></td>
 <td class="td2" align="center"><span class="norm">{$akt_rank['rank_posts']}</span></td>
 <td class="td1" align="center"><span class="norm">{$akt_rank_gfx}</span></td>
 <td class="td2" align="right"><span class="small"><a href="administration.php?faction=ad_ranks&amp;mode=deleterank&amp;rank_id={$akt_rank['rank_id']}&amp;{$MYSID}">{$lng['delete']}</a> | <a href="administration.php?faction=ad_ranks&amp;mode=editrank&amp;rank_id={$akt_rank['rank_id']}&amp;{$MYSID}">{$lng['edit']}</a></span></td>
</tr>
</template>
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="3"><span class="thnorm">{$lng['Special_ranks']}</span></td></tr>
<template:srankrow>
<tr>
 <td class="td1"><span class="norm">{$akt_rank['rank_name']}</span></td>
 <td class="td2" align="center"><span class="norm">{$akt_rank_gfx}</span></td>
 <td class="td1" align="right"><span class="small"><a href="administration.php?faction=ad_ranks&amp;mode=deleterank&amp;rank_id={$akt_rank['rank_id']}&amp;{$MYSID}">{$lng['delete']}</a> | <a href="administration.php?faction=ad_ranks&amp;mode=editrank&amp;rank_id={$akt_rank['rank_id']}&amp;{$MYSID}">{$lng['edit']}</a></span></td>
</tr>
</template>
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{$lng['Other_options']}</span></tr></tr>
<tr><td class="td1"><span class="norm"><a href="administration.php?faction=ad_ranks&amp;mode=addrank&amp;{$MYSID}">{$lng['Add_rank']}</a></span></td></tr>
</table>
