<!-- Login -->
{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=login&amp;mode=verify{$smarty.const.SID_AMPER}" method="post">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th colspan="2" class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('login')}</span></th></tr>
 <tr><td colspan="2" class="cellCat"><span class="fontCat">{Language::getInstance()->getString('login_data')}</span></td></tr>
 <tr>
  <td width="20%" class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('user_name_colon')}</span></td>
  <td width="80%" class="cellAlt"><input class="formText" type="text" name="login_name" value="{$loginName}" style="width:150px;" /><span class="fontSmall">&nbsp;(<a href="{$smarty.const.INDEXFILE}?faction=register{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('register')}</a>)</span></td>
 </tr>
 <tr>
  <td width="20%" class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('password_colon')}</span></td>
  <td width="80%" class="cellAlt"><input class="formText" type="password" name="login_pw" style="width:150px;" /><span class="fontSmall">&nbsp;(<a href="{$smarty.const.INDEXFILE}?faction=sendpw{if Config::getInstance()->getCfgVal('activate_mail') == 1 && !empty($loginName)}&amp;nick={$loginName|escape:'url'}{/if}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('password_forgotten')}</a>)</span></td>
 </tr>
 <tr><td colspan="2" class="cellCat"><span class="fontCat">{Language::getInstance()->getString('options')}</span></td></tr>
 <tr><td colspan="2" class="cellStd"><input type="checkbox" id="stayli" name="stayli" value="yes" />&nbsp;<label for="stayli" class="fontNorm">{Language::getInstance()->getString('login_automatically_each_visit')}</label></td></tr>{if Config::getInstance()->getCfgVal('wio') != 0}
 <tr><td colspan="2" class="cellStd"><input type="checkbox" id="bewio" name="bewio" value="yes" />&nbsp;<label for="bewio" class="fontNorm">{Language::getInstance()->getString('hide_from_wiwo')}</label></td></tr>{/if}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('login')}" /></p>
</form>