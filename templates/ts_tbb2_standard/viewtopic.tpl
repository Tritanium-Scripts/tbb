<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar2" align="left"><span class="navbar">{$page_listing}</span></td>
 <td class="navbar2" align="right"><span class="navbar"><a href="index.php?faction=postreply&amp;topic_id={$topic_id}&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/{$TCONFIG['images']['post_new_reply']}" border="0" alt="{$LNG['Post_new_reply']}" /></a> <a href="index.php?faction=posttopic&amp;forum_id={$forum_id}&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/{$TCONFIG['images']['post_new_topic']}" border="0" alt="{$LNG['Post_new_topic']}" /></a></span></td>
</tr>
</table>
<br />
{$poll_box}
<table class="tablestd" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
 <td class="celltitle" align="left" width="15%"><span class="fonttitlesmall">{$LNG['Author']}</span></td>
 <td class="celltitle" align="left" width="85%"><span class="fonttitlesmall">{$LNG['Topic']}: {$topic_data['topic_title']}</span></td>
</tr>
<template:postrow>
 <tr>
  <td class="cellalt" width="15%" valign="top" rowspan="3"><span class="fontnorm"><b>{$akt_poster_nick}</b></span><br /><span class="fontsmall">{$akt_poster_rank_text}<br />{$akt_poster_rank_pic}<br />{$akt_poster_id}<br /><br />{$akt_poster_avatar}<br /><br /></span></td>
  <td class="cellalt" width="85%" valign="middle">
   <table border="0" cellspacing="0" cellpadding="0" width="100%">
   <tr>
    <td><if:"{$akt_post_pic} != ''"><span style="margin-right:4px;">{$akt_post_pic}</span></if><span class="fontsmall"><a id="post{$akt_post['post_id']}" name="post{$akt_post['post_id']}"><b>{$akt_post['post_title']}</b></a></span></td>
    <td align="right">{$delete_button} {$edit_button} {$user_email_button} {$user_hp_button} {$quote_button}</td>
   </tr>
   </table>
  </td>
 </tr>
 <tr><td class="cellstd"><span class="fontnorm">{$akt_post['post_text']}</span>
   <template:signature>
   <br /><br /><span class="signature">-----------<br />{$akt_post_signature}</span>
   </template>
   <p><span class="fontsmall">{$akt_edited_text}</span></p>
  </td>
 </tr>
 <tr><td class="cellstd" width="85%"><span class="fontsmall">{$LNG['Posted']}: {$akt_post_date}</span></td></tr>
</template>
</table>
<br />
<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar2" align="left"><span class="navbar">{$page_listing}</span></td>
 <td class="navbar2" align="right"><span class="navbar"><a href="index.php?faction=postreply&amp;topic_id={$topic_id}&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/{$TCONFIG['images']['post_new_reply']}" border="0" alt="{$LNG['Post_new_reply']}" /></a> <a href="index.php?faction=posttopic&amp;forum_id={$forum_id}&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/{$TCONFIG['images']['post_new_topic']}" border="0" alt="{$LNG['Post_new_topic']}" /></a></span></td>
</tr>
</table>
<br />
<template:modtools>
 <table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="navbar" align="center"><span class="navar">{$modtools}</span></td></tr>
 </table>
 <br />
</template>