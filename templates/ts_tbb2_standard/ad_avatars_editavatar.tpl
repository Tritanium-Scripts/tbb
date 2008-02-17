<form method="post" action="administration.php?faction=ad_avatars&amp;mode=editavatar&amp;avatar_id={$avatar_id}&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellspacing="0" cellpediting="3" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Edit_avatar']}</span></td></tr>
<template:errorrow>
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Avatar_address']}:</span><br /><span class="fontsmall">{$LNG['Path_or_url']}</span></td>
 <td class="cellstd" width="85%"><input class="form_text" type="text" name="p_avatar_address" maxlength="255" size="40" value="{$p_avatar_address}" /></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Edit_avatar']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
