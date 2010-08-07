<!-- AdminUserNewUser -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=new&amp;create=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('add_new_member')}</span></th></tr>
 <tr><td colspan="2" class="kat"><span class="kat">{$modules.Language->getString('user_data')}</span></td></tr>
 <tr>
  <td class="td1" style="width:30%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('user_name_colon')}</span></td>
  <td class="td1" style="width:70%;"><input type="text" name="new[nick]" value="{$newUser['nick']}" style="width:150px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="width:30%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('email_address_colon')}</span></td>
  <td class="td1" style="width:70%;"><input type="text" name="new[email]" value="{$newUser['email']}" style="width:150px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="width:30%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('password_colon')}</span></td>
  <td class="td1" style="width:70%;"><input type="password" name="new[pw1]" style="width:150px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="width:30%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('confirm_password_colon')}</span></td>
  <td class="td1" style="width:70%;"><input type="password" name="new[pw2]" style="width:150px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="width:30%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('group_colon')}</span></td>
  <td class="td1" style="width:70%;"><select name="new[group]"><option value="">{$modules.Language->getString('no_group')}</option>{foreach $groups as $curGroup}<option value="{$curGroup[0]}"{if $newUser['group'] == $curGroup[0]} selected="selected"{/if}>{$curGroup[1]}</option>{/foreach}</select></td>
 </tr>
 <tr><td colspan="2" class="kat"><span class="kat">{$modules.Language->getString('options')}</span></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="sendRegMail" name="new[send_reg]" value="1"{if $newUser['send_reg']} checked="checked"{/if} /> <label for="sendRegMail" class="norm">{$modules.Language->getString('notify_new_member_per_mail')}</label></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('add_new_member')}" /></p>
</form>