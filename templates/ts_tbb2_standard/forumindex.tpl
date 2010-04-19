<template:newsbox>
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle"><span class="fonttitle">{$LNG['Latest_news']}</span></td></tr>
<tr><td class="cellstd"><span class="fontnorm"><b>{$news_data['news_title']}</b><br /><br />{$news_data['news_text']}</span><br /><br /><span class="fontsmall">{$news_comments_link}</span></td></tr>
</table>
<br />
</template>
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="celltitle">&nbsp;</td>
 <td class="celltitle" align="center"><span class="fonttitlesmall">{$LNG['Forum']}</span></td>
 <td class="celltitle" align="center"><span class="fonttitlesmall">{$LNG['Topics']}</span></td>
 <td class="celltitle" align="center"><span class="fonttitlesmall">{$LNG['Posts']}</span></td>
 <td class="celltitle" align="center"><span class="fonttitlesmall">{$LNG['Last_post']}</span></td>
 <td class="celltitle" align="center"><span class="fonttitlesmall">{$LNG['Moderators']}</span></td>
</tr>
<template:catrow>
 <tr>
  <td class="cellcat" colspan="6">{$akt_appendix}{$akt_plus_minus_pic}&nbsp;<span class="fontcat"><a class="fontcat" href="index.php?faction=viewcat&amp;cat_id={$akt_cat['cat_id']}&amp;{$MYSID}">{$akt_cat['cat_name']}</a></span></td>
 </tr>
 <template:forumrow>
  <tr>
   <td class="cellalt" align="center">{$akt_appendix}{$akt_new_post_status}</td>
   <td class="cellstd" width="50%">
    <table border="0" cellspacing="0" cellpadding="0">
     <tr><td><span class="forumlink"><a class="forumlink" href="index.php?faction=viewforum&amp;forum_id={$akt_forum['forum_id']}&amp;{$MYSID}">{$akt_forum['forum_name']}</a></span></td></tr>
     <tr><td><span class="fontsmall">{$akt_forum['forum_description']}</span></td></tr>
    </table>
   </td>
   <td class="cellalt" align="center"><span class="fontsmall">{$akt_forum['forum_topics_counter']}</span></td>
   <td class="cellalt" align="center"><span class="fontsmall">{$akt_forum['forum_posts_counter']}</span></td>
   <td class="cellstd">
    <table border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td align="center">{$akt_last_post_pic}</td>
     <td align="left"><span class="fontsmall">{$akt_last_post_text}</span></td>
    </tr>
    </table>
   </td>
   <td class="cellalt" align="center"><span class="fontsmall">{$akt_forum_mods}</span></td>
  </tr>
 </template>
</template>
</table>
<template:boardstatsbox>
 <br />
 <table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="celltitle"><span class="fonttitle">{$LNG['Board_statistics']}</span></td></tr>
 <tr><td class="cellstd"><span class="fontsmall">{$board_stats_text}</span></td></tr>
 </table>
</template>
<template:wiobox>
 <br />
 <table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="celltitle"><span class="fonttitle">{$LNG['Who_is_online']}</span></td></tr>
 <tr><td class="cellstd"><span class="fontsmall">{$wio_text}</span><hr /><span class="fontsmall">{$members}</span></td></tr>
 </table>
</template>
<template:latestpostsbox>
 <br />
 <table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="celltitle"><span class="fonttitle">{$LNG['Latest_posts']}</span></td></tr>
 <template:postrow>
  <tr><td class="cellstd"><span class="fontsmall">{$akt_latest_post_text}</span></td></tr>
 </template>
 </table>
</template>
