<form method="post" action="{$indexFile}?action=EditProfile&amp;mode=Avatar&amp;doit=1&amp;{$mySID}" name="myForm">
<table class="TableStd" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('Avatar')}</span></td></tr>
<tr><td class="CellStd">
 <fieldset>
  <legend><span class="FontSmall"><b>{$modules.Language->getString('Current_avatar')}</b></span></legend>
  <table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
   <td><span class="FontNorm">{$modules.Language->getString('Avatar')}:</span>{if $p.avatarAddress != ''}<br/><img src="{$p.avatarAddress}" alt="{$modules.Language->getString('Current_avatar')}" width="{$modules.Config->getValue('avatar_image_width')}" height="{$modules.Config->getValue('avatar_image_height')}"/>{/if}</td>
   <td valign="top"><input class="FormText" type="text" size="60" name="p[avatarAddress]" value="{$p.avatarAddress}"/></td>
  </tr>
  <tr><td colspan="2"><span class="FontNorm"><a href="{$indexFile}?action=EditProfile&amp;mode=UploadAvatar&amp;{$mySID}{*javascript:popup('{$indexFile}?action=EditProfile&amp;mode=UploadAvatar&amp;{$mySID}','uploadavatarwindow','width=500,height=250,scrollbars=yes,toolbar=no,status=yes')*}">{$modules.Language->getString('Upload_avatar')}</a></span></td></tr>
  </table>
 </fieldset>
 {if $avatarsCounter > 0}
  <fieldset>
   <legend><span class="FontSmall"><b>{$modules.Language->getString('Select_avatar_from_list')}</b></span></legend>
   <table border="0" cellpadding="3" cellspacing="0" width="100%">
    <tr>
    {foreach from=$avatarsData item=curAvatar name=avatarsLoop}
     <td align="center"><a href="javascript:document.getElementsByName('p[avatarAddress]')[0].value = encodeURI('{$curAvatar.avatarAddress}'); document.forms.myForm.submit();"><img src="{$curAvatar.avatarAddress}" width="{$modules.Config->getValue('avatar_image_width')}" height="{$modules.Config->getValue('avatar_image_height')}" alt=""/></a></td>
     {if $smarty.foreach.avatarsLoop.iteration % 8 == 0 && $smarty.foreach.avatarsLoop.iteration != $smarty.foreach.avatarsLoop.total}</tr><tr>{/if}
    {/foreach}
    </tr>
   </table>
  </fieldset>
 {/if}
</td></tr>
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Save_changes')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>
