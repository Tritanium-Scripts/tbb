<form method="post" action="{$IndexFile}?Action=EditProfile&amp;Mode=Avatar&amp;Doit=1&amp;{$MySID}" name="myForm">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$Modules.Language->getString('Avatar')}</span></td></tr>
<tr><td class="CellStd">
 <fieldset>
  <legend><span class="FontSmall"><b>{$Modules.Language->getString('Current_avatar')}</b></span></legend>
  <table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
   <td><span class="FontNorm">{$Modules.Language->getString('Avatar')}:</span>{if $p.AvatarAddress != ''}<br/><img src="{$p.AvatarAddress}" width="{$Modules.Config->getValue('avatar_image_width')}" height="{$Modules.Config->getValue('avatar_image_height')}"/>{/if}</td>
   <td valign="top"><input class="FormText" type="text" size="60" name="p[AvatarAddress]" value="{$p.AvatarAddress}"/></td>
  </tr>
  <tr><td colspan="2"><span class="FontNorm"><a href="javascript:popup('{$IndexFile}?Action=EditProfile&amp;Mode=UploadAvatar&amp;{$MySID}','uploadavatarwindow','width=500,height=250,scrollbars=yes,toolbar=no,status=yes')">{$Modules.Language->getString('Upload_avatar')}</a></span></td></tr>
  </table>
 </fieldset>
 {if $AvatarsCounter > 0}
  <fieldset>
   <legend><span class="FontSmall"><b>{$Modules.Language->getString('Select_avatar_from_list')}</b></span></legend>
   <table border="0" cellpadding="3" cellspacing="0" width="100%">
    <tr>
    {foreach from=$AvatarsData item=curAvatar name=AvatarsLoop}
     <td align="center"><a href="javascript:document.getElementsByName('p[AvatarAddress]')[0].value = encodeURI('{$curAvatar.AvatarAddress}'); document.forms.myForm.submit();"><img src="{$curAvatar.AvatarAddress}" width="{$Modules.Config->getValue('avatar_image_width')}" height="{$Modules.Config->getValue('avatar_image_height')}" border="0" alt=""/></a></td>
     {if $smarty.foreach.AvatarsLoop.iteration % 8 == 0 && $smarty.foreach.AvatarsLoop.iteration != $smarty.foreach.AvatarsLoop.total}</tr><tr>{/if}
    {/foreach}
    </tr>
   </table>
  </fieldset>
 {/if}
</td></tr>
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$Modules.Language->getString('Save_changes')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$Modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>
