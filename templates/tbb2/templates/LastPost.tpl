<table cellpadding="2" cellspacing="0" style="width:100%;">
 <tr>
  <td style="text-align:center; width:10%;"><img src="{$tSmileyURL}" alt="" /></td>
  <td style="text-align:left; width:90%;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?mode=viewthread&amp;forum_id={$forumID}&amp;thread={$topicID}&amp;z=last{$smarty.const.SID_AMPER}">{$topicTitle}</a> {$user|string_format:$modules.Language->getString('by_x')}<br />{$date|string_format:$modules.Language->getString('on_x')}</span></td>
 </tr>
</table>