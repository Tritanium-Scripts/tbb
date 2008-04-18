<form method="post" action="{$indexFile}?action=AdminProfileFields&amp;mode=EditField&amp;fieldID={$fieldID}&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Edit_profile_field')}</span></td></tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Field_name')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" size="40" name="p[fieldName]" value="{$p.fieldName}"/></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Field_type')}:</span></td>
 <td class="CellAlt"><select class="FormSelect" name="p[fieldType]">
  <option value="0"{if $p.fieldType} == $smarty.const.PROFILE_FIELD_TYPE_TEXTFIELD} selected="selected"{/if}>{$modules.Language->getString('Textfield')}</option>
  <option value="1"{if $p.fieldType} == $smarty.const.PROFILE_FIELD_TYPE_TEXTAREA} selected="selected"{/if}>{$modules.Language->getString('Textarea')}</option>
  <option value="2"{if $p.fieldType} == $smarty.const.PROFILE_FIELD_TYPE_SELECTSINGLE} selected="selected"{/if}>{$modules.Language->getString('Single_selection_list')}</option>
  <option value="3"{if $p.fieldType} == $smarty.const.PROFILE_FIELD_TYPE_SELECTMULTI} selected="selected"{/if}>{$modules.Language->getString('Multiple_selection_list')}</option>
 </select></td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Field_data')}:</span><br/><span class="FontSmall">{$modules.Language->getString('field_data_info')}</span></td>
 <td class="CellAlt"><textarea class="FormTextArea" cols="40" rows="8" name="p[fieldData]">{$p.fieldData}</textarea></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Regex_verification')}:</span><br/><span class="FontSmall">{$modules.Language->getString('regex_verification_info')}</span></td>
 <td class="CellAlt" valign="top"><input class="FormText" type="text" size="50" name="p[fieldRegexVerification]" value="{$p.fieldRegexVerification}"/></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Field_link')}:</span><br/><span class="FontSmall">{$modules.Language->getString('field_link_info')}</span></td>
 <td class="CellAlt" valign="top"><input class="FormText" type="text" size="50" name="p[fieldLink]" value="{$p.fieldLink}"/></td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Other_options')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">
  <label><input type="checkbox" name="c[fieldIsRequired]" value="1"{if $c.fieldIsRequired == 1} checked="checked"{/if}/> {$modules.Language->getString('Field_is_required')}</label><br/>
  <label><input type="checkbox" name="c[fieldShowRegistration]" value="1"{if $c.fieldShowRegistration == 1} checked="checked"{/if}/> {$modules.Language->getString('Show_at_registration')}</label><br/>
  <label><input type="checkbox" name="c[fieldShowMemberlist]" value="1"{if $c.fieldShowMemberlist == 1} checked="checked"{/if}/> {$modules.Language->getString('Show_at_memberlist')}</label><br/>
 </span></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Edit_profile_field')}"/></td></tr>
</table>
</form>
