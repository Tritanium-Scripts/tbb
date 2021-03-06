{include file='AdminMenu.tpl'}
<!-- AdminGroupNewGroup -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=new{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('add_new_group')}</span></th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('name_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="title" value="{$newName}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('avatar_colon')}</span><br /><span class="fontSmall">{$modules.Language->getString('avatar_description')}</span></td>
  <td class="cellAlt" style="vertical-align:top;"><input class="formText" type="text" name="pic" value="{$newAvatar}" style="width:250px;" /> <span class="fontSmall">{$modules.Language->getString('url_or_path')}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('members_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="group_members" value="{$newUserIDs}" style="width:250px;" /> <span class="fontSmall">{$modules.Language->getString('separate_ids_with_comma')}</span></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('add_new_group')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="create" value="yes" />
</form>
{include file='AdminMenuTail.tpl'}