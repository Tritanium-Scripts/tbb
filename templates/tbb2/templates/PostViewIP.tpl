<!-- PostViewIP -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('view_ip_address')}</span></th></tr>
 <tr>
  <td class="cellStd" style="text-align:center;">
   <p class="fontNorm">{$ipAddress|string_format:$modules.Language->getString('view_ip_address_text')}<br />
   <a href="{$smarty.const.INDEXFILE}?faction=viewip&amp;mode=sperren&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;post_id={$postID}{$smarty.const.SID_AMPER}">{$modules.Language->getString('block_ip_address')}</a><br />
   <a href="{$smarty.const.INDEXFILE}?mode=viewthread&amp;forum_id={$forumID}&amp;thread={$topicID}{$smarty.const.SID_AMPER}">{$modules.Language->getString('btt')}</a></p>
  </td>
 </tr>
</table>