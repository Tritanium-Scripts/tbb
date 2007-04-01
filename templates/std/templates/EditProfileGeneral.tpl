<form method="post" action="{$indexFile}?action=EditProfile&amp;mode=GeneralProfile&amp;doit=1&amp;{$mySID}">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('General_profile')}</span></td></tr>
{if $error != ''}<tr><td class="CellError"><span class="FontError">{$error}</span></td></tr>{/if}
<tr><td class="CellStd">
 <fieldset>
 <legend><span class="FontSmall"><b>{$modules.Language->getString('General_information')}</b></span></legend>
 <table border="0" cellpadding="2" cellspacing="0" border="0" width="100%">
 <tr>
  <td width="20%"><span class="FontNorm">{$modules.Language->getString('Email_address')}:</span></td>
  <td width="80%"><input class="FormText" type="text" size="40" name="p[userEmail]" value="{$p.userEmail}"/></td>
 </tr>
 <tr>
  <td width="20%" valign="top"><span class="FontNorm">{$modules.Language->getString('Signature')}:</span></td>
  <td width="80%"><textarea class="FormTextArea" cols="60" rows="8" name="p[userSignature]">{$p.userSignature}</textarea></td>
 </tr>
 </table>
 </fieldset>
 <br/>
 <fieldset>
 <legend><span class="FontSmall"><b>{$modules.Language->getString('Change_password')}</b></span></legend>
 <div class="DivInfoBox"><span class="FontInfoBox"><img src="templates/std/templates/images/lightbulb_a.gif" class="ImageIcon"/>{$modules.Language->getString('change_password_info')}</span></div>
 <table border="0" cellpadding="2" cellspacing="0" border="0" width="100%">
 <tr>
  <td width="20%"><span class="FontNorm">{$modules.Language->getString('Current_password')}:</span></td>
  <td width="80%"><input class="FormText" type="password" size="30" name="p[userOldPassword]"/></td>
 </tr>
 <tr>
  <td width="20%"><span class="FontNorm">{$modules.Language->getString('New_password')}:</span></td>
  <td width="80%"><input class="FormText" type="password" size="30" name="p[userNewPassword]"/></td>
 </tr>
 <tr>
  <td width="20%"><span class="FontNorm">{$modules.Language->getString('Confirm_new_password')}:</span></td>
  <td width="80%"><input class="FormText" type="password" size="30" name="p[userNewPasswordConfirmation]"/></td>
 </tr>
 </table>
 </fieldset>
</td></tr>
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Save_changes')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>
