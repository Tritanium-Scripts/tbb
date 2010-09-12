{include file='AdminMenu.tpl'}
<!-- AdminForumEditCat -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=chgkg&amp;chgkg=yes{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('edit_category')}</span></th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('name_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="name" value="{$editName}" /></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('edit_category')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="id" value="{$catID}" />
</form>
{include file='AdminMenuTail.tpl'}