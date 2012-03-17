{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=sendpw{$smarty.const.SID_AMPER}" method="post">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('request_new_password')}</span></th></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('user_name_colon')}</span> <input type="text" name="nick" value="{$loginName}" /><hr /><span class="norm">{$modules.Language->getString('create_new_password')|string_format:$modules.Language->getString('request_new_password_info')}</span></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('create_new_password')}" /></p>
<input type="hidden" name="send" value="1" />
</form>