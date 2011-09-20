<!-- AdminNewsletter -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_newsletter{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('send_newsletter')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:10%;"><span class="norm">{$modules.Language->getString('recipient_colon')}</span></td>
  <td class="td1" style="width:90%;">{html_options name='target' values=array(1, 2, 3) output=array($modules.Language->getString('all_members'), $modules.Language->getString('only_moderators'), $modules.Language->getString('only_administrators'))}</td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:10%;"><span class="norm">{$modules.Language->getString('dispatch_colon')}</span></td>
  <td class="td1" style="width:90%;"><input type="radio" id="perPM" name="sendmethod" value="2" checked="checked" /><label for="perPM" class="norm">{$modules.Language->getString('per_pm')}</label> <input type="radio" id="perMail" name="sendmethod" value="1" /><label for="perMail" class="norm">{$modules.Language->getString('per_email')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:10%;"><span class="norm">{$modules.Language->getString('subject_colon')}</span></td>
  <td class="td1" style="width:90%;"><input type="text" name="betreff" size="30" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:10%;"><span class="norm">{$modules.Language->getString('message_colon')}</span></td>
  <td class="td1" style="width:90%;"><textarea name="newsletter" rows="8" cols="60"></textarea></td>
 </tr>
 <tr>
  <td class="td1" style="width:10%;"></td>
  <td class="td1" style="width:90%;"><input type="checkbox" id="isArchived" name="isArchived" value="true" checked="checked" /> <label for="isArchived" class="norm" style="font-weight:bold;">{$modules.Language->getString('save_in_archive')}</label></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('next')}" /></p>
<input type="hidden" name="mode" value="accept" />
</form>