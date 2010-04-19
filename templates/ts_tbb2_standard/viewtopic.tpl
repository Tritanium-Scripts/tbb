<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar2" align="left"><span class="navbar">{$page_listing}</span></td>
 <td class="navbar2" align="right"><span class="navbar"><a href="index.php?faction=postreply&amp;topic_id={$topic_id}&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/{$TCONFIG['images']['post_new_reply']}" border="0" alt="{$lng['Post_new_reply']}" /></a> <a href="index.php?faction=posttopic&amp;forum_id={$forum_id}&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/{$TCONFIG['images']['post_new_topic']}" border="0" alt="{$lng['Post_new_topic']}" /></a></span></td>
</tr>
</table>
<br />
{$poll_box}
<table class="tbl" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
 <th class="thsmall" align="left" width="15%"><span class="thsmall">{$lng['Author']}</span></th>
 <th class="thsmall" align="left" width="85%"><span class="thsmall">{$lng['Topic']}: {$topic_data['topic_title']}</span></th>
</tr>
<template:postrow>
 <tr>
  <td rowspan="2" class="{$akt_cell_class}" width="15%" valign="top"><span class="norm"><b>{$akt_poster_nick}</b></span><br /><span class="small">{$akt_poster_rank_text}<br />{$akt_poster_rank_pic}<br />{$akt_poster_id}<br /><br />{$akt_poster_avatar}<br /><br /></span></td>
  <td class="{$akt_cell_class}" width="85%" valign="top">
   <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border-bottom:1px black solid; margin-bottom:4px;">
    <tr>
     <td><span style="margin-right:4px;">{$akt_post_pic}</span><span class="small"><a id="post{$akt_post['post_id']}" name="post{$akt_post['post_id']}">{$akt_post['post_title']}</a></span></td>
     <td align="right">{$delete_button} {$edit_button} {$user_email_button} {$user_hp_button} {$quote_button}</td>
    </tr>
   </table>
   <span class="norm">{$akt_post['post_text']}</span>
   <template:signature>
   <br /><br /><span class="signature">-----------<br />{$akt_post_signature}</span>
   </template>
   <p><span class="small">{$akt_edited_text}</span></p>
  </td>
 </tr>
 <tr><td class="{$akt_cell_class}" width="85%"><hr /><span class="small">{$lng['Posted']}: {$akt_post_date}</span></td></tr>
</template>
</table>
<br />
<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar2" align="left"><span class="navbar">{$page_listing}</span></td>
 <td class="navbar2" align="right"><span class="navbar"><a href="index.php?faction=postreply&amp;topic_id={$topic_id}&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/{$TCONFIG['images']['post_new_reply']}" border="0" alt="{$lng['Post_new_reply']}" /></a> <a href="index.php?faction=posttopic&amp;forum_id={$forum_id}&amp;{$MYSID}"><img src="{$TEMPLATE_PATH}/{$TCONFIG['images']['post_new_topic']}" border="0" alt="{$lng['Post_new_topic']}" /></a></span></td>
</tr>
</table>
<br />
<template:modtools>
 <table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="navbar" align="center"><span class="navar">{$modtools}</span></td></tr>
 </table>
 <br />
</template>