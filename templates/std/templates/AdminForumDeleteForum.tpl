<!-- AdminForumDeleteForum -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=change&amp;change=yes&amp;ad_forum_id={$editID}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('delete_forum')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p class="norm">{$editName|string_format:$modules.Language->getString('really_delete_forum_x')}</p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('delete_forum')}" /></p>
<input type="hidden" name="confirm" value="yes" />
<input type="hidden" name="kill" value="yes" />
</form>