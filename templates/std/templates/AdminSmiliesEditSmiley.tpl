<form method="post" action="{$smarty.const.INDEXFILE}?action=AdminSmilies&amp;mode=editSmiley&amp;smileyID={$smileyID}&amp;doit=1&amp;{$smarty.const.MYSID}">
<table class="TableStd" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('edit_smiley')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('type')}:</span></td>
 <td class="CellAlt"><select name="p[smileyType]" class="FormSelect">
  <option value="{$smarty.const.SMILEY_TYPE_SMILEY}"{if $p.smileyType == $smarty.const.SMILEY_TYPE_SMILEY} selected="selected"{/if}>{$modules.Language->getString('smiley')}</option>
  <option value="{$smarty.const.SMILEY_TYPE_TPIC}"{if $p.smileyType == $smarty.const.SMILEY_TYPE_TPIC} selected="selected"{/if}>{$modules.Language->getString('topic_pic')}</option>
  <option value="{$smarty.const.SMILEY_TYPE_ADMINSMILEY}"{if $p.smileyType == $smarty.const.SMILEY_TYPE_ADMINSMILEY} selected="selected"{/if}>{$modules.Language->getString('adminsmiley')}</option>
  </select></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('path_or_url')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[smileyFileName]" value="{$p.smileyFileName}" size="50"/></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('synonym')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[smileySynonym]" value="{$p.smileySynonym}"/> <span class="FontSmall">({$modules.Language->getString('only_for_smilies')})</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('status')}:</span></td>
 <td class="CellAlt"><select name="p[smileyStatus]" class="FormSelect">
  <option value="1"{if $p.smileyStatus == 1} selected="selected"{/if}>{$modules.Language->getString('visible')}</option>
  <option value="0"{if $p.smileyStatus == 0} selected="selected"{/if}>{$modules.Language->getString('invisible')}</option>
 </select> <span class="FontSmall">({$modules.Language->getString('only_for_smilies')})</span></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('edit_smiley')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
