<!-- AdminForumEditCat -->
{include file='Errors.tpl'}
<form method=post action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=chgkg&amp;chgkg=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan=2><span class="thnorm">{$modules.Language->getString('edit_category')}</span></th></tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('name_colon')}</span></td>
  <td class="td1"><input type="text" name="name" value="{$editName}" /></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('edit_category')}" /></p>
<input type="hidden" name="id" value="{$catID}" />
</form>