{include file='AdminMenu.tpl'}
<!-- AdminForumNewCat -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newkg&amp;newkg=yes{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('add_new_category')}</span></th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('name_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="name" value="{$newName}" /></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('add_new_category')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
</form>
{include file='AdminMenuTail.tpl'}