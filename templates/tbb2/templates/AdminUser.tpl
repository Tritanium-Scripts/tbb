{include file='AdminMenu.tpl'}
<!-- AdminUser -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=search&amp;search=yes{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('member_search')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_FORM_START}
 <tr><td class="cellStd" style="width:20%;"><span class="fontNorm">{Language::getInstance()->getString('search_key_colon')}</span></td><td class="cellAlt" style="width:80%;"><input type="radio" id="searchMethodID" name="searchmethod" value="id"{if $searchMethod == 'id'} checked="checked"{/if} /><label for="searchMethodID" class="fontNorm">{Language::getInstance()->getString('user_id')}</label> <input type="radio" id="searchMethodNick" name="searchmethod" value="nick"{if $searchMethod == 'nick'} checked="checked"{/if} /><label for="searchMethodNick" class="fontNorm">{Language::getInstance()->getString('user_name')}</label> <input type="radio" id="searchMethodMail" name="searchmethod" value="email"{if $searchMethod == 'email'} checked="checked"{/if} /><label for="searchMethodMail" class="fontNorm">{Language::getInstance()->getString('email_address')}</label></td></tr>
 <tr><td class="cellStd" style="width:20%;"><span class="fontNorm">{Language::getInstance()->getString('search_for_colon')}</span></td><td class="cellAlt" style="width:80%;"><input class="formText" type="text" name="searched" value="{$searchFor}" /></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_FORM_END}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('member_search')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_BUTTONS}</p>
</form>

<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="5"><span class="fontTitle">{Language::getInstance()->getString('search_results', 'Search')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('id')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('user_name')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('email_address')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('percent')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_TABLE_HEAD}
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $results as $curResult}
 <tr>
  <td class="cellStd" align="center"><span class="fontNorm">{$curResult['id']}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curResult['nick']}</span></td>
  <td class="cellStd"><span class="fontNorm">{$curResult['mail']}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontNorm">{$curResult['percent']|round}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_TABLE_BODY}
  <td class="cellStd" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=edit&amp;id={$curResult['id']}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/user_edit.png" alt="{Language::getInstance()->getString('edit')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="5" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_search_results_to_display')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=new{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/user_add.png" alt="{Language::getInstance()->getString('add_new_member')}" class="imageIcon" /> {Language::getInstance()->getString('add_new_member')}</a>{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_USER_OPTIONS}</span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}