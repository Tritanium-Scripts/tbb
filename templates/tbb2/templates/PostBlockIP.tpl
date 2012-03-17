<!-- PostBlockIP -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=viewip{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('block_ip_address')}</span></th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('ip_address_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$ipAddress}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('blocking_period_colon')}</span></td>
  <td class="cellAlt"><select class="formSelect" name="spdauer" size="1"><option value="60">{$modules.Language->getString('one_hour')}</option><option value="120">{2|string_format:$modules.Language->getString('x_hours')}</option><option value="300">{5|string_format:$modules.Language->getString('x_hours')}</option><option value="1440">{$modules.Language->getString('one_day')}</option><option value="-1">{$modules.Language->getString('forever')}</option></select></td>
 </tr>{if $modules.Auth->isAdmin()}
 <tr><td class="cellAlt" colspan="2"><input type="checkbox" id="foren" name="foren" value="ja" style="vertical-align:middle;" /> <label for="foren" class="fontNorm">{$modules.Language->getString('block_ip_for_entire_board')}</label></td></tr>{/if}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('block_ip_address')}" /></p>
<input type="hidden" name="mode" value="sperren" />
<input type="hidden" name="forum_id" value="{$forumID}" />
<input type="hidden" name="topic_id" value="{$topicID}" />
<input type="hidden" name="post_id" value="{$postID}" />
<input type="hidden" name="sperren" value="yes" />
</form>