<form method="post" action="{$indexFile}?action=Register&amp;mode=RegisterForm&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('register')}</span></td></tr>
{if $error != ''}<tr><td class="CellError"><span class="FontError"><img src="{$modules.Template->getTD()}/images/icons/Warning.png" class="ImageIcon" alt=""/>{$error}</span></td></tr>{/if}
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('general_information')}</span></td></tr>
<tr><td class="CellStd">
 <fieldset>
  <legend><span class="FontSmall">{$modules.Language->getString('user_name')}</span></legend>
  <div class="DivInfoBox"><img src="{$modules.Template->getTD()}/images/icons/Info.png" alt="" class="ImageIcon"/><span class="FontInfoBox">{$modules.Language->getString('user_name_info')}</span></div>
  <span class="FontNorm"><b>{$modules.Language->getString('user_name')}:</b> <input class="FormText" type="text" name="p[userName]" value="{$p.userName}" size="30"/></span>
 </fieldset>
 <br/>
 <fieldset>
  <legend><span class="FontSmall">{$modules.Language->getString('email_address')}</span></legend>
  <div class="DivInfoBox"><img src="{$modules.Template->getTD()}/images/icons/Info.png" alt="" class="ImageIcon"/><span class="FontInfoBox">{$modules.Language->getString('email_address_info')}</span></div>
  <span class="FontNorm"><b>{$modules.Language->getString('email_address')}:</b> <input class="FormText" type="text" name="p[userEmailAddress]" value="{$p.userEmailAddress}" size="40"/>&nbsp;&nbsp;&nbsp;<b>{$modules.Language->getString('email_address_confirmation')}:</b> <input class="FormText" type="text" name="p[userEmailAddressConfirmation]" value="{$p.userEmailAddressConfirmation}" size="40"/></span>
 </fieldset>
 {if $modules.Config->getValue('verify_email_address') != 1}
 <br/>
  <fieldset>
   <legend><span class="FontSmall">{$modules.Language->getString('password')}</span></legend>
   <div class="DivInfoBox"><img src="{$modules.Template->getTD()}/images/icons/Info.png" alt="" class="ImageIcon"/><span class="FontInfoBox">{$modules.Language->getString('password_info')}</span></div>
   <span class="FontNorm"><b>{$modules.Language->getString('password')}:</b> <input class="FormText" type="password" name="p[userPassword]" value="" size="40"/>&nbsp;&nbsp;&nbsp;<b>{$modules.Language->getString('password_confirmation')}:</b> <input class="FormText" type="password" name="p[userPasswordConfirmation]" value="" size="40"/></span>
  </fieldset>
 {/if}
</td></tr>
{if $fieldsCounter > 0}
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('other_information')}</span></td></tr>
<tr><td class="CellStd">
{foreach from=$groupsData item=curGroup}
 {if count($curGroup.groupFields) > 0}
 <fieldset>
 <legend><span class="FontSmall"><b>{$curGroup.groupName}</b></span></legend>
 <table style="padding:2px;" width="100%">
 <colgroup>
  <col width="20%"/>
  <col width="80%"/>
 </colgroup>
 {foreach from=$curGroup.groupFields item=curField}
  {if $curField.fieldType == $smarty.const.PROFILE_FIELD_TYPE_TEXT}
   <tr>
    <td><span class="FontNorm">{$curField.fieldName}:</span></td>
    <td><input class="FormText" type="text" size="50" name="p[fieldsData][{$curField.fieldID}]" value="{$curField._fieldValue}"/></td>
   </tr>
  {elseif $curField.fieldType == $smarty.const.PROFILE_FIELD_TYPE_TEXTAREA}
   <tr>
    <td valign="top"><span class="FontNorm">{$curField.fieldName}:</span></td>
    <td><textarea class="FormTextArea" name="p[fieldsData][{$curField.fieldID}]" cols="30" rows="4">{$curField._fieldValue}</textarea></td>
   </tr>
  {elseif $curField.fieldType == $smarty.const.PROFILE_FIELD_TYPE_SELECTSINGLE}
   <tr>
    <td><span class="FontNorm">{$curField.fieldName}:</span></td>
    <td><select class="FormSelect" name="p[fieldsData][{$curField.fieldID}]">
    {foreach from=$curField._fieldOptions item=curOption key=curOptionKey}
     <option value="{$curOptionKey}"{if $curOptionKey == $curField._fieldSelectedIDs} selected="selected"{/if}>{$curOption}</option>
    {/foreach}
    </select></td>
   </tr>
  {elseif $curField.fieldType == $smarty.const.PROFILE_FIELD_TYPE_SELECTMULTI}
   <tr>
    <td valign="top"><span class="FontNorm">{$curField.fieldName}:</span></td>
    <td><select class="FormSelect" name="p[fieldsData][{$curField.FieldID}][]" size="5" multiple="multiple">
    {foreach from=$curField._fieldOptions item=curOption key=curOptionKey}
     <option value="{$curOptionKey}"{if in_array($curOptionKey,$curField._fieldSelectedIDs)} selected="selected"{/if}>{$curOption}</option>
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
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('register')}"/></td></tr>
</table>
</form>