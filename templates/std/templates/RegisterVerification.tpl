<!-- RegisterVerification -->
{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=register&amp;mode=verifyAccount{$smarty.const.SID_AMPER}" method="post">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('activate_account')}</span></th></tr>
 <tr><td class="td1" colspan="2"><span class="small">{Language::getInstance()->getString('activate_account_info')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('activation_code_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="code" value="{$newUser.code}" style="width:250px;" /></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('activate_account')}" /></p>
<input type="hidden" name="verify" value="true" />
</form>