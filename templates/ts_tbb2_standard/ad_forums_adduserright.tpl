<form method="post" action="administration.php?faction=ad_forums&amp;mode=adduserright&amp;forum_id={$forum_id}&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Add_user_right']}</span></td></tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Users']}:</span></td>
 <td class="cellstd"><input type="text" class="form_text" name="p_users" value="{$p_users}" size="30" /></td>
</tr>
<tr>
 <td class="cellalt" valign="top"><span class="fontnorm">{$LNG['Rights']}:</span></td>
 <td class="cellalt"><span class="fontnorm">
  <input type="checkbox" name="p_is_mod" value="1"{$checked['is_mod']} /> {$LNG['Auth_is_moderator']}<br />
  <input type="checkbox" name="p_view_forum" value="1"{$checked['view_forum']} /> {$LNG['Auth_view_forum']}<br />
  <input type="checkbox" name="p_post_topic" value="1"{$checked['post_topic']} /> {$LNG['Auth_post_topic']}<br />
  <input type="checkbox" name="p_post_reply" value="1"{$checked['post_reply']} /> {$LNG['Auth_post_reply']}<br />
  <input type="checkbox" name="p_post_poll" value="1"{$checked['post_poll']} /> {$LNG['Auth_post_poll']}<br />
  <input type="checkbox" name="p_edit_posts" value="1"{$checked['edit_posts']} /> {$LNG['Auth_edit_posts']}
 </span></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input type="submit" class="form_bbutton" value="{$LNG['Add_user_right']}" />&nbsp;&nbsp;&nbsp;<input type="reset" class="form_button" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>