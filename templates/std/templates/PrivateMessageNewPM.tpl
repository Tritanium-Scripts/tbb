<!-- PrivateMessageNewPM -->
{include file='Errors.tpl'}
<form name="pmform" method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;send=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('new_pm')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('recipient_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="target_id" value="{$recipient}" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('subject_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="betreff" value="{$newPM[1]}" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{$modules.Language->getString('message_colon')}</span><br /><br/>smilies</td>
  <td class="td1" style="width:80%;"><textarea name="pm" rows="10" cols="50">{$newPM[2]}</textarea></td>
 </tr>{if $modules.Config->getCfgVal('tspacing') < 1}
 <tr><td class="td1" colspan="2"><hr /></td></tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{$modules.Language->getString('options_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="checkbox" id="smilies" name="smilies" value="1" style="vertical-align:middle;" checked="checked" /> <label for="smilies" class="norm">{$modules.Language->getString('enable_smilies')}</label><br /><input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" style="vertical-align:middle;" checked="checked" /> <label for="use_upbcode" class="norm">{$modules.Language->getString('enable_bbcode')}</label></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('send_pm')}" onfocus="this.blur()"></p>
<input type="hidden" name="pmbox_id" value="{$pmBoxID}" />
</form>