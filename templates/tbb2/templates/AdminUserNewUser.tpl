{include file='AdminMenu.tpl'}
<!-- AdminUserNewUser -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=new&amp;create=yes{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('add_new_member')}</span></th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('user_name_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="new[nick]" value="{$newUser['nick']}" style="width:150px;" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('email_address_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="new[email]" value="{$newUser['email']}" style="width:150px;" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('password_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="password" name="new[pw1]" style="width:150px;" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('confirm_password_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="password" name="new[pw2]" style="width:150px;" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('group_colon')}</span></td>
  <td class="cellAlt"><select class="formSelect" name="new[group]"><option value="">{$modules.Language->getString('no_group')}</option>{foreach $groups as $curGroup}<option value="{$curGroup[0]}"{if $newUser['group'] == $curGroup[0]} selected="selected"{/if}>{$curGroup[1]}</option>{/foreach}</select></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('options')}</span></td>
  <td class="cellAlt"><input type="checkbox" id="sendRegMail" name="new[send_reg]" value="1"{if $newUser['send_reg']} checked="checked"{/if} /> <label for="sendRegMail" class="fontNorm">{$modules.Language->getString('notify_new_member_per_mail')}</label></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('add_new_member')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
</form>
{include file='AdminMenuTail.tpl'}