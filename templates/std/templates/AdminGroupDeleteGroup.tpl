<!-- AdminGroupDeleteGroup -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=kill&amp;group_id={$groupID}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('delete_group')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p class="norm">{$groupName|string_format:Language::getInstance()->getString('really_delete_group')}</p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('delete_group')}" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_GROUP_DELETE_GROUP_BUTTONS}</p>
<input type="hidden" name="kill" value="yes" />
</form>