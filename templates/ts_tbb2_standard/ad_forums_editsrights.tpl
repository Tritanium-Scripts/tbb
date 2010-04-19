<form method="post" action="administration.php?faction=ad_forums&amp;mode=editsrights&amp;forum_id={$forum_id}&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <th class="thsmall"><span class="thsmall">{$lng["Name"]}</span></th>
 <th class="thsmall"><span class="thsmall">{$lng["Auth_is_moderator"]}</span></th>
 <th class="thsmall"><span class="thsmall">{$lng["Auth_view_forum"]}</span></th>
 <th class="thsmall"><span class="thsmall">{$lng["Auth_post_topic"]}</span></th>
 <th class="thsmall"><span class="thsmall">{$lng["Auth_post_reply"]}</span></th>
 <th class="thsmall"><span class="thsmall">{$lng["Auth_post_poll"]}</span></th>
 <th class="thsmall"><span class="thsmall">{$lng["Auth_edit_posts"]}</span></th>
</tr>
<tr><td class="cat" colspan="7"><span class="cat">{$lng["User_rights"]}</span></td></tr>
<!-- TPLBLOCK urightrow -->
<tr>
 <td class="{urightrow.$tpl_config["akt_class"]}"><span class="norm">{urightrow.$akt_uright["auth_user_nick"]}</span><input type="hidden" name="p_rights[0][{urightrow.$akt_uright["auth_id"]}][auth_id]" value="{urightrow.$akt_uright["auth_id"]}" /></td>
 <td class="{urightrow.$tpl_config["akt_class"]}" align="center"><input type="checkbox" name="p_rights[0][{urightrow.$akt_uright["auth_id"]}][auth_is_mod]" value="1"{urightrow.$akt_checked["auth_is_mod"]} /></td>
 <td class="{urightrow.$tpl_config["akt_class"]}" align="center"><input type="checkbox" name="p_rights[0][{urightrow.$akt_uright["auth_id"]}][auth_view_forum]" value="1"{urightrow.$akt_checked["auth_view_forum"]} /></td>
 <td class="{urightrow.$tpl_config["akt_class"]}" align="center"><input type="checkbox" name="p_rights[0][{urightrow.$akt_uright["auth_id"]}][auth_post_topic]" value="1"{urightrow.$akt_checked["auth_post_topic"]} /></td>
 <td class="{urightrow.$tpl_config["akt_class"]}" align="center"><input type="checkbox" name="p_rights[0][{urightrow.$akt_uright["auth_id"]}][auth_post_reply]" value="1"{urightrow.$akt_checked["auth_post_reply"]} /></td>
 <td class="{urightrow.$tpl_config["akt_class"]}" align="center"><input type="checkbox" name="p_rights[0][{urightrow.$akt_uright["auth_id"]}][auth_post_poll]" value="1"{urightrow.$akt_checked["auth_post_poll"]} /></td>
 <td class="{urightrow.$tpl_config["akt_class"]}" align="center"><input type="checkbox" name="p_rights[0][{urightrow.$akt_uright["auth_id"]}][auth_edit_posts]" value="1"{urightrow.$akt_checked["auth_edit_posts"]} /></td>
</tr>
<!-- /TPLBLOCK urightrow -->
<tr><td class="cat" colspan="7"><span class="cat">{$lng["Group_rights"]}</span></td></tr>
<tr><td colspan="7" class="buttonrow" align="center"><input type="submit" class="form_bbutton" value="{$lng["Edit_special_rights"]}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng["Reset"]}" /></td></tr>
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{$lng["Other_options"]}</span></th></tr>
<tr><td class="td1"><span class="norm">
 <a href="administration.php?faction=ad_forums&amp;mode=adduserright&amp;forum_id={$forum_id}&amp;{$MYSID}">{$lng["Add_user_right"]}</a><br />
 <a href="administration.php?faction=ad_forums&amp;mode=addgroupright&amp;forum_id={$forum_id}&amp;{$MYSID}">{$lng["Add_group_right"]}</a>
</span></td></tr>
</table>
</form>