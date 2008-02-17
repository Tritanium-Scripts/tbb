<form method="post" action="index.php?faction=viewprofile&amp;profile_id={$profile_id}&amp;mode=editnote&amp;note_id={$note_id}&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Edit_note']}</span></td></tr>
<tr>
 <td class="cellstd" valign="top"><span class="fontnorm">{$LNG['Note']}:</span></td>
 <td class="cellstd"><textarea cols="50" rows="8" name="p_note_text">{$p_note_text}</textarea></td>
</tr>
<if:"{$USER_DATA['user_is_admin']} == 1 || {$USER_DATA['user_is_supermod']} == 1 || {$USER_DATA['user_mod_status']} == TRUE">
 <tr>
  <td class="cellstd" valign="top"><span class="fontnorm">{$LNG['Options']}:</span></td>
  <td class="cellstd"><span class="fontnorm"><input type="checkbox" name="p_note_is_public" value="1"<if:"{$p_note_is_public} == 1"> checked="checked"</if> />&nbsp;{$LNG['Post_public_note']}</span></td>
 </tr>
</if>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Edit_note']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
