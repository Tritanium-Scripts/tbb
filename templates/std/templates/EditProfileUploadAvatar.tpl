<form method="post" action="{$IndexFile}?Action=EditProfile&amp;Mode=UploadAvatar&amp;Doit=1&amp;{$MySID}" enctype="multipart/form-data">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$Modules.Language->getString('Upload_avatar')}</span></td></tr>
{if $Error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$Error}</span></td></tr>{/if}
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$Modules.Language->getString('Limitations')}:</span></td>
 <td class="CellAlt">
  <table border="0" cellpadding="1" cellspacing="0">
   <tr>
    <td><span class="FontNorm">{$Modules.Language->getString('Maximum_file_size')}:</span></td>
    <td><span class="FontNorm">{$Modules.Config->getValue('max_avatar_file_size')} {$Modules.Language->getString('in_kilobytes')}</span></td>
   </tr>
   <tr>
    <td><span class="FontNorm">{$Modules.Language->getString('Avatar_width')}:</span></td>
    <td><span class="FontNorm">{$Modules.Config->getValue('avatar_image_width')} {$Modules.Language->getString('in_pixel')}</span></td>
   </tr>
   <tr>
    <td><span class="FontNorm">{$Modules.Language->getString('Avatar_height')}:</span></td>
    <td><span class="FontNorm">{$Modules.Config->getValue('avatar_image_height')} {$Modules.Language->getString('in_pixel')}</span></td>
   </tr>
  </table>
 </td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$Modules.Language->getString('File_name')}:</span></td>
 <td class="CellAlt"><input class="FormText" size="40" type="file" name="AvatarFile"/></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$Modules.Language->getString('Upload_avatar')}"/></td></tr>
</table>
</form>
