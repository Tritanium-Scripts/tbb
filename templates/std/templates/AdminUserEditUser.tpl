{* 0:nick - 1:id - 3:mail - 4:rank - 7:signature - 9:homepage - 10:avatar *}
<!-- AdminUserEditUser -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=edit&amp;edit=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('edit_user')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_EDIT_USER_FORM_START}
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('user_id_colon')}</span></td>
  <td class="td1"><span class="norm">{$editUser[1]}</span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('user_name_colon')}</span></td>
  <td class="td1"><span class="norm"><input type="text" name="name" value="{$editUser[0]}" style="width:250px;" /></span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('email_address_colon')}</span></td>
  <td class="td1"><span class="norm"><input type="text" name="email" value="{$editUser[3]}" style="width:250px;" /></span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('user_rank_colon')}</span></td>
  <td class="td1"><span class="norm">{html_options name='status' values=array(1, 6, 2, 3, 4) output=array(Language::getInstance()->getString('administrator_state'), Language::getInstance()->getString('super_moderator_state'), Language::getInstance()->getString('moderator_state'), Language::getInstance()->getString('normal_state'), Language::getInstance()->getString('banned_state')) selected=$editUser[4]}</span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('special_user_rank_colon')}</span></td>
  <td class="td1"><span class="norm"><input type="text" name="specialState" value="{$editUser[17]}" style="width:250px;" /></span> <span class="small">{Language::getInstance()->getString('overrides_normal_user_rank')}</span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('homepage_colon')}</span></td>
  <td class="td1"><span class="norm"><input type="text" name="hp" value="{$editUser[9]}" style="width:250px;" /></span> <span class="small">{Language::getInstance()->getString('http_dots')}</span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('avatar_colon')}</span></td>
  <td class="td1"><span class="norm"><input type="text" name="pic" value="{$editUser[10]}" style="width:250px;" /></span></td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('signature_colon')}</span></td>
  <td class="td1"><textarea cols="55" rows="8" name="signatur">{$editUser[7]}</textarea></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_EDIT_USER_FORM_END}
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('edit_user')}" />&nbsp;&nbsp;&nbsp;<input type="submit" name="kill" value="{Language::getInstance()->getString('delete_user')}" onclick="return confirm('{Language::getInstance()->getString('really_delete_this_user')}')" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_EDIT_USER_BUTTONS}</p>
<input type="hidden" name="id" value="{$editUser[1]}" />
</form>