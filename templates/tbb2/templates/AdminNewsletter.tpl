{include file='AdminMenu.tpl'}
<!-- AdminNewsletter -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_newsletter{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="10%" />
  <col width="90%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('send_newsletter')}</span></th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('recipient_colon')}</span></td>
  <td class="cellAlt"><select class="formSelect" name="target">{html_options values=array(1, 2, 3) output=array($modules.Language->getString('all_members'), $modules.Language->getString('only_moderators'), $modules.Language->getString('only_administrators'))}</select></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('dispatch_colon')}</span></td>
  <td class="cellAlt"><input type="radio" id="perPM" name="sendmethod" value="2" checked="checked" /><label for="perPM" class="fontNorm">{$modules.Language->getString('per_pm')}</label> <input type="radio" id="perMail" name="sendmethod" value="1" /><label for="perMail" class="fontNorm">{$modules.Language->getString('per_email')}</label></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('subject_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="betreff" size="30" /></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('message_colon')}</span></td>
  <td class="cellAlt"><textarea class="formTextArea" name="newsletter" rows="8" cols="60"></textarea></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('options_colon')}</span></td>
  <td class="cellAlt"><input type="checkbox" id="isArchived" name="isArchived" value="true" checked="checked" /> <label for="isArchived" class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('save_in_archive')}</label></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('next')}" /></p>
<input type="hidden" name="mode" value="accept" />
</form>
{include file='AdminMenuTail.tpl'}