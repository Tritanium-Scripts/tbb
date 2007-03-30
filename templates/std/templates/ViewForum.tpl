<table class="TableNavbar" width="100%">
<tr><td class="CellNavbarBig">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td><span class="FontNavbar">{$PageListing}</span></td>
  <td align="right"><span class="FontNavbar"><a href="{$IndexFile}?Action=Posting&amp;Mode=Topic&amp;ForumID={$ForumID}&amp;{$MySID}"><img src="{$Modules.Template->getTD()}/images/buttons/{$Modules.Language->getLC()}/AddTopic.png" border="0" alt="{$Modules.Language->getString('Post_new_topic')}"/></a></span></td>
 </tr>
 </table>
</td></tr>
</table>
<br />
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="CellTitle" colspan="3" align="center"><span class="FontTitleSmall">{$Modules.Language->getString('Topic')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$Modules.Language->getString('Author')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$Modules.Language->getString('Replies')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$Modules.Language->getString('Views')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$Modules.Language->getString('Last_post')}</span></td>
</tr>
{foreach from=$TopicsData item=curTopic}
 <tr>
  <td class="CellAlt" width="20" align="center"><img src="{$akt_topic_status}" alt="" /></td>
  <td class="CellAlt" width="20" align="center">{$curTopic._TopicPic}</td>
  <td class="CellStd"><span class="FontNorm">{$curTopic._TopicPrefix}</span><span class="topiclink"><a class="topiclink" href="{$IndexFile}?Action=ViewTopic&amp;TopicID={$curTopic.TopicID}&amp;{$MySID}">{$curTopic.TopicTitle}</a></span></td>
  <td class="CellAlt"><span class="FontNorm">{$curTopic._TopicPosterNick}</span></td>
  <td class="CellStd" align="center"><span class="FontSmall">{$curTopic.TopicRepliesCounter}</span></td>
  <td class="CellStd" align="center"><span class="FontSmall">{$curTopic.TopicViewsCounter}</span></td>
  <td class="CellAlt" align="right"><span class="FontSmall">{$curTopic._TopicLastPost}</span></td>
 </tr>
{foreachelse}
 <tr><td class="CellStd" align="center" colspan="7"><span class="FontNorm">{$Modules.Language->getString('No_topics')}</span></td></tr>
{/foreach}
</table>
<br/>
<table class="TableNavbar" width="100%">
<tr><td class="CellNavbarBig">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td><span class="FontNavbar">{$PageListing}</span></td>
  <td align="right"><span class="FontNavbar"><a href="{$IndexFile}?Action=Posting&amp;Mode=Topic&amp;ForumID={$ForumID}&amp;{$MySID}"><img src="{$Modules.Template->getTD()}/images/buttons/{$Modules.Language->getLC()}/AddTopic.png" border="0" alt="{$Modules.Language->getString('Post_new_topic')}"/></a></span></td>
 </tr>
 </table>
</td></tr>
</table>
