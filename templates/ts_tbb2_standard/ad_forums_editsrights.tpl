<form method="post" action="administration.php?faction=ad_forums&amp;mode=editsrights&amp;forum_id={$forum_id}&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="celltitle"><span class="fonttitle">{$LNG['Name']}</span></td>
 <td class="celltitle"><span class="fonttitle">{$LNG['Auth_is_moderator']}</span></td>
 <td class="celltitle"><span class="fonttitle">{$LNG['Auth_view_forum']}</span></td>
 <td class="celltitle"><span class="fonttitle">{$LNG['Auth_post_topic']}</span></td>
 <td class="celltitle"><span class="fonttitle">{$LNG['Auth_post_reply']}</span></td>
 <td class="celltitle"><span class="fonttitle">{$LNG['Auth_post_poll']}</span></td>
 <td class="celltitle"><span class="fonttitle">{$LNG['Auth_edit_posts']}</span></td>
 <td class="celltitle"><span class="fonttitle">&nbsp;</span></td>
</tr>
<tr><td class="cellcat" colspan="8"><span class="fontcat">{$LNG['User_rights']}</span></td></tr>
<template:urightrow>
<tr>
 <td class="{$akt_cell_class}"><span class="fontsmall">{$akt_uright['auth_user_nick']}</span><input type="hidden" name="p_rights[0][{$akt_uright['auth_id']}][auth_id]" value="{$akt_uright['auth_id']}" /></td>
 <td class="{$akt_cell_class}" align="center"><input type="checkbox" name="p_rights[0][{$akt_uright['auth_id']}][auth_is_mod]" value="1"{$akt_checked['auth_is_mod']} /></td>
 <td class="{$akt_cell_class}" align="center"><input type="checkbox" name="p_rights[0][{$akt_uright['auth_id']}][auth_view_forum]" value="1"{$akt_checked['auth_view_forum']} /></td>
 <td class="{$akt_cell_class}" align="center"><input type="checkbox" name="p_rights[0][{$akt_uright['auth_id']}][auth_post_topic]" value="1"{$akt_checked['auth_post_topic']} /></td>
 <td class="{$akt_cell_class}" align="center"><input type="checkbox" name="p_rights[0][{$akt_uright['auth_id']}][auth_post_reply]" value="1"{$akt_checked['auth_post_reply']} /></td>
 <td class="{$akt_cell_class}" align="center"><input type="checkbox" name="p_rights[0][{$akt_uright['auth_id']}][auth_post_poll]" value="1"{$akt_checked['auth_post_poll']} /></td>
 <td class="{$akt_cell_class}" align="center"><input type="checkbox" name="p_rights[0][{$akt_uright['auth_id']}][auth_edit_posts]" value="1"{$akt_checked['auth_edit_posts']} /></td>
 <td class="{$akt_cell_class}" align="center"><span class="fontsmall"><a href="administration.php?faction=ad_forums&amp;mode=deletesright&amp;forum_id={$forum_id}&amp;sright_type=0&amp;sright_id={$akt_uright['auth_id']}&amp;{$MYSID}">{$LNG['delete']}</a></span></td>
</tr>
</template>
<tr><td class="cellcat" colspan="8"><span class="fontcat">{$LNG['Group_rights']}</span></td></tr>
<template:grightrow>
<tr>
 <td class="{$akt_cell_class}"><span class="fontsmall">{$akt_gright['auth_group_name']}</span><input type="hidden" name="p_rights[1][{$akt_gright['auth_id']}][auth_id]" value="{$akt_gright['auth_id']}" /></td>
 <td class="{$akt_cell_class}" align="center"><input type="checkbox" name="p_rights[1][{$akt_gright['auth_id']}][auth_is_mod]" value="1"{$akt_checked['auth_is_mod']} /></td>
 <td class="{$akt_cell_class}" align="center"><input type="checkbox" name="p_rights[1][{$akt_gright['auth_id']}][auth_view_forum]" value="1"{$akt_checked['auth_view_forum']} /></td>
 <td class="{$akt_cell_class}" align="center"><input type="checkbox" name="p_rights[1][{$akt_gright['auth_id']}][auth_post_topic]" value="1"{$akt_checked['auth_post_topic']} /></td>
 <td class="{$akt_cell_class}" align="center"><input type="checkbox" name="p_rights[1][{$akt_gright['auth_id']}][auth_post_reply]" value="1"{$akt_checked['auth_post_reply']} /></td>
 <td class="{$akt_cell_class}" align="center"><input type="checkbox" name="p_rights[1][{$akt_gright['auth_id']}][auth_post_poll]" value="1"{$akt_checked['auth_post_poll']} /></td>
 <td class="{$akt_cell_class}" align="center"><input type="checkbox" name="p_rights[1][{$akt_gright['auth_id']}][auth_edit_posts]" value="1"{$akt_checked['auth_edit_posts']} /></td>
 <td class="{$akt_cell_class}" align="center"><span class="fontsmall"><a href="administration.php?faction=ad_forums&amp;mode=deletesright&amp;forum_id={$forum_id}&amp;sright_type=1&amp;sright_id={$akt_gright['auth_id']}&amp;{$MYSID}">{$LNG['delete']}</a></span></td>
</tr>
</template>
<tr><td colspan="8" class="cellbuttons" align="center"><input type="submit" class="form_bbutton" value="{$LNG['Edit_special_rights']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
<br />
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle"><span class="fonttitle">{$LNG['Other_options']}</span></td></tr>
<tr><td class="cellstd"><span class="fontnorm">
 <a href="administration.php?faction=ad_forums&amp;mode=adduserright&amp;forum_id={$forum_id}&amp;{$MYSID}">{$LNG['Add_user_right']}</a><br />
 <a href="administration.php?faction=ad_forums&amp;mode=addgroupright&amp;forum_id={$forum_id}&amp;{$MYSID}">{$LNG['Add_group_right']}</a>
</span></td></tr>
</table>
</form>