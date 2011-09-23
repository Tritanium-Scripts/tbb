{include file='AdminMenu.tpl'}
{* 0:nick - 1:id - 3:mail - 4:rank - 7:signature - 10:avatar *}
<!-- AdminUserEditUser -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=edit&amp;edit=yes{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('edit_user')}</span></th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('user_id_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$editUser[1]}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('user_name_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm"><input class="formText" type="text" name="name" value="{$editUser[0]}" style="width:250px;" /></span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('email_address_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm"><input class="formText" type="text" name="email" value="{$editUser[3]}" style="width:250px;" /></span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('user_rank_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm"><select class="formSelect" name="status">{html_options values=array(1, 6, 2, 3, 4) output=array($modules.Language->getString('administrator_state'), $modules.Language->getString('super_moderator_state'), $modules.Language->getString('moderator_state'), $modules.Language->getString('normal_state'), $modules.Language->getString('banned_state')) selected=$editUser[4]}</select></span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('special_user_rank_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm"><input class="formText" type="text" name="specialState" value="{$editUser[17]}" style="width:250px;" /></span> <span class="fontSmall">{$modules.Language->getString('overrides_normal_user_rank')}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('avatar_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm"><input class="formText" type="text" name="pic" value="{$editUser[10]}" style="width:250px;" /></span></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('signature_colon')}</span></td>
  <td class="cellAlt"><textarea class="formTextArea" cols="60" rows="6" name="signatur">{$editUser[7]}</textarea></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('edit_user')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="submit" name="kill" value="{$modules.Language->getString('delete_user')}" onclick="return confirm('{$modules.Language->getString('really_delete_this_user')}')" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="id" value="{$editUser[1]}" />
</form>
{include file='AdminMenuTail.tpl'}