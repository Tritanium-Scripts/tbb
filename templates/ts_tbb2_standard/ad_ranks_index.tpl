<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="4"><span class="fonttitle">{$LNG['Normal_ranks']}</span></td></tr>
<template:nrankrow>
<tr>
 <td class="cellstd"><span class="fontnorm">{$akt_rank['rank_name']}</span></td>
 <td class="cellalt" align="center"><span class="fontnorm">{$akt_rank['rank_posts']}</span></td>
 <td class="cellstd" align="center"><span class="fontnorm">{$akt_rank_gfx}</span></td>
 <td class="cellalt" align="right"><span class="fontsmall"><a href="administration.php?faction=ad_ranks&amp;mode=deleterank&amp;rank_id={$akt_rank['rank_id']}&amp;{$MYSID}">{$LNG['delete']}</a> | <a href="administration.php?faction=ad_ranks&amp;mode=editrank&amp;rank_id={$akt_rank['rank_id']}&amp;{$MYSID}">{$LNG['edit']}</a></span></td>
</tr>
</template>
</table>
<br />
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="3"><span class="fonttitle">{$LNG['Special_ranks']}</span></td></tr>
<template:srankrow>
<tr>
 <td class="cellstd"><span class="fontnorm">{$akt_rank['rank_name']}</span></td>
 <td class="cellalt" align="center"><span class="fontnorm">{$akt_rank_gfx}</span></td>
 <td class="cellstd" align="right"><span class="fontsmall"><a href="administration.php?faction=ad_ranks&amp;mode=deleterank&amp;rank_id={$akt_rank['rank_id']}&amp;{$MYSID}">{$LNG['delete']}</a> | <a href="administration.php?faction=ad_ranks&amp;mode=editrank&amp;rank_id={$akt_rank['rank_id']}&amp;{$MYSID}">{$LNG['edit']}</a></span></td>
</tr>
</template>
</table>
<br />
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle"><span class="fonttitle">{$LNG['Other_options']}</span></tr></tr>
<tr><td class="cellstd"><span class="fontnorm"><a href="administration.php?faction=ad_ranks&amp;mode=addrank&amp;{$MYSID}">{$LNG['Add_rank']}</a></span></td></tr>
</table>
