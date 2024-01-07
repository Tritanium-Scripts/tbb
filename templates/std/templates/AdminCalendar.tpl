<!-- AdminCalendar -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall" colspan="2"><span class="thsmall">{Language::getInstance()->getString('event')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('date')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $events as $curEvent}
 <tr>
  <td class="td1"><span class="norm"><img src="{$curEvent[2]}" alt="" /></span></td>
  <td class="td2"><span class="norm">{$curEvent[5]|escape}</span></td>
  <td class="td1"><span class="norm">{$curEvent[3]}-{$curEvent[4]}</span></td>
  <td class="td2" style="text-align:center;"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=adminCalendar&amp;mode=delete&amp;id={$curEvent[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('delete')}</a>&nbsp;|&nbsp;<a class="norm" href="{$smarty.const.INDEXFILE}?faction=adminCalendar&amp;mode=edit&amp;id={$curEvent[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="4" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_events_available')}</span></td></tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=adminCalendar&amp;mode=new{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_event')}</a></p>