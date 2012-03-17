<!-- PostBlockIP -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=viewip{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('block_ip_address')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('ip_address_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$ipAddress}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('blocking_period_colon')}</span></td>
  <td class="td1" style="width:80%;"><select name="spdauer" size="1"><option value="60">{$modules.Language->getString('one_hour')}</option><option value="120">{2|string_format:$modules.Language->getString('x_hours')}</option><option value="300">{5|string_format:$modules.Language->getString('x_hours')}</option><option value="1440">{$modules.Language->getString('one_day')}</option><option value="-1">{$modules.Language->getString('forever')}</option></select></td>
 </tr>{if $modules.Auth->isAdmin()}
 <tr><td class="td1" colspan="2"><input type="checkbox" id="foren" name="foren" value="ja" style="vertical-align:middle;" /> <label for="foren" class="norm">{$modules.Language->getString('block_ip_for_entire_board')}</label></td></tr>{/if}
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('block_ip_address')}" /></p>
<input type="hidden" name="mode" value="sperren" />
<input type="hidden" name="forum_id" value="{$forumID}" />
<input type="hidden" name="topic_id" value="{$topicID}" />
<input type="hidden" name="post_id" value="{$postID}" />
<input type="hidden" name="sperren" value="yes" />
</form>