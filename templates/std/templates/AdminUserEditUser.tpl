{* 0:nick - 1:id - 3:mail - 4:rank - 7:signature - 9:homepage - 10:avatar *}
<!-- AdminUserEditUser -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=edit&amp;edit=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('edit_user')}</span></th></tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('user_id_colon')}</span></td>
  <td class="td1"><span class="norm">{$editUser[1]}</span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('user_name_colon')}</span></td>
  <td class="td1"><span class="norm"><input type="text" name="name" value="{$editUser[0]}" style="width:250px;" /></span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('email_address_colon')}</span></td>
  <td class="td1"><span class="norm"><input type="text" name="email" value="{$editUser[3]}" style="width:250px;" /></span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('user_rank_colon')}</span></td>
  <td class="td1"><span class="norm">{html_options name='status' values=array(1, 6, 2, 3, 4) output=array($modules.Language->getString('administrator_state'), $modules.Language->getString('super_moderator_state'), $modules.Language->getString('moderator_state'), $modules.Language->getString('normal_state'), $modules.Language->getString('banned_state')) selected=$editUser[4]}</span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('special_user_rank_colon')}</span></td>
  <td class="td1"><span class="norm"><input type="text" name="specialState" value="{$editUser[17]}" style="width:250px;" /></span> <span class="small">{$modules.Language->getString('overrides_normal_user_rank')}</span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('homepage_colon')}</span></td>
  <td class="td1"><span class="norm"><input type="text" name="hp" value="{$editUser[9]}" style="width:250px;" /></span> <span class="small">{$modules.Language->getString('http_dots')}</span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('avatar_colon')}</span></td>
  <td class="td1"><span class="norm"><input type="text" name="pic" value="{$editUser[10]}" style="width:250px;" /></span></td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('signature_colon')}</span></td>
  <td class="td1"><textarea cols="55" rows="8" name="signatur">{$editUser[7]}</textarea></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('edit_user')}" />&nbsp;&nbsp;&nbsp;<input type="submit" name="kill" value="{$modules.Language->getString('delete_user')}" onclick="return confirm('{$modules.Language->getString('really_delete_this_user')}')" /></p>
<input type="hidden" name="id" value="{$editUser[1]}" />
</form>