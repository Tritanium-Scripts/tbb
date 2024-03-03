<!-- AdminForumDeleteForum -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=change&amp;change=yes&amp;ad_forum_id={$editID}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('delete_forum')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{$editName|string_format:Language::getInstance()->getString('really_delete_forum_x')}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('delete_forum')}" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_DELETE_FORUM_BUTTONS}</p>
<input type="hidden" name="confirm" value="yes" />
<input type="hidden" name="kill" value="yes" />
</form>