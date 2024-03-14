<!-- PrivateMessageNewPM -->
{include file='Errors.tpl'}
<form name="pmform" method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;send=yes{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('new_pm')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PRIVATE_MESSAGE_NEW_PM_FORM_START}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('recipient_id_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="target_id" value="{$recipient}" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('subject_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="betreff" value="{$newPM[1]}" style="width:300px;" /></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('bbcode_colon')}</span></td>
  <td class="cellAlt">{include file='BBCodes.tpl' targetBoxID='pm'}</td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('message_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='pm'}</td>
  <td class="cellAlt"><textarea class="formTextArea" id="pm" name="pm" rows="14" cols="80">{$newPM[2]}</textarea></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PRIVATE_MESSAGE_NEW_PM_FORM_END}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('options_colon')}</span></td>
  <td class="cellAlt">
   <input type="checkbox" id="smilies" name="smilies" value="1" style="vertical-align:middle;"{if $newPM[5]} checked="checked"{/if} /> <label for="smilies" class="fontNorm">{Language::getInstance()->getString('enable_smilies')}</label><br />
   <input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" style="vertical-align:middle;"{if $newPM[6]} checked="checked"{/if} /> <label for="use_upbcode" class="fontNorm">{Language::getInstance()->getString('enable_bbcode')}</label><br />
   <input type="checkbox" id="storeToOutbox" name="storeToOutbox" value="true" style="vertical-align:middle;"{if $storeToOutbox} checked="checked"{/if} /> <label for="storeToOutbox" class="fontNorm">{Language::getInstance()->getString('store_to_outbox')}</label>
  </td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('send_pm')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_PRIVATE_MESSAGE_NEW_PM_BUTTONS}</p>
<input type="hidden" name="pmbox_id" value="{$pmBoxID}" />
</form>