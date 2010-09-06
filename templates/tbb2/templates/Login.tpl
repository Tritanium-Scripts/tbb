<!-- Login -->
{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=login&amp;mode=verify{$smarty.const.SID_AMPER}" method="post">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th colspan="2" class="cellTitle"><span class="fontTitle">{$modules.Language->getString('login')}</span></th></tr>
 <tr><td colspan="2" class="cellCat"><span class="fontCat">{$modules.Language->getString('login_data')}</span></td></tr>
 <tr>
  <td width="20%" class="cellStd"><span class="fontNorm">{$modules.Language->getString('user_name_colon')}</span></td>
  <td width="80%" class="cellAlt"><input class="formText" type="text" name="login_name" value="{$loginName}" style="width:150px;" /><span class="fontSmall">&nbsp;(<a href="{$smarty.const.INDEXFILE}?faction=register{$smarty.const.SID_AMPER}">{$modules.Language->getString('register')}</a>)</span></td>
 </tr>
 <tr>
  <td width="20%" class="cellStd"><span class="fontNorm">{$modules.Language->getString('password_colon')}</span></td>
  <td width="80%" class="cellAlt"><input class="formText" type="password" name="login_pw" style="width:150px;" /><span class="fontSmall">&nbsp;(<a href="{$smarty.const.INDEXFILE}?faction=sendpw{if $modules.Config->getCfgVal('activate_mail') == 1 && !empty($loginName)}&amp;nick={$loginName|escape:'url'}{/if}{$smarty.const.SID_AMPER}">{$modules.Language->getString('password_forgotten')}</a>)</span></td>
 </tr>
 <tr><td colspan="2" class="cellCat"><span class="fontCat">{$modules.Language->getString('options')}</span></td></tr>
 <tr>
  <td colspan="2" class="cellStd">
   <input type="checkbox" id="stayli" name="stayli" value="yes" onfocus="this.blur();" />&nbsp;<label for="stayli" class="fontNorm">{$modules.Language->getString('login_automatically_each_visit')}</label>{if $modules.Config->getCfgVal('wio') == 1}<br />
   <input type="checkbox" id="bewio" name="bewio" value="yes" onfocus="this.blur();" />&nbsp;<label for="bewio" class="fontNorm">{$modules.Language->getString('hide_from_wiwo')}</label>{/if}
  </td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('login')}" /></p>
</form>