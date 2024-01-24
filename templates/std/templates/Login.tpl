<!-- Login -->
{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=login&amp;mode=verify{$smarty.const.SID_AMPER}" method="post">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th colspan="2" class="thnorm" style="text-align:left;"><span class="thnorm">{Language::getInstance()->getString('login')}</span></th></tr>
 <tr><td colspan="2" class="kat"><span class="kat">{Language::getInstance()->getString('login_data')}</span></td></tr>
 <tr>
  <td width="20%" class="td1"><span class="norm">{Language::getInstance()->getString('user_name_colon')}</span></td>
  <td width="80%" class="td1"><input type="text" name="login_name" value="{$loginName}" style="width:150px;" /></td>
 </tr>
 <tr>
  <td width="20%" class="td1"><span class="norm">{Language::getInstance()->getString('password_colon')}</span></td>
  <td width="80%" class="td1"><input type="password" name="login_pw" style="width:150px;" /><span class="small">&nbsp;(<a class="small" href="{$smarty.const.INDEXFILE}?faction=sendpw{if Config::getInstance()->getCfgVal('activate_mail') == 1 && !empty($loginName)}&amp;nick={$loginName|escape:'url'}{/if}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('password_forgotten')}</a>)</span></td>
 </tr>
 <tr><td colspan="2" class="kat"><span class="kat">{Language::getInstance()->getString('options')}</span></td></tr>
 <tr>
  <td colspan="2" class="td1">
   <input type="checkbox" id="stayli" name="stayli" value="yes" onfocus="this.blur();" />&nbsp;<label for="stayli" class="norm">{Language::getInstance()->getString('login_automatically_each_visit')}</label>{if Config::getInstance()->getCfgVal('wio') != 0}<br />
   <input type="checkbox" id="bewio" name="bewio" value="yes" onfocus="this.blur();" />&nbsp;<label for="bewio" class="norm">{Language::getInstance()->getString('hide_from_wiwo')}</label>{/if}
  </td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('login')}" /></p>
</form>