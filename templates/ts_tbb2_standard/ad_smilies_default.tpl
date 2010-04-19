<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="5"><span class="thnorm">{$lng['Smilies']}</span></th></tr>
<template:smileyrow>
 <tr>
  <td class="{$TCONFIG['cell_classes']['td1_class']}" align="center"><img src="{$akt_smiley['smiley_gfx']}" alt="" /></td>
  <td class="{$TCONFIG['cell_classes']['td2_class']}"><span class="small">{$akt_smiley['smiley_synonym']}</span></td>
  <td class="{$TCONFIG['cell_classes']['td1_class']}"><span class="small">{$akt_smiley['smiley_gfx']}</span></td>
  <td class="{$TCONFIG['cell_classes']['td2_class']}" align="center"><span class="small">{$akt_status}</span></td>
  <td class="{$TCONFIG['cell_classes']['td1_class']}" align="right"><span class="small"><a href="administration.php?faction=ad_smilies&amp;mode=delete&amp;smiley_id={$akt_smiley['smiley_id']}&amp;{$MYSID}">{$lng['delete']}</a> | <a href="administration.php?faction=ad_smilies&amp;mode=edit&amp;smiley_id={$akt_smiley['smiley_id']}&amp;{$MYSID}">{$lng['edit']}</a></span></td>
 </tr>
</template>
<tr><th class="thnorm" colspan="5"><span class="thnorm">{$lng['Topic_pics']}</span></th></tr>
<template:tpicrow>
<tr>
 <td class="{$TCONFIG['cell_classes']['td1_class']}" align="center"><img src="{$akt_tpic['smiley_gfx']}" alt="" /></td>
 <td class="{$TCONFIG['cell_classes']['td2_class']}" colspan="3"><span class="small">{$akt_tpic['smiley_gfx']}</span></td>
 <td class="{$TCONFIG['cell_classes']['td1_class']}" align="right"><span class="small"><a href="administration.php?faction=ad_smilies&amp;mode=delete&amp;smiley_id={$akt_tpic['smiley_id']}&amp;{$MYSID}">{$lng['delete']}</a> | <a href="administration.php?faction=ad_smilies&amp;mode=edit&amp;smiley_id={$akt_tpic['smiley_id']}&amp;{$MYSID}">{$lng['edit']}</a></span></td>
</tr>
</template>
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{$lng['Other_options']}</span></th></tr>
<tr><td class="td1"><span class="norm"><a href="administration.php?faction=ad_smilies&amp;mode=add&amp;p_type=0&amp;{$MYSID}">{$lng['Add_smiley']}</a><br /><a href="administration.php?faction=ad_smilies&amp;mode=add&amp;p_type=1&amp;{$MYSID}">{$lng['Add_topic_pic']}</a></span></td></tr>
</table>