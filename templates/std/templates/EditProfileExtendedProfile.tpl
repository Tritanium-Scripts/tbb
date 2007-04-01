<form method="post" action="{$indexFile}?action=EditProfile&amp;mode=ExtendedProfile&amp;doit=1&amp;{$mySID}">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('Extended_profile')}</span></td></tr>
{if $error != ''}<tr><td class="CellError"><span class="FontError">{$error}</span></td></tr>{/if}
<tr><td class="CellStd">
{foreach from=$groupsData item=curGroup}
 {if count($curGroup.groupFields) > 0}
 <fieldset>
 <legend><span class="FontSmall"><b>{$curGroup.groupName}</b></span></legend>
 <table border="0" cellpadding="2" cellspacing="0" width="100%">
 {foreach from=$curGroup.groupFields item=curField}
  {if $curField.fieldType == $smarty.const.PROFILE_FIELD_TYPE_TEXT}
   <tr>
    <td width="25%"><span class="FontNorm">{$curField.fieldName}:</span></td>
    <td width="75%"><input class="FormText" type="text" size="50" name="p[fieldsData][{$curField.fieldID}]" value="{$curField._fieldValue}"/></td>
   </tr>
  {elseif $curField.fieldType == $smarty.const.PROFILE_FIELD_TYPE_TEXTAREA}
   <tr>
    <td width="25%" valign="top"><span class="FontNorm">{$curField.fieldName}:</span></td>
    <td width="75%"><textarea class="FormTextArea" name="p[FieldsData][{$curField.fieldID}]" cols="30" rows="4">{$curField._fieldValue}</textarea></td>
   </tr>
  {elseif $curField.fieldType == $smarty.const.PROFILE_FIELD_TYPE_SELECTSINGLE}
   <tr>
    <td width="25%"><span class="FontNorm">{$curField.fieldName}:</span></td>
    <td width="75%"><select class="FormSelect" name="p[FieldsData][{$curField.fieldID}]">
    {foreach from=$curField._FieldOptions item=curOption key=curOptionKey}
     <option value="{$curOptionKey}"{if $curOptionKey == $curField._FieldSelectedIDs} selected="selected"{/if}>{$curOption}</option>
    {/foreach}
    </select></td>
   </tr>
  {elseif $curField.fieldType == $smarty.const.PROFILE_FIELD_TYPE_SELECTMULTI}
   <tr>
    <td width="25%" valign="top"><span class="FontNorm">{$curField.fieldName}:</span></td>
    <td width="75%"><select class="FormSelect" name="p[FieldsData][{$curField.fieldID}][]" size="5" multiple="multiple">
    {foreach from=$curField._FieldOptions item=curOption key=curOptionKey}
     <option value="{$curOptionKey}"{if in_array($curOptionKey,$curField._fieldSelectedIDs) == TRUE} selected="selected"{/if}>{$curOption}</option>
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
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Save_changes')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>
