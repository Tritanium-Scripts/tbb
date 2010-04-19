<table class="tbl" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Manage_avatars']}</span></th></tr>
<template:avatarrow>
<tr>
 <td class="td1"><img src="{avatarrow.$akt_avatar['avatar_address']}" alt="" border="0" /></td>
 <td class="td2" align="right"><span class="small"><a href="administration.php?faction=ad_avatars&amp;mode=deleteavatar&amp;avatar_id={avatarrow.$akt_avatar['avatar_id']}&amp;{avatarrow.$MYSID}">{avatarrow.$lng['delete']}</a> | <a href="administration.php?faction=ad_avatars&amp;mode=editavatar&amp;avatar_id={avatarrow.$akt_avatar['avatar_id']}&amp;{avatarrow.$MYSID}">{avatarrow.$lng['edit']}</a></span></td>
</tr>
</template:avatarrow>
</table>
<br />
<table class="tbl" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr><th class="thnorm"><span class="thnorm">{$lng['Other_options']}</span></th></tr>
<tr><td class="td1"><span class="norm"><a href="administration.php?faction=ad_avatars&amp;mode=addavatar&amp;{$MYSID}">{$lng['Add_avatar']}</a></td></tr>
</table>
