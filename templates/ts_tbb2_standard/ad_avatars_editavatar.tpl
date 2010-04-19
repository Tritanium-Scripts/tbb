<form method="post" action="administration.php?faction=ad_avatars&amp;mode=editavatar&amp;avatar_id={$avatar_id}&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellspacing="0" cellpediting="3" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Edit_avatar']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{errorrow.$error}</span></td></tr>
</template:errorrow>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Avatar_address']}:</span><br /><span class="small">{$lng['Path_or_url']}</span></td>
 <td class="td1" width="85%"><input class="form_text" type="text" name="p_avatar_address" maxlength="255" size="40" value="{$p_avatar_address}" /></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Edit_avatar']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>
