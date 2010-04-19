<form method="post" action="index.php?faction=editprofile&amp;mode=uploadavatar&amp;doit=1&amp;{$MYSID}" enctype="multipart/form-data">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Upload_avatar']}</span></td></tr>
<template:errorrow>
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr>
 <td class="cellstd" valign="top"><span class="fontnorm">{$LNG['Limitations']}:</span></td>
 <td class="cellalt"><span class="fontnorm">{$LNG['Maximum_file_size']}: {$CONFIG['max_avatar_file_size']} {$LNG['in_kilobytes']}<br />{$LNG['Avatar_width']}: {$CONFIG['avatar_image_width']} {$LNG['in_pixel']}<br />{$LNG['Avatar_height']}: {$CONFIG['avatar_image_height']} {$LNG['in_pixel']}</span></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['File_name']}:</span></td>
 <td class="cellalt"><input class="form_text" size="40" type="file" name="p_avatar_file" /></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Upload_avatar']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
