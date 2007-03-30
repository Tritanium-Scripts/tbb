<form method="post" action="{$IndexFile}?Action=EditProfile&amp;Mode=ExtendedProfile&amp;Doit=1&amp;{$MySID}">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$Modules.Language->getString('Extended_profile')}</span></td></tr>
{if $Error != ''}<tr><td class="CellError"><span class="FontError">{$Error}</span></td></tr>{/if}
<tr><td class="CellStd">
{foreach from=$GroupsData item=curGroup}
 {if count($curGroup.GroupFields) > 0}
 <fieldset>
 <legend><span class="FontSmall"><b>{$curGroup.GroupName}</b></span></legend>
 <table border="0" cellpadding="2" cellspacing="0" width="100%">
 {foreach from=$curGroup.GroupFields item=curField}
  {if $curField.FieldType == $smarty.const.PROFILE_FIELD_TYPE_TEXT}
   <tr>
    <td width="25%"><span class="FontNorm">{$curField.FieldName}:</span></td>
    <td width="75%"><input class="FormText" type="text" size="50" name="p[FieldsData][{$curField.FieldID}]" value="{$curField._FieldValue}"/></td>
   </tr>
  {elseif $curField.FieldType == $smarty.const.PROFILE_FIELD_TYPE_TEXTAREA}
   <tr>
    <td width="25%" valign="top"><span class="FontNorm">{$curField.FieldName}:</span></td>
    <td width="75%"><textarea class="formtextarea" name="p[FieldsData][{$curField.FieldID}]" cols="30" rows="4">{$curField._FieldValue}</textarea></td>
   </tr>
  {elseif $curField.FieldType == $smarty.const.PROFILE_FIELD_TYPE_SELECTSINGLE}
   <tr>
    <td width="25%"><span class="FontNorm">{$curField.FieldName}:</span></td>
    <td width="75%"><select class="FormSelect" name="p[FieldsData][{$curField.FieldID}]">
    {foreach from=$curField._FieldOptions item=curOption key=curOptionKey}
     <option value="{$curOptionKey}"{if $curOptionKey == $curField._FieldSelectedIDs} selected="selected"{/if}>{$curOption}</option>
    {/foreach}
    </select></td>
   </tr>
  {elseif $curField.FieldType == $smarty.const.PROFILE_FIELD_TYPE_SELECTMULTI}
   <tr>
    <td width="25%" valign="top"><span class="FontNorm">{$curField.FieldName}:</span></td>
    <td width="75%"><select class="FormSelect" name="p[FieldsData][{$curField.FieldID}][]" size="5" multiple="multiple">
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
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$Modules.Language->getString('Save_changes')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$Modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>
