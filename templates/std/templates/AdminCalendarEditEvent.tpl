<!-- AdminCalendarEditEvent -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=adminCalendar&amp;mode=edit{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('edit_event')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('post_icon_colon')}</span></td>
  <td class="td1" style="vertical-align:top;">{include file='TopicSmilies.tpl' checked=$editEventIcon}</td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('name')}</span></td>
  <td class="td1"><input type="text" name="eventName" value="{$editEventName}" /></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('start')}</span></td>
  <td class="td1">{{html_select_date prefix='' time=$editEventStartDate start_year='-5' end_year='+5' reverse_years=true field_array='eventStartDate' field_order=Language::getInstance()->getString('DATE_FIELD_ORDER') field_separator=Language::getInstance()->getString('DATE_SEPARATOR')}|utf8_encode} {html_select_time prefix='' time=$editEventStartDate field_array='eventStartDate'}</td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('end')}</span></td>
  <td class="td1">{{html_select_date prefix='' time=$editEventEndDate start_year='-5' end_year='+5' reverse_years=true field_array='eventEndDate' field_order=Language::getInstance()->getString('DATE_FIELD_ORDER') field_separator=Language::getInstance()->getString('DATE_SEPARATOR')}|utf8_encode} {html_select_time prefix='' time=$editEventEndDate field_array='eventEndDate'}</td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('description')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='eventDescription'}</td>
  <td class="td1"><textarea id="eventDescription" name="eventDescription" rows="8" cols="60">{$editEventDescription}</textarea></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('edit_event')}" /></p>
<input type="hidden" name="update" value="yes" />
<input type="hidden" name="id" value="{$eventId}" />
</form>