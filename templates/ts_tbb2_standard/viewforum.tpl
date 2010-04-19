<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar2" align="left"><span class="navbar">{$page_listing}</span></td>
 <td class="navbar2" align="right"><a href="index.php?faction=posttopic&amp;forum_id={$forum_id}&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/{$TCONFIG['images']['post_new_topic']}" border="0" alt="{$lng['Post_new_topic']}" /></a></td>
</tr>
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <th class="thsmall" colspan="3">{$lng['Topic']}</th>
 <th class="thsmall">{$lng['Author']}</th>
 <th class="thsmall">{$lng['Replies']}</th>
 <th class="thsmall">{$lng['Views']}</th>
 <th class="thsmall">{$lng['Last_post']}</th>
</tr>
<template:topicrow>
 <tr>
  <td class="td1" width="20" align="center"><img src="{$akt_topic_status}" alt="" /></td>
  <td class="td2" width="20" align="center">{$akt_topic_pic}</td>
  <td class="td1"><span class="norm">{$akt_topic_prefix}</span><span class="topiclink"><a class="topiclink" href="index.php?faction=viewtopic&amp;topic_id={$akt_topic_data['topic_id']}&amp;{$MYSID}">{$akt_topic_data['topic_title']}</a></span></td>
  <td class="td2"><span class="norm">{$akt_topic_poster_nick}</span></td>
  <td class="td1" align="center"><span class="small">{$akt_topic_data['topic_replies_counter']}</span></td>
  <td class="td2" align="center"><span class="small">{$akt_topic_data['topic_views_counter']}</span></td>
  <td class="td1" align="right"><span class="small">{$topic_last_post}</span></td>
 </tr>
</template>
<template:no_topics>
 <tr><td colspan="7" class="td1"><center><span class="norm">{$lng['No_topics']}</span></center></td></tr>
</template>
</table>
<br />
<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar2" align="left"><span class="navbar">{$page_listing}</span></td>
 <td class="navbar2" align="right"><a href="index.php?faction=posttopic&amp;forum_id={$forum_id}&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/{$TCONFIG['images']['post_new_topic']}" border="0" alt="{$lng['Post_new_topic']}" /></a></td>
</tr>
</table>