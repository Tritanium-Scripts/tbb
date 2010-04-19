<form method="post" action="administration.php?faction=ad_forums&amp;mode=addforum&amp;doit=1&amp;{MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{LNG_ADD_FORUM}</span></th></tr>
<!-- TPLBLOCK errorrow -->
 <tr><td class="error" colspan="2"><span class="error">{errorrow.ERROR}</span></td></tr>
<!-- /TPLBLOCK errorrow -->
<tr><td class="cat" colspan="2"><span class="cat">{LNG_GENERAL_INFORMATION}</span></td></tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{LNG_NAME}:</span></td>
 <td class="td1" width="85%"><input class="form_text" type="text" size="35" name="p_forum_name" value="{P_FORUM_NAME}" /></td>
</tr>
<tr>
 <td class="td2" width="15%"><span class="norm">{LNG_DESCRIPTION}:</span></td>
 <td class="td2" width="85%"><input class="form_text" type="text" size="45" name="p_forum_description" value="{P_FORUM_DESCRIPTION}" /></td>
</tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{LNG_CATEGORY}:</span></td>
 <td class="td1" width="85%"><select class="form_select" name="p_cat_id">
 <!-- TPLBLOCK optionrow -->
  <option value="{optionrow.VALUE}"{optionrow.SELECTED}>{optionrow.TEXT}</option>
 <!-- /TPLBLOCK optionrow -->
 </select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{LNG_GENERAL_RIGHTS}</span></td></tr>
<tr><td colspan="2" class="td1"><span class="norm">
 <input type="checkbox" name="p_members_view_forum" value="1"{C_MEMBERS_VIEW_FORUM} /> {LNG_MEMBERS_VIEW_FORUM}<br />
 <input type="checkbox" name="p_members_post_topic" value="1"{C_MEMBERS_POST_TOPIC} /> {LNG_MEMBERS_POST_TOPIC}<br />
 <input type="checkbox" name="p_members_post_reply" value="1"{C_MEMBERS_POST_REPLY} /> {LNG_MEMBERS_POST_REPLY}<br />
 <input type="checkbox" name="p_members_post_poll" value="1"{C_MEMBERS_POST_POLL} /> {LNG_MEMBERS_POST_POLL}<br />
 <input type="checkbox" name="p_members_edit_posts" value="1"{C_MEMBERS_EDIT_POSTS} /> {LNG_MEMBERS_EDIT_POSTS}<br />
 <input type="checkbox" name="p_guests_view_forum" value="1"{C_GUESTS_VIEW_FORUM} /> {LNG_GUESTS_VIEW_FORUM}<br />
 <input type="checkbox" name="p_guests_post_topic" value="1"{C_GUESTS_POST_TOPIC} /> {LNG_GUESTS_POST_TOPIC}<br />
 <input type="checkbox" name="p_guests_post_reply" value="1"{C_GUESTS_POST_REPLY} /> {LNG_GUESTS_POST_REPLY}<br />
 <input type="checkbox" name="p_guests_post_poll" value="1"{C_GUESTS_POST_POLL} /> {LNG_GUESTS_POST_POLL}
</span></td></tr>
<tr><td class="cat" colspan="2"><span class="cat">{LNG_OTHER_OPTIONS}</span></td></tr>
<tr><td colspan="2" class="td1"><span class="norm">
 <input type="checkbox" value="1" name="p_forum_is_moderated"{C_MODERATED}/> {LNG_MODERATE_FORUM}<br />
 <input type="checkbox" value="1" name="p_forum_enable_bbcode"{C_BBCODE}/> {LNG_ENABLE_BBCODE}<br />
 <input type="checkbox" value="1" name="p_forum_enable_smilies"{C_SMILIES}/> {LNG_ENABLE_SMILIES}<br />
 <input type="checkbox" value="1" name="p_forum_enable_htmlcode"{C_HTMLCODE}/> {LNG_ENABLE_HTMLCODE}<br />
</span></td></tr>
<tr><td colspan="2" class="buttonrow" align="center"><input type="submit" class="form_bbutton" value="{LNG_ADD_FORUM}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{LNG_RESET}" /></td></tr>
</table>
</form>