<form method="post" action="{$smarty.const.INDEXFILE}?action=EditProfile&amp;mode=GeneralProfile&amp;doit=1&amp;{$smarty.const.MYSID}">
<table class="TableStd" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('general_profile')}</span></td></tr>
{if $error != ''}<tr><td class="CellError"><span class="FontError">{$error}</span></td></tr>{/if}
<tr><td class="CellStd">
 <fieldset>
 <legend><span class="FontSmall"><b>{$modules.Language->getString('general_information')}</b></span></legend>
 <table border="0" cellpadding="2" cellspacing="0" width="100%">
 <tr>
  <td width="20%" style="padding:3px;"><span class="FontNorm">{$modules.Language->getString('email_address')}:</span></td>
  <td width="80%" style="padding:3px;"><input class="FormText" type="text" size="40" name="p[userEmailAddress]" value="{$p.userEmailAddress}"/></td>
 </tr>
 <tr>
  <td width="20%" style="padding:3px;" valign="top"><span class="FontNorm">{$modules.Language->getString('signature')}:</span></td>
  <td width="80%" style="padding:3px;"><textarea class="FormTextArea" cols="60" rows="8" name="p[userSignature]">{$p.userSignature}</textarea></td>
 </tr>
 </table>
 </fieldset>
 <br/>
 <fieldset>
 <legend><span class="FontSmall"><b>{$modules.Language->getString('change_password')}</b></span></legend>
 <div class="DivInfoBox"><span class="FontInfoBox"><img src="{$modules.Template->getTD()}/images/icons/Info.png" alt="" class="ImageIcon"/>{$modules.Language->getString('change_password_info')}</span></div>
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td width="20%" style="padding:3px;"><span class="FontNorm">{$modules.Language->getString('current_password')}:</span></td>
  <td width="80%" style="padding:3px;"><input class="FormText" type="password" size="30" name="p[userOldPassword]"/></td>
 </tr>
 <tr>
  <td width="20%" style="padding:3px;"><span class="FontNorm">{$modules.Language->getString('new_password')}:</span></td>
  <td width="80%" style="padding:3px;"><input class="FormText" type="password" size="30" name="p[userNewPassword]"/></td>
 </tr>
 <tr>
  <td width="20%" style="padding:3px;"><span class="FontNorm">{$modules.Language->getString('confirm_new_password')}:</span></td>
  <td width="80%" style="padding:3px;"><input class="FormText" type="password" size="30" name="p[userNewPasswordConfirmation]"/></td>
 </tr>
 </table>
 </fieldset>
</td></tr>
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('save_changes')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
