{include file='AdminMenu.tpl'}
<!-- AdminCalendarEditEvent -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=adminCalendar&amp;mode=edit{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('edit_event')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CALENDAR_EDIT_EVENT_FORM_START}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('post_icon_colon')}</span></td>
  <td class="cellAlt" style="vertical-align:top;">{include file='TopicSmilies.tpl' checked=$editEventIcon}</td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('name')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="eventName" size="30" value="{$editEventName}" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('start')}</span></td>
  <td class="cellAlt">{{html_select_date prefix='' time=$editEventStartDate start_year='-5' end_year='+5' reverse_years=true field_array='eventStartDate' field_order=Language::getInstance()->getString('DATE_FIELD_ORDER') field_separator=Language::getInstance()->getString('DATE_SEPARATOR') all_extra='class="formSelect"'}|utf8_encode} {html_select_time prefix='' time=$editEventStartDate field_array='eventStartDate' all_extra='class="formSelect"'}</td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('end')}</span></td>
  <td class="cellAlt">{{html_select_date prefix='' time=$editEventEndDate start_year='-5' end_year='+5' reverse_years=true field_array='eventEndDate' field_order=Language::getInstance()->getString('DATE_FIELD_ORDER') field_separator=Language::getInstance()->getString('DATE_SEPARATOR') all_extra='class="formSelect"'}|utf8_encode} {html_select_time prefix='' time=$editEventEndDate field_array='eventEndDate' all_extra='class="formSelect"'}</td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('description')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='eventDescription'}</td>
  <td class="cellAlt"><textarea class="formTextArea" id="eventDescription" name="eventDescription" rows="8" cols="60">{$editEventDescription}</textarea></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CALENDAR_EDIT_EVENT_FORM_END}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('edit_event')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CALENDAR_EDIT_EVENT_BUTTONS}</p>
<input type="hidden" name="update" value="yes" />
<input type="hidden" name="id" value="{$eventId}" />
</form>
{include file='AdminMenuTail.tpl'}