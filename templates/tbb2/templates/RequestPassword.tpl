{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=sendpw{$smarty.const.SID_AMPER}" method="post">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('request_new_password')}</span></th></tr>
 <tr>
  <td class="cellStd" style="width:15%;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('user_name_colon')}</span></td>
  <td class="cellAlt" style="width:85%;"><input class="formText" type="text" name="nick" value="{$loginName}" /></td>
 </tr>
 <tr><td class="divInfoBox" colspan="2"><span class="fontInfoBox"><img src="{$modules.Template->getTplDir()}images/icons/info.png" class="imageIcon" alt="" /> {$modules.Language->getString('create_new_password')|string_format:$modules.Language->getString('request_new_password_info')}</span></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('create_new_password')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="send" value="1" />
</form>