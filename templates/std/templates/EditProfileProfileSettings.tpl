<form method="post" action="index.php?action=editprofile&amp;mode=settings&amp;doit=1&amp;{$mYSID}">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('Settings')}</span></td></tr>
<tr><td class="CellStd">
 <fieldset>
  <legend><span class="FontSmall"><b>{$modules.Language->getString('General_settings')}</b></span></legend>
  <table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
   <td width="35%" valign="top"><span class="FontNorm">{$modules.Language->getString('Show_email_address')}:</span><br/><span class="FontSmall">{$modules.Language->getString('show_email_address_info')}</span></td>
   <td width="65%" valign="top"><span class="FontNorm"><input class="FormRadio" type="radio" name="p[UserHideEmail]" value="0"{if $p.UserHideEmail == 0} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Yes')}&nbsp;&nbsp;&nbsp;<input class="FormRadio" type="radio" name="p[UserHideEmail]" value="1"{if $p.UserHideEmail == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('No')}</span></td>
  </tr>
  <tr>
   <td width="35%" valign="top"><span class="FontNorm">{$modules.Language->getString('Receive_board_emails')}:</span><br/><span class="FontSmall">{$modules.Language->getString('receive_board_emails_info')}</span></td>
   <td width="65%" valign="top"><span class="FontNorm"><input class="FormRadio" type="radio" name="p[UserReceiveEmails]" value="1"{if $p.UserReceiveEmails == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Yes')}&nbsp;&nbsp;&nbsp;<input class="FormRadio" type="radio" name="p[UserReceiveEmails]" value="0"{if $p.UserReceiveEmails == 0} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('No')}</span></td>
  </tr>
  <tr>
   <td width="35%" valign="top"><span class="FontNorm">{$modules.Language->getString('Timezone')}:</span></td>
   <td width="65%" valign="top"><select class="form_select" name="p[UserTimeZone]">
   {foreach from=$timeZones item=curTimeZone key=curTimeZoneKey}
    <option value="{$curTimeZoneKey}"{if $curTimeZoneKey == $p.UserTimeZone} selected="selected"{/if}>{$curTimeZone}</option>
   {/foreach}
   </select></td>
  </tr>
  </table>
 </fieldset>
</td></tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Save_changes')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>
