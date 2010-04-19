<table class="tablestd" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Manage_avatars']}</span></td></tr>
<template:avatarrow>
<tr>
 <td class="cellstd"><img src="{$akt_avatar['avatar_address']}" alt="" border="0" /></td>
 <td class="cellalt" align="right"><span class="fontsmall"><a href="administration.php?faction=ad_avatars&amp;mode=deleteavatar&amp;avatar_id={$akt_avatar['avatar_id']}&amp;{$MYSID}">{$LNG['delete']}</a> | <a href="administration.php?faction=ad_avatars&amp;mode=editavatar&amp;avatar_id={$akt_avatar['avatar_id']}&amp;{$MYSID}">{$LNG['edit']}</a></span></td>
</tr>
</template>
</table>
<br />
<table class="tablestd" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr><td class="celltitle"><span class="fonttitle">{$LNG['Other_options']}</span></td></tr>
<tr><td class="cellstd"><span class="fontnorm"><a href="administration.php?faction=ad_avatars&amp;mode=addavatar&amp;{$MYSID}">{$LNG['Add_avatar']}</a></td></tr>
</table>
