<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar2" align="left"><span class="navbar">{$page_listing}</span></td>
 <td class="navbar2" align="right"><a href="index.php?faction=posttopic&amp;forum_id={$forum_id}&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/{$TCONFIG['images']['post_new_topic']}" border="0" alt="{$LNG['Post_new_topic']}" /></a></td>
</tr>
</table>
<br />
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="celltitle" colspan="3" align="center"><span class="fonttitlesmall">{$LNG['Topic']}</span></td>
 <td class="celltitle" align="center"><span class="fonttitlesmall">{$LNG['Author']}</span></td>
 <td class="celltitle" align="center"><span class="fonttitlesmall">{$LNG['Replies']}</span></td>
 <td class="celltitle" align="center"><span class="fonttitlesmall">{$LNG['Views']}</span></td>
 <td class="celltitle" align="center"><span class="fonttitlesmall">{$LNG['Last_post']}</span></td>
</tr>
<template:topicrow>
 <tr>
  <td class="cellalt" width="20" align="center"><img src="{$akt_topic_status}" alt="" /></td>
  <td class="cellalt" width="20" align="center">{$akt_topic_pic}</td>
  <td class="cellstd"><span class="fontnorm">{$akt_topic_prefix}</span><span class="topiclink"><a class="topiclink" href="index.php?faction=viewtopic&amp;topic_id={$akt_topic_data['topic_id']}&amp;{$MYSID}">{$akt_topic_data['topic_title']}</a></span></td>
  <td class="cellalt"><span class="fontnorm">{$akt_topic_poster_nick}</span></td>
  <td class="cellstd" align="center"><span class="fontsmall">{$akt_topic_data['topic_replies_counter']}</span></td>
  <td class="cellstd" align="center"><span class="fontsmall">{$akt_topic_data['topic_views_counter']}</span></td>
  <td class="cellalt" align="right"><span class="fontsmall">{$topic_last_post}</span></td>
 </tr>
</template>
<template:no_topics>
 <tr><td class="cellstd" align="center" colspan="7"><span class="fontnorm">{$LNG['No_topics']}</span></td></tr>
</template>
</table>
<br />
<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar2" align="left"><span class="navbar">{$page_listing}</span></td>
 <td class="navbar2" align="right"><a href="index.php?faction=posttopic&amp;forum_id={$forum_id}&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/{$TCONFIG['images']['post_new_topic']}" border="0" alt="{$LNG['Post_new_topic']}" /></a></td>
</tr>
</table>