<form method="post" action="{$IndexFile}?Action=EditProfile&amp;Mode=GeneralProfile&amp;Doit=1&amp;{$MySID}">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$Modules.Language->getString('General_profile')}</span></td></tr>
{if $Error != ''}<tr><td class="CellError"><span class="FontError">{$Error}</span></td></tr>{/if}
<tr><td class="CellStd">
 <fieldset>
 <legend><span class="FontSmall"><b>{$Modules.Language->getString('General_information')}</b></span></legend>
 <table border="0" cellpadding="2" cellspacing="0" border="0" width="100%">
 <tr>
  <td width="20%"><span class="FontNorm">{$Modules.Language->getString('Email_address')}:</span></td>
  <td width="80%"><input class="FormText" type="text" size="40" name="p[UserEmail]" value="{$p.UserEmail}"/></td>
 </tr>
 <tr>
  <td width="20%" valign="top"><span class="FontNorm">{$Modules.Language->getString('Signature')}:</span></td>
  <td width="80%"><textarea class="FormTextArea" cols="60" rows="8" name="p[UserSignature]">{$p.UserSignature}</textarea></td>
 </tr>
 </table>
 </fieldset>
 <br/>
 <fieldset>
 <legend><span class="FontSmall"><b>{$Modules.Language->getString('Change_password')}</b></span></legend>
 <div class="DivInfoBox"><span class="FontInfoBox"><img src="templates/std/templates/images/lightbulb_a.gif" class="ImageIcon"/>{$Modules.Language->getString('change_password_info')}</span></div>
 <table border="0" cellpadding="2" cellspacing="0" border="0" width="100%">
 <tr>
  <td width="20%"><span class="FontNorm">{$Modules.Language->getString('Current_password')}:</span></td>
  <td width="80%"><input class="FormText" type="password" size="30" name="p[UserOldPassword]"/></td>
 </tr>
 <tr>
  <td width="20%"><span class="FontNorm">{$Modules.Language->getString('New_password')}:</span></td>
  <td width="80%"><input class="FormText" type="password" size="30" name="p[UserNewPassword]"/></td>
 </tr>
 <tr>
  <td width="20%"><span class="FontNorm">{$Modules.Language->getString('Confirm_new_password')}:</span></td>
  <td width="80%"><input class="FormText" type="password" size="30" name="p[UserNewPasswordConfirmation]"/></td>
 </tr>
 </table>
 </fieldset>
</td></tr>
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$Modules.Language->getString('Save_changes')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$Modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>
