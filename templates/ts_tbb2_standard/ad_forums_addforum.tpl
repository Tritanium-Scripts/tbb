<form method="post" action="administration.php?faction=ad_forums&amp;mode=addforum&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Add_forum']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{$error}</span></td></tr>
</template>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['General_information']}</span></td></tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Name']}:</span></td>
 <td class="td1" width="85%"><input class="form_text" type="text" size="35" name="p_forum_name" value="{$p_forum_name}" /></td>
</tr>
<tr>
 <td class="td2" width="15%"><span class="norm">{$lng['Description']}:</span></td>
 <td class="td2" width="85%"><input class="form_text" type="text" size="45" name="p_forum_description" value="{$p_forum_description}" /></td>
</tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Category']}:</span></td>
 <td class="td1" width="85%"><select class="form_select" name="p_cat_id">
 <template:optionrow>
  <option value="{$akt_cat['cat_id']}"{$akt_selected}>{$akt_prefix} {$akt_cat['cat_name']}</option>
 </template>
 </select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['General_rights']}</span></td></tr>
<tr><td colspan="2" class="td1"><span class="norm">
 <input type="checkbox" name="p_members_view_forum" value="1"{$checked['members_view_forum']} /> {$lng['Members_view_forum']}<br />
 <input type="checkbox" name="p_members_post_topic" value="1"{$checked['members_post_topic']} /> {$lng['Members_post_topic']}<br />
 <input type="checkbox" name="p_members_post_reply" value="1"{$checked['members_post_reply']} /> {$lng['Members_post_reply']}<br />
 <input type="checkbox" name="p_members_post_poll" value="1"{$checked['members_post_poll']} /> {$lng['Members_post_poll']}<br />
 <input type="checkbox" name="p_members_edit_posts" value="1"{$checked['members_edit_posts']} /> {$lng['Members_edit_posts']}<br />
 <input type="checkbox" name="p_guests_view_forum" value="1"{$checked['guests_view_forum']} /> {$lng['Guests_view_forum']}<br />
 <input type="checkbox" name="p_guests_post_topic" value="1"{$checked['guests_post_topic']} /> {$lng['Guests_post_topic']}<br />
 <input type="checkbox" name="p_guests_post_reply" value="1"{$checked['guests_post_reply']} /> {$lng['Guests_post_reply']}<br />
 <input type="checkbox" name="p_guests_post_poll" value="1"{$checked['guests_post_poll']} /> {$lng['Guests_post_poll']}
</span></td></tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Other_options']}</span></td></tr>
<tr><td colspan="2" class="td1"><span class="norm">
 <input type="checkbox" value="1" name="p_forum_show_latest_posts"{$checked['latestposts']} /> {$lng['Show_latest_posts']}<br />
 <input type="checkbox" value="1" name="p_forum_is_moderated"{$checked['moderated']}/> {$lng['Moderate_forum']}<br />
 <input type="checkbox" value="1" name="p_forum_enable_bbcode"{$checked['bbcode']}/> {$lng['Enable_bbcode']}<br />
 <input type="checkbox" value="1" name="p_forum_enable_smilies"{$checked['smilies']}/> {$lng['Enable_smilies']}<br />
 <input type="checkbox" value="1" name="p_forum_enable_htmlcode"{$checked['htmlcode']}/> {$lng['Enable_html_code']}<br />
</span></td></tr>
<tr><td colspan="2" class="buttonrow" align="center"><input type="submit" class="form_bbutton" value="{$lng['Add_forum']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>