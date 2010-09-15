{include file='AdminMenu.tpl'}
<!-- AdminUser -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=search&amp;search=yes{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('member_search')}</span></th></tr>
 <tr><td class="cellStd" style="width:20%;"><span class="fontNorm">{$modules.Language->getString('search_key_colon')}</span></td><td class="cellAlt" style="width:80%;"><input type="radio" id="searchMethodID" name="searchmethod" value="id"{if $searchMethod == 'id'} checked="checked"{/if} /><label for="searchMethodID" class="fontNorm">{$modules.Language->getString('user_id')}</label> <input type="radio" id="searchMethodNick" name="searchmethod" value="nick"{if $searchMethod == 'nick'} checked="checked"{/if} /><label for="searchMethodNick" class="fontNorm">{$modules.Language->getString('user_name')}</label> <input type="radio" id="searchMethodMail" name="searchmethod" value="email"{if $searchMethod == 'email'} checked="checked"{/if} /><label for="searchMethodMail" class="fontNorm">{$modules.Language->getString('email_address')}</label></td></tr>
 <tr><td class="cellStd" style="width:20%;"><span class="fontNorm">{$modules.Language->getString('search_for_colon')}</span></td><td class="cellAlt" style="width:80%;"><input class="formText" type="text" name="searched" value="{$searchFor}" /></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('member_search')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
</form>

<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="5"><span class="fontTitle">{$modules.Language->getString('search_results', 'Search')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('id')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('user_name')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('email_address')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('percent')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $results as $curResult}
 <tr>
  <td class="cellStd" align="center"><span class="fontNorm">{$curResult['id']}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curResult['nick']}</span></td>
  <td class="cellStd"><span class="fontNorm">{$curResult['mail']}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontNorm">{$curResult['percent']|round}</span></td>
  <td class="cellStd" style="text-align:right;"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=edit&amp;id={$curResult['id']}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="5" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('no_search_results_to_display')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=new{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/user_add.png" alt="" class="imageIcon" /> {$modules.Language->getString('add_new_member')}</a></span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}