<!-- PostViewIP -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('view_ip_address')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_VIEW_IP_FORM_START}
 <tr>
  <td class="td1" style="text-align:center;">
   <p class="norm">{$ipAddress|string_format:Language::getInstance()->getString('view_ip_address_text')}<br />
   <a href="{$smarty.const.INDEXFILE}?faction=viewip&amp;mode=sperren&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;post_id={$postID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('block_ip_address')}</a><br />
   <a href="{$smarty.const.INDEXFILE}?mode=viewthread&amp;forum_id={$forumID}&amp;thread={$topicID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('btt')}</a></p>
  </td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_VIEW_IP_FORM_END}
</table>