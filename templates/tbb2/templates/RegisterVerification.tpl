<!-- RegisterVerification -->
{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=register&amp;mode=verifyAccount{$smarty.const.SID_AMPER}" method="post">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('activate_account')}</span></th></tr>
 <tr><td class="cellInfoBox" colspan="2"><span class="fontNorm"><img src="{Template::getInstance()->getTplDir()}images/icons/info.png" alt="" class="imageIcon" /> {Language::getInstance()->getString('activate_account_info')}</span></td></tr>
 <tr>
  <td class="cellStd" style="width:20%;"><span class="fontNorm">{Language::getInstance()->getString('activation_code_colon')}</span></td>
  <td class="cellAlt" style="width:80%;"><input class="formText" type="text" name="code" value="{$newUser.code}" style="width:250px;" /></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('activate_account')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="verify" value="true" />
</form>