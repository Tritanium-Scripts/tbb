<template:newsbox>
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{$lng['Latest_news']}</span></th></tr>
<tr><td class="td1"><span class="norm"><b>{$news_data['news_title']}</b><br /><br />{$news_data['news_text']}</span><br /><br /><span class="small">{$news_comments_link}</span></td></tr>
</table>
<br />
</template>
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <th class="thsmall">{$lng['Forum']}</th>
 <th class="thsmall">{$lng['Topics']}</th>
 <th class="thsmall">{$lng['Posts']}</th>
 <th class="thsmall">{$lng['Last_post']}</th>
 <th class="thsmall">{$lng['Moderators']}</th>
</tr>
<template:catrow>
 <tr>
  <td colspan="5" class="cat">{$akt_appendix}{$akt_plus_minus_pic}&nbsp;<span class="cat"><a class="cat" href="index.php?faction=viewcat&amp;cat_id={$akt_cat['cat_id']}&amp;{$MYSID}">{$akt_cat['cat_name']}</a></span></td>
 </tr>
 <template:forumrow>
  <tr>
   <td class="td2" width="50%">
    <table border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td rowspan="2">{$akt_appendix}{$akt_new_post_status}&nbsp;</td>
      <td><span class="forumlink"><a class="forumlink" href="index.php?faction=viewforum&amp;forum_id={$akt_forum['forum_id']}&amp;{$MYSID}">{$akt_forum['forum_name']}</a></span></td>
     </tr>
     <tr><td><span class="small">{$akt_forum['forum_description']}</span></td></tr>
    </table></td>
   <td class="td1" align="center"><span class="small">{$akt_forum['forum_topics_counter']}</span></td>
   <td class="td2" align="center"><span class="small">{$akt_forum['forum_posts_counter']}</span></td>
   <td class="td1">
    <table border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td align="center">{$akt_last_post_pic}</td>
     <td align="left"><span class="small">{$akt_last_post_text}</span></td>
    </tr>
    </table>
   </td>
   <td class="td2" align="center"><span class="small">{$akt_forum_mods}</span></td>
  </tr>
 </template>
</template>
</table>
<template:boardstatsbox>
 <br />
 <table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><th class="thnorm"><span class="thnorm">{$lng['Board_statistics']}</span></th></tr>
 <tr><td class="td1"><span class="small">{$board_stats_text}</span></td></tr>
 </table>
</template>
<template:wiobox>
 <br />
 <table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><th class="thnorm"><span class="thnorm">{$lng['Who_is_online']}</span></th></tr>
 <tr><td class="td1"><span class="small">{$wio_text}</span><hr /><span class="small">{$members}</span></td></tr>
 </table>
</template>
<template:latestpostsbox>
 <br />
 <table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><th class="thnorm"><span class="thnorm">{$lng['Latest_posts']}</span></th></tr>
 <template:postrow>
  <tr><td class="td1"><span class="small">{$akt_latest_post_text}</span></td></tr>
 </template>
 </table>
</template>
