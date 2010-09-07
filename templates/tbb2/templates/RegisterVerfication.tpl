<!-- RegisterVerfication -->
{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=register&amp;mode=verifyAccount{$smarty.const.SID_AMPER}" method="post">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('activate_account')}</span></th></tr>
 <tr><td class="cellInfoBox" colspan="2"><span class="fontNorm"><img src="{$modules.Template->getTplDir()}images/icons/info.png" class="imageIcon" alt="" /> {$modules.Language->getString('activate_account_info')}</span></td></tr>
 <tr>
  <td class="cellStd" style="font-weight:bold; width:20%;"><span class="fontNorm">{$modules.Language->getString('activation_code_colon')}</span></td>
  <td class="cellAlt" style="width:80%;"><input class="formText" type="text" name="code" value="{$newUser.code}" style="width:250px;" /></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('activate_account')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="verify" value="true" />
</form>