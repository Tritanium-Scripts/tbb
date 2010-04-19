<form method="post" action="administration.php?faction=ad_forums&amp;mode=addgroupright&amp;forum_id={$forum_id}&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng["Add_group_right"]}</span></th></tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng["Group"]}:</span></td>
 <td class="td1" width="85%"><select class="form_select" name="p_group_id">
 <!-- TPLBLOCK grouprow -->
   <option value="{grouprow.$akt_group["group_id"]}">{grouprow.$akt_group["group_name"]}</option>
 <!-- /TPLBLOCK grouprow -->
 </select></td>
</tr>
<tr>
 <td class="td2" width="15%" valign="top"><span class="norm">{$lng["Rights"]}:</span></td>
 <td class="td2" width="85%"><span class="norm">
  <input type="checkbox" name="p_is_mod" value="1"{$checked["is_mod"]} /> {$lng["Auth_is_moderator"]}<br />
  <input type="checkbox" name="p_view_forum" value="1"{$checked["view_forum"]} /> {$lng["Auth_view_forum"]}<br />
  <input type="checkbox" name="p_post_topic" value="1"{$checked["post_topic"]} /> {$lng["Auth_post_topic"]}<br />
  <input type="checkbox" name="p_post_reply" value="1"{$checked["post_reply"]} /> {$lng["Auth_post_reply"]}<br />
  <input type="checkbox" name="p_post_poll" value="1"{$checked["post_poll"]} /> {$lng["Auth_post_poll"]}<br />
  <input type="checkbox" name="p_edit_posts" value="1"{$checked["edit_posts"]} /> {$lng["Auth_edit_posts"]}
 </span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng["Add_group_right"]}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng["Reset"]}" /></td></tr>
</table>
</form>