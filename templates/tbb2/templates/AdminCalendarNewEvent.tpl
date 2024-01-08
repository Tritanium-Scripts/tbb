{include file='AdminMenu.tpl'}
<!-- AdminCalendarNewEvent -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=adminCalendar&amp;mode=new{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('add_new_event')}</span></th></tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('post_icon_colon')}</span></td>
  <td class="cellAlt" style="vertical-align:top;">{include file='TopicSmilies.tpl' checked=$newEventIcon}</td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('name')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="eventName" size="30" value="{$newEventName}" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('start')}</span></td>
  <td class="cellAlt">{{html_select_date prefix='' time=$newEventStartDate start_year='-5' end_year='+5' reverse_years=true field_array='eventStartDate' field_order=Language::getInstance()->getString('DATE_FIELD_ORDER') field_separator=Language::getInstance()->getString('DATE_SEPARATOR')}|utf8_encode}</td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('end')}</span></td>
  <td class="cellAlt">{{html_select_date prefix='' time=$newEventEndDate start_year='-5' end_year='+5' reverse_years=true field_array='eventEndDate' field_order=Language::getInstance()->getString('DATE_FIELD_ORDER') field_separator=Language::getInstance()->getString('DATE_SEPARATOR')}|utf8_encode}</td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('description')}</span></td>
  <td class="cellAlt"><textarea class="formTextArea" name="eventDescription" rows="8" cols="60">{$newEventDescription}</textarea></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('add_new_event')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="create" value="yes" />
</form>
{include file='AdminMenuTail.tpl'}