<form method="post" action="administration.php?faction=ad_forums&amp;mode=addgroupright&amp;forum_id={$forum_id}&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Add_group_right']}</span></td></tr>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Group']}:</span></td>
 <td class="cellstd" width="85%"><select class="form_select" name="p_group_id">
 <template:grouprow>
   <option value="{$akt_group['group_id']}">{$akt_group['group_name']}</option>
 </template>
 </select></td>
</tr>
<tr>
 <td class="cellalt" width="15%" valign="top"><span class="fontnorm">{$LNG['Rights']}:</span></td>
 <td class="cellalt" width="85%"><span class="fontnorm">
  <input type="checkbox" name="p_is_mod" value="1"{$checked['is_mod']} /> {$LNG['Auth_is_moderator']}<br />
  <input type="checkbox" name="p_view_forum" value="1"{$checked['view_forum']} /> {$LNG['Auth_view_forum']}<br />
  <input type="checkbox" name="p_post_topic" value="1"{$checked['post_topic']} /> {$LNG['Auth_post_topic']}<br />
  <input type="checkbox" name="p_post_reply" value="1"{$checked['post_reply']} /> {$LNG['Auth_post_reply']}<br />
  <input type="checkbox" name="p_post_poll" value="1"{$checked['post_poll']} /> {$LNG['Auth_post_poll']}<br />
  <input type="checkbox" name="p_edit_posts" value="1"{$checked['edit_posts']} /> {$LNG['Auth_edit_posts']}
 </span></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Add_group_right']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>