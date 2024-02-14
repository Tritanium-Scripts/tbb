<!-- AdminMailBlockNewAddress -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=adminMailBlock{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('add_new_mail_block')}</span></th></tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('email_address_colon')}</span></td>
  <td class="td1"><input type="text" name="mailAddressLocalPart" value="{$newMailAddressLocalPart}" />@<input type="text" name="mailAddressSld" value="{$newMailAddressSld}" />.<input type="text" size="5" name="mailAddressTld" value="{$newMailAddressTld}" /></td>
 </tr>
 <tr><td class="td1" colspan="2"><span class="small">{Language::getInstance()->getString('mail_block_hint')}</span></td></tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('blocking_period_colon')}</span></td>
  <td class="td1"><input type="text" size="4" name="blockPeriod" value="{$newBlockPeriod}" /> <span class="small">{Language::getInstance()->getString('blocking_period_hint')}</span></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('add_new_mail_block')}" /></p>
<input type="hidden" name="mode" value="new" />
<input type="hidden" name="create" value="yes" />
</form>