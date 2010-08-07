<!-- AdminUser -->
{include file='Errors.tpl'}
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('id')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('user_name')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('email_address')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('percent')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $results as $curResult}
 <tr>
  <td class="td1" align="center"><span class="norm">{$curResult['id']}</span></td>
  <td class="td1"><span class="norm">{$curResult['nick']}</span></td>
  <td class="td1"><span class="norm">{$curResult['mail']}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm">{$curResult['percent']|round}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=edit&amp;id={$curResult['id']}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="5" style="text-align:center;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('no_search_results_to_display')}</span></td></tr>
{/foreach}
</table>

<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=search&amp;search=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('member_search')}</span></th></tr>
 <tr><td class="td1" style="width:20%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('search_key_colon')}</span></td><td class="td1" style="width:80%;"><input type="radio" id="searchMethodID" name="searchmethod" value="id"{if $searchMethod == 'id'} checked="checked"{/if} /><label for="searchMethodID" class="norm">{$modules.Language->getString('user_id')}</label> <input type="radio" id="searchMethodNick" name="searchmethod" value="nick"{if $searchMethod == 'nick'} checked="checked"{/if} /><label for="searchMethodNick" class="norm">{$modules.Language->getString('user_name')}</label> <input type="radio" id="searchMethodMail" name="searchmethod" value="email"{if $searchMethod == 'email'} checked="checked"{/if} /><label for="searchMethodMail" class="norm">{$modules.Language->getString('email_address')}</label></td></tr>
 <tr><td class="td1" style="width:20%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('search_for_colon')}</span></td><td class="td1" style="width:80%;"><input type="text" name="searched" value="{$searchFor}"></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('member_search')}" /></p>
</form>