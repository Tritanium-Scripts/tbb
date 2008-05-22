<form method="post" action="{$indexFile}?action=EditProfile&amp;mode=UploadAvatar&amp;doit=1&amp;{$mySID}" enctype="multipart/form-data">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Upload_avatar')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Limitations')}:</span></td>
 <td class="CellAlt">
  <table border="0" cellpadding="1" cellspacing="0">
   <tr>
    <td><span class="FontNorm">{$modules.Language->getString('Maximum_file_size')}:</span></td>
    <td><span class="FontNorm">{$modules.Config->getValue('max_avatar_file_size')} {$modules.Language->getString('in_kilobytes')}</span></td>
   </tr>
   <tr>
    <td><span class="FontNorm">{$modules.Language->getString('Avatar_width')}:</span></td>
    <td><span class="FontNorm">{$modules.Config->getValue('avatar_image_width')} {$modules.Language->getString('in_pixel')}</span></td>
   </tr>
   <tr>
    <td><span class="FontNorm">{$modules.Language->getString('Avatar_height')}:</span></td>
    <td><span class="FontNorm">{$modules.Config->getValue('avatar_image_height')} {$modules.Language->getString('in_pixel')}</span></td>
   </tr>
  </table>
 </td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('File_name')}:</span></td>
 <td class="CellAlt"><input class="FormText" size="40" type="file" name="avatarFile"/></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Upload_avatar')}"/></td></tr>
</table>
</form>