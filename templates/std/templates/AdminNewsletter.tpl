<!-- AdminNewsletter -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_newsletter{$smarty.const.SID_AMPER}">
<input type="hidden" name="mode" value="accept" />
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('send_newsletter')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{$modules.Language->getString('recipient_colon')}</span></td>
  <td class="td1"><select name="target"><option value="1">{$modules.Language->getString('all_members')}</option><option value="2">{$modules.Language->getString('only_moderators')}</option><option value="3">{$modules.Language->getString('only_administrators')}</option></select></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{$modules.Language->getString('dispatch_colon')}</span></td>
  <td class="td1"><input type="radio" id="perPM" name="sendmethod" value="2" checked="checked" /><label for="perPM" class="norm">{$modules.Language->getString('per_pm')}</label> <input type="radio" id="perMail" name="sendmethod" value="1" /><label for="perMail" class="norm">{$modules.Language->getString('per_email')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{$modules.Language->getString('subject_colon')}</span></td>
  <td class="td1"><input type="text" name="betreff" size="30" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top;"><span class="norm">{$modules.Language->getString('message_colon')}</span></td>
  <td class="td1"><textarea name="newsletter" rows="8" cols="60"></textarea></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('next')}" /></p>
</form>