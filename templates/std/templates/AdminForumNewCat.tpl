<!-- AdminForumNewCat -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newkg&amp;newkg=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('add_new_category')}</span></th></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('name_colon')}</span> <input type="text" name="name" value="{$newName}" /></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('add_new_category')}" /></p>
</form>