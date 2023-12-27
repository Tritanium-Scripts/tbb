<!-- PostBlockIP -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=viewip{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('block_ip_address')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('ip_address_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$ipAddress}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('blocking_period_colon')}</span></td>
  <td class="td1" style="width:80%;"><select name="spdauer" size="1"><option value="60">{Language::getInstance()->getString('one_hour')}</option><option value="120">{2|string_format:Language::getInstance()->getString('x_hours')}</option><option value="300">{5|string_format:Language::getInstance()->getString('x_hours')}</option><option value="1440">{Language::getInstance()->getString('one_day')}</option><option value="-1">{Language::getInstance()->getString('forever')}</option></select></td>
 </tr>{if Auth::getInstance()->isAdmin()}
 <tr><td class="td1" colspan="2"><input type="checkbox" id="foren" name="foren" value="ja" style="vertical-align:middle;" /> <label for="foren" class="norm">{Language::getInstance()->getString('block_ip_for_entire_board')}</label></td></tr>{/if}
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('block_ip_address')}" /></p>
<input type="hidden" name="mode" value="sperren" />
<input type="hidden" name="forum_id" value="{$forumID}" />
<input type="hidden" name="topic_id" value="{$topicID}" />
<input type="hidden" name="post_id" value="{$postID}" />
<input type="hidden" name="sperren" value="yes" />
</form>