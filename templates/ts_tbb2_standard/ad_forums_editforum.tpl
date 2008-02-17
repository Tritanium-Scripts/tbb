<form method="post" action="administration.php?faction=ad_forums&amp;mode=editforum&amp;forum_id={$forum_id}&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Edit_forum']}</span></td></tr>
<template:errorrow>
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['General_information']}</span></td></tr>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Name']}:</span></td>
 <td class="cellstd" width="85%"><input class="form_text" type="text" size="35" name="p_forum_name" value="{$p_forum_name}" /></td>
</tr>
<tr>
 <td class="cellalt" width="15%"><span class="fontnorm">{$LNG['Description']}:</span></td>
 <td class="cellalt" width="85%"><input class="form_text" type="text" size="45" name="p_forum_description" value="{$p_forum_description}" /></td>
</tr>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Category']}:</span></td>
 <td class="cellstd" width="85%"><select class="form_select" name="p_cat_id">
 <template:optionrow>
  <option value="{$akt_cat['cat_id']}"{$akt_selected}>{$akt_prefix} {$akt_cat['cat_name']}</option>
 </template>
 </select></td>
</tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['General_rights']}</span></td></tr>
<tr><td colspan="2" class="cellstd"><span class="fontnorm">
 <input type="checkbox" name="p_members_view_forum" value="1"{$checked['members_view_forum']} /> {$LNG['Members_view_forum']}<br />
 <input type="checkbox" name="p_members_post_topic" value="1"{$checked['members_post_topic']} /> {$LNG['Members_post_topic']}<br />
 <input type="checkbox" name="p_members_post_reply" value="1"{$checked['members_post_reply']} /> {$LNG['Members_post_reply']}<br />
 <input type="checkbox" name="p_members_post_poll" value="1"{$checked['members_post_poll']} /> {$LNG['Members_post_poll']}<br />
 <input type="checkbox" name="p_members_edit_posts" value="1"{$checked['members_edit_posts']} /> {$LNG['Members_edit_posts']}<br />
 <input type="checkbox" name="p_guests_view_forum" value="1"{$checked['guests_view_forum']} /> {$LNG['Guests_view_forum']}<br />
 <input type="checkbox" name="p_guests_post_topic" value="1"{$checked['guests_post_topic']} /> {$LNG['Guests_post_topic']}<br />
 <input type="checkbox" name="p_guests_post_reply" value="1"{$checked['guests_post_reply']} /> {$LNG['Guests_post_reply']}<br />
 <input type="checkbox" name="p_guests_post_poll" value="1"{$checked['guests_post_poll']} /> {$LNG['Guests_post_poll']}<br />
 <a href="administration.php?faction=ad_forums&amp;mode=editsrights&amp;forum_id={$forum_id}&amp;{$MYSID}">{$LNG['Edit_special_rights']}</a>
</span></td></tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Other_options']}</span></td></tr>
<tr><td colspan="2" class="cellstd"><span class="fontnorm">
 <input type="checkbox" value="1" name="p_forum_show_latest_posts"{$checked['latestposts']} /> {$LNG['Show_latest_posts']}<br />
 <input type="checkbox" value="1" name="p_forum_is_moderated"{$checked['moderated']} /> {$LNG['Moderate_forum']}<br />
 <input type="checkbox" value="1" name="p_forum_enable_bbcode"{$checked['bbcode']} /> {$LNG['Enable_bbcode']}<br />
 <input type="checkbox" value="1" name="p_forum_enable_smilies"{$checked['smilies']} /> {$LNG['Enable_smilies']}<br />
 <input type="checkbox" value="1" name="p_forum_enable_htmlcode"{$checked['htmlcode']} /> {$LNG['Enable_html_code']}<br />
</span></td></tr>
<tr><td colspan="2" class="cellbuttons" align="center"><input type="submit" class="form_bbutton" value="{$LNG['Edit_forum']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>