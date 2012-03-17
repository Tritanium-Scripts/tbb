<!-- PrivateMessageNewPM -->
{include file='Errors.tpl'}
<form name="pmform" method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;send=yes{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('new_pm')}</span></th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('recipient_id_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="target_id" value="{$recipient}" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('subject_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="betreff" value="{$newPM[1]}" style="width:300px;" /></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('message_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='pm'}</td>
  <td class="cellAlt"><textarea class="formTextArea" id="pm" name="pm" rows="14" cols="80">{$newPM[2]}</textarea></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('options_colon')}</span></td>
  <td class="cellAlt">
   <input type="checkbox" id="smilies" name="smilies" value="1" style="vertical-align:middle;"{if $newPM[5]} checked="checked"{/if} /> <label for="smilies" class="fontNorm">{$modules.Language->getString('enable_smilies')}</label><br />
   <input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" style="vertical-align:middle;"{if $newPM[6]} checked="checked"{/if} /> <label for="use_upbcode" class="fontNorm">{$modules.Language->getString('enable_bbcode')}</label><br />
   <input type="checkbox" id="storeToOutbox" name="storeToOutbox" value="true" style="vertical-align:middle;"{if $storeToOutbox} checked="checked"{/if} /> <label for="storeToOutbox" class="fontNorm">{$modules.Language->getString('store_to_outbox')}</label>
  </td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('send_pm')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="pmbox_id" value="{$pmBoxID}" />
</form>