{include file='AdminMenu.tpl'}
<!-- AdminNewsletter -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_newsletter{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="10%" />
  <col width="90%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('send_newsletter')}</span></th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('recipient_colon')}</span></td>
  <td class="cellAlt"><select class="formSelect" name="target">{html_options values=array(1, 2, 3) output=array(Language::getInstance()->getString('all_members'), Language::getInstance()->getString('only_moderators'), Language::getInstance()->getString('only_administrators'))}</select></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('dispatch_colon')}</span></td>
  <td class="cellAlt"><input type="radio" id="perPM" name="sendmethod" value="2" checked="checked" /><label for="perPM" class="fontNorm">{Language::getInstance()->getString('per_pm')}</label> <input type="radio" id="perMail" name="sendmethod" value="1" /><label for="perMail" class="fontNorm">{Language::getInstance()->getString('per_email')}</label></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('subject_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="betreff" size="30" /></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('message_colon')}</span></td>
  <td class="cellAlt"><textarea class="formTextArea" name="newsletter" rows="8" cols="60"></textarea></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('options_colon')}</span></td>
  <td class="cellAlt"><input type="checkbox" id="isArchived" name="isArchived" value="true" checked="checked" /> <label for="isArchived" class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('save_in_archive')}</label></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('next')}" /></p>
<input type="hidden" name="mode" value="accept" />
</form>
{include file='AdminMenuTail.tpl'}