<form method="post" action="administration.php?faction=ad_forums&amp;mode=editsrights&amp;forum_id={FORUM_ID}&amp;doit=1&amp;{MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <th class="thsmall"><span class="thsmall">{LNG_NAME}</span></th>
 <th class="thsmall"><span class="thsmall">{LNG_AUTH_IS_MODERATOR}</span></th>
 <th class="thsmall"><span class="thsmall">{LNG_AUTH_VIEW_FORUM}</span></th>
 <th class="thsmall"><span class="thsmall">{LNG_AUTH_POST_TOPIC}</span></th>
 <th class="thsmall"><span class="thsmall">{LNG_AUTH_POST_REPLY}</span></th>
 <th class="thsmall"><span class="thsmall">{LNG_AUTH_POST_POLL}</span></th>
 <th class="thsmall"><span class="thsmall">{LNG_AUTH_EDIT_POSTS}</span></th>
</tr>
<tr><td class="cat" colspan="7"><span class="cat">{LNG_USER_RIGHTS}</span></td></tr>
<tr><td class="cat" colspan="7"><span class="cat">{LNG_GROUP_RIGHTS}</span></td></tr>
<tr><td colspan="7" class="buttonrow" align="center"><input type="submit" class="form_bbutton" value="{LNG_EDIT_SPECIAL_RIGHTS}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{LNG_RESET}" /></td></tr>
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{LNG_OTHER_OPTIONS}</span></th></tr>
<tr><td class="td1"><span class="norm">
 <a href="administration.php?faction=ad_forums&amp;mode=adduserright&amp;forum_id={FORUM_ID}&amp;{MYSID}">{LNG_ADD_USER_RIGHT}</a><br />
 <a href="administration.php?faction=ad_forums&amp;mode=addgroupright&amp;forum_id={FORUM_ID}&amp;{MYSID}">{LNG_ADD_GROUP_RIGHT}</a>
</span></td></tr>
</table>
</form>