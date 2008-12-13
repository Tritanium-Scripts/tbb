<table class="TableNavbar" width="100%">
<tr><td class="CellNavbarBig">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td><span class="FontNavbar">{$pageListing}</span></td>
  <td align="right"><span class="FontNavbar"><a href="{$smarty.const.INDEXFILE}?action=Posting&amp;mode=Topic&amp;forumID={$forumID}&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/{$modules.Language->getLS()}/AddTopic.png" alt="{$modules.Language->getString('post_new_topic')}"/></a></span></td>
 </tr>
 </table>
</td></tr>
</table>
<br />
<table class="TableStd" width="100%">
<tr>
 <td class="CellTitle" colspan="3" align="center"><span class="FontTitleSmall">{$modules.Language->getString('topic')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('author')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('replies')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('views')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('last_post')}</span></td>
</tr>
{foreach from=$topicsData item=curTopic}
 {if $curTopic.topicMovedID != 0}
   <tr onmouseover="setRowCellsClass(this,'CellHighlight');" onmouseout="restoreRowCellsClass(this);">
   <td class="CellAlt" width="20" align="center">&nbsp;</td>
   <td class="CellAlt" width="20" align="center">&nbsp;</td>
   <td class="CellStd"><span class="FontNorm">{$curTopic._topicPrefix}</span><span class="topiclink"><a class="topiclink" href="{$smarty.const.INDEXFILE}?action=ViewTopic&amp;topicID={$curTopic.topicID}&amp;{$smarty.const.MYSID}">{$curTopic.topicTitle}</a></span></td>
   <td class="CellAlt"><span class="FontNorm">{$curTopic._topicPosterNick}</span></td>
   <td class="CellStd" align="center"><span class="FontSmall">-</span></td>
   <td class="CellStd" align="center"><span class="FontSmall">-</span></td>
   <td class="CellAlt" align="right"><span class="FontSmall">-<br/>-</span></td>
  </tr>
 {else}
  <tr onmouseover="setRowCellsClass(this,'CellHighlight');" onmouseout="restoreRowCellsClass(this);">
   <td class="CellAlt" width="20" align="center"><img src="{$modules.Template->getTD()}/images/topic_{if $curTopic._newPostsAvailable == 1}on{else}off{/if}_{if $curTopic.topicIsClosed == 1}closed{else}open{if $curTopic._topicIsHot}_hot{/if}{/if}.gif" alt=""/></td>
   <td class="CellAlt" width="20" align="center">{if $curTopic._topicPic != ''}{$curTopic._topicPic}{else}&nbsp;{/if}</td>
   <td class="CellStd"><span class="FontNorm">{$curTopic._topicPrefix}</span><span class="topiclink"><a class="topiclink" href="{$smarty.const.INDEXFILE}?action=ViewTopic&amp;topicID={$curTopic.topicID}&amp;{$smarty.const.MYSID}">{$curTopic.topicTitle}</a></span></td>
   <td class="CellAlt"><span class="FontNorm">{$curTopic._topicPosterNick}</span></td>
   <td class="CellStd" align="center"><span class="FontSmall">{$curTopic.topicRepliesCounter}</span></td>
   <td class="CellStd" align="center"><span class="FontSmall">{$curTopic.topicViewsCounter}</span></td>
   <td class="CellAlt" align="right"><span class="FontSmall">{$curTopic._topicLastPost}</span></td>
  </tr>
 {/if}
{foreachelse}
 <tr><td class="CellStd" align="center" colspan="7"><span class="FontNorm">{$modules.Language->getString('no_topics')}</span></td></tr>
{/foreach}
</table>
<br/>
<table class="TableNavbar" width="100%">
<tr><td class="CellNavbarBig">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td><span class="FontNavbar">{$pageListing}</span></td>
  <td align="right"><span class="FontNavbar"><a href="{$smarty.const.INDEXFILE}?action=Posting&amp;mode=Topic&amp;forumID={$forumID}&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/buttons/{$modules.Language->getLS()}/AddTopic.png" alt="{$modules.Language->getString('post_new_topic')}"/></a></span></td>
 </tr>
 </table>
</td></tr>
</table>
