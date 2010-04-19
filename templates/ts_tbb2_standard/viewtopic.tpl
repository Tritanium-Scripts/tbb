<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar" align="left"><span class="navbar">{PAGE_LISTING}</span></td>
 <td class="navbar" align="right"><span class="navbar"><a href="index.php?faction=postreply&amp;topic_id={TOPIC_ID}&amp;{MYSID}">{LNG_POST_NEW_REPLY}</a> | <a href="index.php?faction=posttopic&amp;forum_id={FORUM_ID}&amp;{MYSID}">{LNG_POST_NEW_TOPIC}</a></span></td>
</tr>
</table>
<br />
<table class="tbl" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
 <th class="thsmall" align="left" width="15%"><span class="thsmall">{LNG_AUTHOR}</span></th>
 <th class="thsmall" align="left" width="85%"><span class="thsmall">{LNG_TOPIC}: {TOPIC_TITLE}</span></th>
</tr>
<!-- TPLBLOCK postrow -->
 <tr>
  <td rowspan="2" class="{postrow.AKT_CLASS}" width="15%" valign="top"><span class="norm"><b>{postrow.POSTER_NICK}</b></span><br /><span class="small">{postrow.POSTER_STATUS}<br />{postrow.POSTER_GROUP_NAME}{postrow.POSTER_RANK_PIC}<br />{postrow.POSTER_ID}<br /><br />{postrow.POSTER_AVATAR}{postrow.POSTER_ICQ}</span></td>
  <td class="{postrow.AKT_CLASS}" width="85%" valign="top"><span class="small"><a id="post{postrow.POST_ID}" name="post{postrow.POST_ID}">{postrow.POST_TITLE}</a><!--<img src="{postrow.POST_PIC}" alt="postpic" />-->&nbsp;<img src="{postrow.CONFIG_TEMPLATE_PATH}/images/sep.gif" alt="" />&nbsp;{postrow.POST_TOOLS}</span><hr /><span class="norm">{postrow.POST}<br /><br />{postrow.POSTER_SIGNATUR}</span></td>
 </tr>
 <tr><td class="{postrow.AKT_CLASS}" width="85%"><hr /><span class="small">{postrow.LNG_POSTED}: {postrow.POST_DATE}{postrow.POSTER_POSTS}{postrow.POSTER_REGDATE}{postrow.POSTER_IP}</span></td></tr>
<!-- /TPLBLOCK postrow -->
</table>
<br />
<table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="navbar" align="left"><span class="navbar">{PAGE_LISTING}</span></td>
 <td class="navbar" align="right"><span class="navbar"><a href="index.php?faction=postreply&amp;topic_id={TOPIC_ID}&amp;{MYSID}">{LNG_POST_NEW_REPLY}</a> | <a href="index.php?faction=posttopic&amp;forum_id={FORUM_ID}&amp;{MYSID}">{LNG_POST_NEW_TOPIC}</a></span></td>
</tr>
</table>
<!-- TPLBLOCK modtools -->
 <br />
 <table class="navbar" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="navbar" align="center"><span class="navar">{modtools.MODTOOLS}</span></td></tr>
 </table>
<!-- /TPLBLOCK modtools -->