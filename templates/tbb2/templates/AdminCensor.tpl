{include file='AdminMenu.tpl'}
<!-- AdminCensor -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="3"><span class="fontTitle">{Language::getInstance()->getString('manage_censorships')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('word')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('replacement')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CENSOR_CENSORSHIPS_TABLE_HEAD}
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $censorships as $curCensorship}
 <tr>
  <td class="cellStd"><span class="fontNorm">{$curCensorship[1]}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curCensorship[2]}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CENSOR_CENSORSHIPS_TABLE_BODY}
  <td class="cellStd" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=kill&amp;id={$curCensorship[0]}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/censor_delete.png" alt="{Language::getInstance()->getString('delete')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=edit&amp;id={$curCensorship[0]}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/censor_edit.png" alt="{Language::getInstance()->getString('edit')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="3" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_censorships')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=new{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/censor_add.png" alt="{Language::getInstance()->getString('add_new_censorship')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('add_new_censorship')}</a></span></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CENSOR_CENSORSHIPS_OPTIONS}
</table>
{include file='AdminMenuTail.tpl'}