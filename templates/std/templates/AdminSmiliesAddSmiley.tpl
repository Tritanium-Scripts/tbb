<form method="post" action="{$indexFile}?action=AdminSmilies&amp;mode=addSmiley&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Add_smiley')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Type')}:</span></td>
 <td class="CellAlt"><select name="p[smileyType]" class="FormSelect">
  <option value="{$smarty.const.SMILEY_TYPE_SMILEY}"{if $p.smileyType == $smarty.const.SMILEY_TYPE_SMILEY} selected="selected"{/if}>{$modules.Language->getString('Smiley')}</option>
  <option value="{$smarty.const.SMILEY_TYPE_TPIC}"{if $p.smileyType == $smarty.const.SMILEY_TYPE_TPIC} selected="selected"{/if}>{$modules.Language->getString('Topic_pic')}</option>
  <option value="{$smarty.const.SMILEY_TYPE_ADMINSMILEY}"{if $p.smileyType == $smarty.const.SMILEY_TYPE_ADMINSMILEY} selected="selected"{/if}>{$modules.Language->getString('Adminsmiley')}</option>
  </select></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Path_or_url')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[smileyFileName]" value="{$p.smileyFileName}" size="50"/></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Synonym')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[smileySynonym]" value="{$p.smileySynonym}"/> <span class="FontSmall">({$modules.Language->getString('Only_for_smilies')})</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Status')}:</span></td>
 <td class="CellAlt"><select name="p[smileyStatus]" class="FormSelect">
  <option value="1"{if $p.smileyStatus == 1} selected="selected"{/if}>{$modules.Language->getString('visible')}</option>
  <option value="0"{if $p.smileyStatus == 0} selected="selected"{/if}>{$modules.Language->getString('invisible')}</option>
 </select> <span class="FontSmall">({$modules.Language->getString('Only_for_smilies')})</span></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Add_smiley_topic_pic')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>
