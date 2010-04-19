<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="5"><span class="thnorm">{$lng['Select_avatar_from_list']}</span></th></tr>
<template:avatarrow>
 <tr>
 <template:avatarrow.avatarcol>
  <td class="td1" align="center"><a href="index.php?faction=editprofile&amp;mode=selectavatar&amp;doit=1&amp;avatar_address={avatarrow.avatarcol.$akt_encoded_avatar_address}&amp;{avatarrow.avatarcol.$MYSID}"><img src="{avatarrow.avatarcol.$akt_avatar['avatar_address']}" width="{avatarrow.avatarcol.$CONFIG['avatar_image_width']}" height="{avatarrow.avatarcol.$CONFIG['avatar_image_height']}" border="0" alt="" /></a></td>
 </template:avatarrow.avatarcol>
 </tr>
</template:avatarrow>
</table>
