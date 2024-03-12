{include file='AdminMenu.tpl'}
<!-- AdminMailBlockNewAddress -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=adminMailBlock{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('add_new_mail_block')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_MAIL_BLOCK_NEW_BLOCK_FORM_START}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('email_address_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="mailAddressLocalPart" value="{$newMailAddressLocalPart}" /><span class="fontNorm">@</span><input class="formText" type="text" name="mailAddressSld" value="{$newMailAddressSld}" /><span class="fontNorm">.</span><input class="formText" type="text" size="5" name="mailAddressTld" value="{$newMailAddressTld}" /></td>
 </tr>
 <tr><td class="divInfoBox" colspan="2"><span class="fontSmall"><img src="{Template::getInstance()->getTplDir()}images/icons/info.png" alt="" class="imageIcon" /> {Language::getInstance()->getString('mail_block_hint')}</span></td></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('blocking_period_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" size="4" name="blockPeriod" value="{$newBlockPeriod}" /> <span class="fontSmall">{Language::getInstance()->getString('blocking_period_hint')}</span></td>
 </tr>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_MAIL_BLOCK_NEW_BLOCK_FORM_END}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('add_new_mail_block')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_MAIL_BLOCK_NEW_BLOCK_BUTTONS}</p>
<input type="hidden" name="mode" value="new" />
<input type="hidden" name="create" value="yes" />
</form>
{include file='AdminMenuTail.tpl'}