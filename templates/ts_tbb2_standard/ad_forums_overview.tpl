<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="3"><span class="fonttitle">{$LNG['Manage_forums']}</span></td></tr>
<template:catrow>
 <tr>
  <td class="cellcat"><span class="fontcat">{$akt_prefix} {$akt_cat['cat_name']}</span></td>
  <td class="cellcat"><span class="fontcatsmall">{$akt_prefix} {$akt_cat_up} | {$akt_cat_down}</span></td>
  <td class="cellcat" align="right"><span class="fontcatsmall"><a href="administration.php?faction=ad_forums&amp;mode=editcat&amp;cat_id={$akt_cat['cat_id']}&amp;{$MYSID}">{$LNG['Edit']}</a> | <a href="administration.php?faction=ad_forums&amp;mode=addcat&amp;parent_id={$akt_cat['cat_id']}&amp;{$MYSID}">{$LNG['Add_sub_category']}</a> | <a href="administration.php?faction=ad_forums&amp;mode=addforum&amp;cat_id={$akt_cat['cat_id']}&amp;{$MYSID}">{$LNG['Add_forum']}</a></span></td>
 </tr>
 <template:forumrow>
 <tr>
  <td class="{$akt_cell_class}"><span class="fontnorm">--{$akt_prefix} {$akt_forum['forum_name']}</span></td>
  <td class="{$akt_cell_class}"><span class="fontsmall">--{$akt_prefix} {$akt_forum_up} | {$akt_forum_down}</span></td>
  <td class="{$akt_cell_class}" align="right"><span class="fontsmall"><a href="administration.php?faction=ad_forums&amp;mode=editsrights&amp;forum_id={$akt_forum['forum_id']}&amp;{$MYSID}">{$LNG['Edit_special_rights']}</a> | <a href="administration.php?faction=ad_forums&amp;mode=editforum&amp;forum_id={$akt_forum['forum_id']}&amp;{$MYSID}">{$LNG['Edit']}</a></span></td>
 </tr>
 </template>
</template>
</table>
<br />
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Forums_without_category']}</span></td></tr>
 <template:forumrow>
 <tr>
  <td class="{$akt_cell_class}"><span class="fontnorm">{$akt_forum['forum_name']}</span></td>
  <td class="{$akt_cell_class}" align="right"><span class="fontsmall"><a href="administration.php?faction=ad_forums&amp;mode=editsrights&amp;forum_id={$akt_forum['forum_id']}&amp;{$MYSID}">{$LNG['Edit_special_rights']}</a> | <span class="fontsmall"><a href="administration.php?faction=ad_forums&amp;mode=editforum&amp;forum_id={$akt_forum['forum_id']}&amp;{$MYSID}">{$LNG['Edit']}</a></span></td>
 </tr>
 </template>
</table>
<br />
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle"><span class="fonttitle">{$LNG['Other_options']}</span></td></tr>
<tr><td class="cellstd"><span class="fontnorm"><a href="administration.php?faction=ad_forums&amp;mode=addcat&amp;parent_id=0&amp;{$MYSID}">{$LNG['Add_category']}</a><br /><a href="administration.php?faction=ad_forums&amp;mode=addforum&amp;cat_id=0&amp;{$MYSID}">{$LNG['Add_forum']}</a></span></td></tr>
</table>