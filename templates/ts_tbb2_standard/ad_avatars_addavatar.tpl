<form method="post" action="administration.php?faction=ad_avatars&amp;mode=addavatar&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Add_avatar']}</span></td></tr>
<if:"{$error} != ''">
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</if>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Avatar_address']}:</span><br /><span class="fontsmall">{$LNG['Path_or_url']}</span></td>
 <td class="cellalt" width="85%"><input class="form_text" type="text" name="p_avatar_address" maxlength="255" size="40" value="{$p_avatar_address}" /></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Add_avatar']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
