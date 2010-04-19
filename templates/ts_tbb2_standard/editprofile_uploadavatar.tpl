<form method="post" action="index.php?faction=editprofile&amp;mode=uploadavatar&amp;doit=1&amp;{$MYSID}" enctype="multipart/form-data">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Upload_avatar']}</span></td></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{$error}</span></td></tr>
</template>
<tr>
 <td class="td1" valign="top"><span class="norm">{$lng['Limitations']}:</span></td>
 <td class="td2"><span class="norm">{$lng['Maximum_file_size']}: {$CONFIG['max_avatar_file_size']} {$lng['in_kilobytes']}<br />{$lng['Avatar_width']}: {$CONFIG['avatar_image_width']} {$lng['in_pixel']}<br />{$lng['Avatar_height']}: {$CONFIG['avatar_image_height']} {$lng['in_pixel']}</span></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['File_name']}:</span></td>
 <td class="td2"><input class="form_text" size="40" type="file" name="p_avatar_file" /></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Upload_avatar']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>
