<!-- AdminUser -->
{include file='Errors.tpl'}
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('id')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('user_name')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('email_address')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('percent')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_TABLE_HEAD}
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $results as $curResult}
 <tr>
  <td class="td1" align="center"><span class="norm">{$curResult['id']}</span></td>
  <td class="td1"><span class="norm">{$curResult['nick']}</span></td>
  <td class="td1"><span class="norm">{$curResult['mail']}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm">{$curResult['percent']|round}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_TABLE_BODY}
  <td class="td1" style="text-align:center;"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=edit&amp;id={$curResult['id']}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="5" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_search_results_to_display')}</span></td></tr>
{/foreach}
</table>

<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=search&amp;search=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('member_search')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_FORM_START}
 <tr><td class="td1" style="width:20%;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('search_key_colon')}</span></td><td class="td1" style="width:80%;"><input type="radio" id="searchMethodID" name="searchmethod" value="id"{if $searchMethod == 'id'} checked="checked"{/if} /><label for="searchMethodID" class="norm">{Language::getInstance()->getString('user_id')}</label> <input type="radio" id="searchMethodNick" name="searchmethod" value="nick"{if $searchMethod == 'nick'} checked="checked"{/if} /><label for="searchMethodNick" class="norm">{Language::getInstance()->getString('user_name')}</label> <input type="radio" id="searchMethodMail" name="searchmethod" value="email"{if $searchMethod == 'email'} checked="checked"{/if} /><label for="searchMethodMail" class="norm">{Language::getInstance()->getString('email_address')}</label></td></tr>
 <tr><td class="td1" style="width:20%;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('search_for_colon')}</span></td><td class="td1" style="width:80%;"><input type="text" name="searched" value="{$searchFor}" /></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_FORM_END}
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('member_search')}" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_BUTTONS}</p>
</form>