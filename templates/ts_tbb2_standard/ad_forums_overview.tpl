<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="3"><span class="thnorm">{$lng['Manage_forums']}</span></th></tr>
<template:catrow>
 <tr>
  <td class="adcat"><span class="norm">{$akt_prefix} {$akt_cat['cat_name']}</span></td>
  <td class="adcat"><span class="small">{$akt_prefix} {$akt_cat_up} | {$akt_cat_down}</span></td>
  <td class="adcat" align="right"><span class="small"><a href="administration.php?faction=ad_forums&amp;mode=editcat&amp;cat_id={$akt_cat['cat_id']}&amp;{$MYSID}">{$lng['Edit']}</a> | <a href="administration.php?faction=ad_forums&amp;mode=addcat&amp;parent_id={$akt_cat['cat_id']}&amp;{$MYSID}">{$lng['Add_sub_category']}</a> | <a href="administration.php?faction=ad_forums&amp;mode=addforum&amp;cat_id={$akt_cat['cat_id']}&amp;{$MYSID}">{$lng['Add_forum']}</a></span></td>
 </tr>
 <template:forumrow>
 <tr>
  <td class="{$akt_cell_class}"><span class="norm">--{$akt_prefix} {$akt_forum['forum_name']}</span></td>
  <td class="{$akt_cell_class}"><span class="small">--{$akt_prefix} {$akt_forum_up} | {$akt_forum_down}</span></td>
  <td class="{$akt_cell_class}" align="right"><span class="small"><a href="administration.php?faction=ad_forums&amp;mode=editsrights&amp;forum_id={$akt_forum['forum_id']}&amp;{$MYSID}">{$lng['Edit_special_rights']}</a> | <a href="administration.php?faction=ad_forums&amp;mode=editforum&amp;forum_id={$akt_forum['forum_id']}&amp;{$MYSID}">{$lng['Edit']}</a></span></td>
 </tr>
 </template>
</template>
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Forums_without_category']}</span></th></tr>
 <template:forumrow>
 <tr>
  <td class="{$akt_cell_class}"><span class="norm">{$akt_forum['forum_name']}</span></td>
  <td class="{$akt_cell_class}" align="right"><span class="small"><a href="administration.php?faction=ad_forums&amp;mode=editsrights&amp;forum_id={$akt_forum['forum_id']}&amp;{$MYSID}">{$lng['Edit_special_rights']}</a> | <span class="small"><a href="administration.php?faction=ad_forums&amp;mode=editforum&amp;forum_id={$akt_forum['forum_id']}&amp;{$MYSID}">{$lng['Edit']}</a></span></td>
 </tr>
 </template>
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{$lng['Other_options']}</span></th></tr>
<tr><td class="td1"><span class="norm"><a href="administration.php?faction=ad_forums&amp;mode=addcat&amp;parent_id=0&amp;{$MYSID}">{$lng['Add_category']}</a><br /><a href="administration.php?faction=ad_forums&amp;mode=addforum&amp;cat_id=0&amp;{$MYSID}">{$lng['Add_forum']}</a></span></td></tr>
</table>