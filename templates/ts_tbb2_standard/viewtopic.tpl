<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar2" align="left"><span class="navbar">{$page_listing}</span></td>
 <td class="navbar2" align="right"><span class="navbar"><a href="index.php?faction=postreply&amp;topic_id={$topic_id}&amp;{$MYSID}"><img src="{$template_path}/{$tpl_config["img_post_new_reply"]}" border="0" alt="{$lng["Post_new_reply"]}" /></a> <a href="index.php?faction=posttopic&amp;forum_id={$forum_id}&amp;{$MYSID}"><img src="{$template_path}/{$tpl_config["img_post_new_topic"]}" border="0" alt="{$lng["Post_new_topic"]}" /></a></span></td>
</tr>
</table>
<br />
{$poll_box}
<table class="tbl" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
 <th class="thsmall" align="left" width="15%"><span class="thsmall">{$lng["Author"]}</span></th>
 <th class="thsmall" align="left" width="85%"><span class="thsmall">{$lng["Topic"]}: {$topic_data["topic_title"]}</span></th>
</tr>
<!-- TPLBLOCK postrow -->
 <tr>
  <td rowspan="2" class="{postrow.$tpl_config["akt_class"]}" width="15%" valign="top"><span class="norm"><b>{postrow.$akt_post["poster_nick"]}</b></span><br /><span class="small"><br /><br />{postrow.$lng["ID"]} # {postrow.$akt_post["poster_id"]}<br /><br /></span></td>
  <td class="{postrow.$tpl_config["akt_class"]}" width="85%" valign="top">
   <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border-bottom:1px black solid; margin-bottom:4px;">
    <tr>
     <td><span style="margin-right:4px;">{postrow.$akt_post_pic}</span><span class="small"><a id="post{postrow.$akt_post["post_id"]}" name="post{postrow.$akt_post["post_id"]}">{postrow.$akt_post["post_title"]}</a></span></td>
     <td align="right">{postrow.$delete_button} {postrow.$edit_button} {postrow.$user_email_button} {postrow.$user_hp_button} {postrow.$quote_button}</td>
    </tr>
   </table>
   <span class="small"></span><span class="norm">{postrow.$akt_post["post_text"]}<br /><br /></span>
  </td>
 </tr>
 <tr><td class="{postrow.$tpl_config["akt_class"]}" width="85%"><hr /><span class="small">{postrow.$lng["Posted"]}: {postrow.$akt_post_date}</span></td></tr>
<!-- /TPLBLOCK postrow -->
</table>
<br />
<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar2" align="left"><span class="navbar">{$page_listing}</span></td>
 <td class="navbar2" align="right"><span class="navbar"><a href="index.php?faction=postreply&amp;topic_id={$topic_id}&amp;{$MYSID}"><img src="{$template_path}/{$tpl_config["img_post_new_reply"]}" border="0" alt="{$lng["Post_new_reply"]}" /></a> <a href="index.php?faction=posttopic&amp;forum_id={$forum_id}&amp;{$MYSID}"><img src="{$template_path}/{$tpl_config["img_post_new_topic"]}" border="0" alt="{$lng["Post_new_topic"]}" /></a></span></td>
</tr>
</table>
<!-- TPLBLOCK modtools -->
 <br />
 <table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="navbar" align="center"><span class="navar">{modtools.$modtools}</span></td></tr>
 </table>
<!-- /TPLBLOCK modtools -->