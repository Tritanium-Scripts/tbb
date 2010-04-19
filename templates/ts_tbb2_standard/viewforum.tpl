<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar" align="left"><span class="navbar">{PAGE_LISTING}</span></td>
 <td class="navbar" align="right"><span class="navbar"><a href="index.php?faction=viewforum&amp;forum_id={FORUM_ID}&amp;mark=all&amp;{MYSID}">{LNG_MARK_TOPICS_READ}</a> | <a href="index.php?faction=posttopic&amp;forum_id={FORUM_ID}&amp;{MYSID}">{LNG_POST_NEW_TOPIC}</a></span></td>
</tr>
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <th class="thsmall" colspan="3">{LNG_TOPIC}</th>
 <th class="thsmall">{LNG_AUTHOR}</th>
 <th class="thsmall">{LNG_REPLIES}</th>
 <th class="thsmall">{LNG_VIEWS}</th>
 <th class="thsmall">{LNG_LAST_POST}</th>
</tr>
<!-- TPLBLOCK topicrow -->
 <tr>
  <td class="td1" width="20" align="center">{topicrow.TOPIC_STATUS}</td>
  <td class="td2" width="20" align="center">{topicrow.TOPIC_PIC}</td>
  <td class="td1"><span class="norm">{topicrow.TOPIC_PREFIX}</span><span class="topiclink"><a class="topiclink" href="index.php?faction=viewtopic&amp;topic_id={topicrow.TOPIC_ID}&amp;{topicrow.MYSID}">{topicrow.TOPIC_TITLE}</a></span></td>
  <td class="td2"><span class="norm">{topicrow.TOPIC_POSTER}</span></td>
  <td class="td1" align="center"><span class="small">{topicrow.TOPIC_REPLIES_COUNTER}</span></td>
  <td class="td2" align="center"><span class="small">{topicrow.TOPIC_VIEWS_COUNTER}</span></td>
  <td class="td1" align="right"><span class="small">{topicrow.TOPIC_LAST_POST}</span></td>
 </tr>
<!-- /TPLBLOCK topicrow -->
<!-- TPLBLOCK no_topics -->
 <tr><td colspan="7" class="td1"><center><span class="norm">{no_topics.LNG_NO_TOPICS}</span></center></td></tr>
<!-- /TPLBLOCK no_topics -->
</table>
<br />
<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar" align="left"><span class="navbar">{PAGE_LISTING}</span></td>
 <td class="navbar" align="right"><span class="navbar"><a href="index.php?faction=viewforum&amp;forum_id={FORUM_ID}&amp;mark=all&amp;{MYSID}">{LNG_MARK_TOPICS_READ}</a> | <a href="index.php?faction=posttopic&amp;forum_id={FORUM_ID}&amp;{MYSID}">{LNG_POST_NEW_TOPIC}</a></span></td>
</tr>
</table>