<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="5"><span class="fonttitle">{$LNG['Smilies']}</span></td></tr>
<template:smileyrow>
 <tr>
  <td class="cellstd" align="center"><img src="{$akt_smiley['smiley_gfx']}" alt="" /></td>
  <td class="cellalt"><span class="fontsmall">{$akt_smiley['smiley_synonym']}</span></td>
  <td class="cellstd"><span class="fontsmall">{$akt_smiley['smiley_gfx']}</span></td>
  <td class="cellalt" align="center"><span class="fontsmall">{$akt_status}</span></td>
  <td class="cellstd" align="right"><span class="fontsmall"><a href="administration.php?faction=ad_smilies&amp;mode=delete&amp;smiley_id={$akt_smiley['smiley_id']}&amp;{$MYSID}">{$LNG['delete']}</a> | <a href="administration.php?faction=ad_smilies&amp;mode=edit&amp;smiley_id={$akt_smiley['smiley_id']}&amp;{$MYSID}">{$LNG['edit']}</a></span></td>
 </tr>
</template>
<tr><td class="celltitle" colspan="5"><span class="fonttitle">{$LNG['Topic_pics']}</span></td></tr>
<template:tpicrow>
<tr>
 <td class="cellstd" align="center"><img src="{$akt_tpic['smiley_gfx']}" alt="" /></td>
 <td class="cellalt" colspan="3"><span class="fontsmall">{$akt_tpic['smiley_gfx']}</span></td>
 <td class="cellstd" align="right"><span class="fontsmall"><a href="administration.php?faction=ad_smilies&amp;mode=delete&amp;smiley_id={$akt_tpic['smiley_id']}&amp;{$MYSID}">{$LNG['delete']}</a> | <a href="administration.php?faction=ad_smilies&amp;mode=edit&amp;smiley_id={$akt_tpic['smiley_id']}&amp;{$MYSID}">{$LNG['edit']}</a></span></td>
</tr>
</template>
</table>
<br />
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle"><span class="fonttitle">{$LNG['Other_options']}</span></td></tr>
<tr><td class="cellstd"><span class="fontnorm"><a href="administration.php?faction=ad_smilies&amp;mode=add&amp;p_type=0&amp;{$MYSID}">{$LNG['Add_smiley']}</a><br /><a href="administration.php?faction=ad_smilies&amp;mode=add&amp;p_type=1&amp;{$MYSID}">{$LNG['Add_topic_pic']}</a></span></td></tr>
</table>