{include file='AdminMenu.tpl'}
<!-- AdminCalendar -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="5"><span class="fontTitle">{Language::getInstance()->getString('manage_calendar')}</span></th></tr>
 <tr>
  <th class="cellCat" colspan="2" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('event')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('start')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('end')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CALENDAR_EVENTS_TABLE_HEAD}
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $events as $curEvent}
 <tr>
  <td class="cellStd"><span class="fontNorm"><img src="{$curEvent[2]}" alt="" /></span></td>
  <td class="cellAlt"><span class="fontNorm">{$curEvent[5]}</span></td>
  <td class="cellStd"><span class="fontNorm">{$curEvent[3]|date_format:Language::getInstance()->getString('DATEFORMAT')|utf8_encode}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curEvent[4]|date_format:Language::getInstance()->getString('DATEFORMAT')|utf8_encode}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CALENDAR_EVENTS_TABLE_BODY}
  <td class="cellStd" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=adminCalendar&amp;mode=delete&amp;id={$curEvent[0]}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/calendar_delete.png" alt="{Language::getInstance()->getString('delete')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=adminCalendar&amp;mode=edit&amp;id={$curEvent[0]}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/calendar_edit.png" alt="{Language::getInstance()->getString('edit')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="5" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_events_available')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=adminCalendar&amp;mode=new{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/calendar_add.png" alt="{Language::getInstance()->getString('add_new_event')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('add_new_event')}</a></span></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CALENDAR_EVENTS_OPTIONS}
</table>
{include file='AdminMenuTail.tpl'}