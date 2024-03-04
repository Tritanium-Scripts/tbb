<!-- AdminGroupDeleteGroup -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=kill&amp;group_id={$groupID}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('delete_group')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{$groupName|string_format:Language::getInstance()->getString('really_delete_group')}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('delete_group')}" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_GROUP_DELETE_GROUP_BUTTONS}</p>
<input type="hidden" name="kill" value="yes" />
</form>