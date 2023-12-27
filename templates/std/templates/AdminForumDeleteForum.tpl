<!-- AdminForumDeleteForum -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=change&amp;change=yes&amp;ad_forum_id={$editID}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('delete_forum')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p class="norm">{$editName|string_format:Language::getInstance()->getString('really_delete_forum_x')}</p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('delete_forum')}" /></p>
<input type="hidden" name="confirm" value="yes" />
<input type="hidden" name="kill" value="yes" />
</form>