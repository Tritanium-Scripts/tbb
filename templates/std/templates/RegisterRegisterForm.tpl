<form method="post" action="{$indexFile}?action=Register&amp;mode=RegisterForm&amp;Doit=1&amp;{$mySID}">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Register')}</span></td></tr>
{if $error != ''}<tr><td class="CellError"><span class="FontError">{$error}</span></td></tr>{/if}
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('General_information')}</span></td></tr>
<tr><td class="CellStd">
 <fieldset>
  <legend><span class="FontSmall"><b>{$modules.Language->getString('User_name')}</b></span></legend>
  <div class="DivInfoBox"><img src="{$modules.Template->getTD()}/images/icons/Info.gif" class="ImageIcon"/><span class="FontInfoBox">{$modules.Language->getString('user_name_info')}</span></div>
  <span class="FontNorm"><b>{$modules.Language->getString('User_name')}:</b> <input class="FormText" type="text" name="p[UserName]" value="{$p.UserName}" size="30"/></span>
 </fieldset>
 <br/>
 <fieldset>
  <legend><span class="FontSmall">{$modules.Language->getString('Email_address')}</span></legend>
  <div class="DivInfoBox"><img src="{$modules.Template->getTD()}/images/icons/Info.gif" class="ImageIcon"/><span class="FontInfoBox">{$modules.Language->getString('email_address_info')}</span></div>
  <span class="FontNorm"><b>{$modules.Language->getString('Email_address')}:</b> <input class="FormText" type="text" name="p[UserEmail]" value="{$p.UserEmail}" size="40"/>&nbsp;&nbsp;&nbsp;<b>{$modules.Language->getString('Email_address_confirmation')}:</b> <input class="FormText" type="text" name="p[UserEmailConfirmation]" value="{$p.UserEmailConfirmation}" size="40"/></span>
 </fieldset>
 {if $modules.Config->getValue('verify_email_address') != 1}
 <br/>
  <fieldset>
   <legend><span class="FontSmall">{$modules.Language->getString('Password')}</span></legend>
   <div class="DivInfoBox"><img src="{$modules.Template->getTD()}/images/icons/Info.gif" class="ImageIcon"/><span class="FontInfoBox">{$modules.Language->getString('password_info')}</span></div>
   <span class="FontNorm"><b>{$modules.Language->getString('Password')}:</b> <input class="FormText" type="password" name="p[UserPassword]" value="" size="40"/>&nbsp;&nbsp;&nbsp;<b>{$modules.Language->getString('Password_confirmation')}:</b> <input class="FormText" type="password" name="p[UserPasswordConfirmation]" value="" size="40"/></span>
  </fieldset>
 {/if}
</td></tr>
{if $fieldsCounter > 0}
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('Other_information')}</span></td></tr>
<tr><td class="CellStd">
{foreach from=$groupsData item=curGroup}
 {if count($curGroup.GroupFields) > 0}
 <fieldset>
 <legend><span class="FontSmall"><b>{$curGroup.GroupName}</b></span></legend>
 <table style="padding:2px;" width="100%">
 <colgroup>
  <col width="20%"/>
  <col width="80%"/>
 </colgroup>
 {foreach from=$curGroup.GroupFields item=curField}
  {if $curField.FieldType == $smarty.const.PROFILE_FIELD_TYPE_TEXT}
   <tr>
    <td><span class="FontNorm">{$curField.FieldName}:</span></td>
    <td><input class="FormText" type="text" size="50" name="p[FieldsData][{$curField.FieldID}]" value="{$curField._FieldValue}"/></td>
   </tr>
  {elseif $curField.FieldType == $smarty.const.PROFILE_FIELD_TYPE_TEXTAREA}
   <tr>
    <td valign="top"><span class="FontNorm">{$curField.FieldName}:</span></td>
    <td><textarea class="formtextarea" name="p[FieldsData][{$curField.FieldID}]" cols="30" rows="4">{$curField._FieldValue}</textarea></td>
   </tr>
  {elseif $curField.FieldType == $smarty.const.PROFILE_FIELD_TYPE_SELECTSINGLE}
   <tr>
    <td><span class="FontNorm">{$curField.FieldName}:</span></td>
    <td><select class="FormSelect" name="p[FieldsData][{$curField.FieldID}]">
    {foreach from=$curField._FieldOptions item=curOption key=curOptionKey}
     <option value="{$curOptionKey}"{if $curOptionKey == $curField._FieldSelectedIDs} selected="selected"{/if}>{$curOption}</option>
    {/foreach}
    </select></td>
   </tr>
  {elseif $curField.FieldType == $smarty.const.PROFILE_FIELD_TYPE_SELECTMULTI}
   <tr>
    <td valign="top"><span class="FontNorm">{$curField.FieldName}:</span></td>
    <td><select class="FormSelect" name="p[FieldsData][{$curField.FieldID}][]" size="5" multiple="multiple">
    {foreach from=$curField._FieldOptions item=curOption key=curOptionKey}
     <option value="{$curOptionKey}"{if in_array($curOptionKey,$curField._FieldSelectedIDs) == TRUE} selected="selected"{/if}>{$curOption}</option>
    {/foreach}
    </select></td>
   </tr>
  {/if}
 {/foreach}
 </table>
 </fieldset>
 {/if}
{/foreach}
</td></tr>
{/if}
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Register')}"/></td></tr>
</table>
</form>
