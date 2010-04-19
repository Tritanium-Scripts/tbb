<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="5"><span class="fonttitle">{$LNG['Select_avatar_from_list']}</span></td></tr>
<template:avatarrow>
 <tr>
 <template:avatarcol>
  <td class="cellstd" align="center"><a href="index.php?faction=editprofile&amp;mode=selectavatar&amp;doit=1&amp;avatar_address={$akt_encoded_avatar_address}&amp;{$MYSID}"><img src="{$akt_avatar['avatar_address']}" width="{$CONFIG['avatar_image_width']}" height="{$CONFIG['avatar_image_height']}" border="0" alt="" /></a></td>
 </template>
 </tr>
</template>
</table>
