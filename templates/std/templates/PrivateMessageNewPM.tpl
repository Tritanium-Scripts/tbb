<!-- PrivateMessageNewPM -->
{include file='Errors.tpl'}
<form name="pmform" method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;send=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('new_pm')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('recipient_id_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="target_id" value="{$recipient}" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('subject_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="betreff" value="{$newPM[1]}" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{Language::getInstance()->getString('message_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='pm'}</td>
  <td class="td1" style="width:80%;"><textarea id="pm" name="pm" rows="10" cols="50">{$newPM[2]}</textarea></td>
 </tr>{if Config::getInstance()->getCfgVal('tspacing') < 1}
 <tr><td class="td1" colspan="2"><hr /></td></tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{Language::getInstance()->getString('options_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <input type="checkbox" id="smilies" name="smilies" value="1" style="vertical-align:middle;"{if $newPM[5]} checked="checked"{/if} /> <label for="smilies" class="norm">{Language::getInstance()->getString('enable_smilies')}</label><br />
   <input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" style="vertical-align:middle;"{if $newPM[6]} checked="checked"{/if} /> <label for="use_upbcode" class="norm">{Language::getInstance()->getString('enable_bbcode')}</label><br />
   <input type="checkbox" id="storeToOutbox" name="storeToOutbox" value="true" style="vertical-align:middle;"{if $storeToOutbox} checked="checked"{/if} /> <label for="storeToOutbox" class="norm">{Language::getInstance()->getString('store_to_outbox')}</label>
  </td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('send_pm')}" /></p>
<input type="hidden" name="pmbox_id" value="{$pmBoxID}" />
</form>