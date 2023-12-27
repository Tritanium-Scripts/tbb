{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=sendpw{$smarty.const.SID_AMPER}" method="post">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('request_new_password')}</span></th></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('user_name_colon')}</span> <input type="text" name="nick" value="{$loginName}" /><hr /><span class="norm">{Language::getInstance()->getString('create_new_password')|string_format:Language::getInstance()->getString('request_new_password_info')}</span></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('create_new_password')}" /></p>
<input type="hidden" name="send" value="1" />
</form>