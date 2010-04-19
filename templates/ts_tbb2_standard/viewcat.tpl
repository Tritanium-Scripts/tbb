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
  <td colspan="5" class="cat">{catrow.$akt_appendix}{catrow.$akt_plus_minus_pic}&nbsp;<span class="cat"><a class="cat" href="index.php?faction=viewcat&amp;cat_id={catrow.$akt_cat['cat_id']}&amp;{catrow.$MYSID}">{catrow.$akt_cat['cat_name']}</a></span></td>
 </tr>
 <template:catrow.forumrow>
  <tr>
   <td class="td2" width="50%">
    <table border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td rowspan="2">{catrow.forumrow.$akt_appendix}{catrow.forumrow.$akt_new_post_status}&nbsp;</td>
      <td><span class="forumlink"><a class="forumlink" href="index.php?faction=viewforum&amp;forum_id={catrow.forumrow.$akt_forum['forum_id']}&amp;{catrow.forumrow.$MYSID}">{catrow.forumrow.$akt_forum['forum_name']}</a></span></td>
     </tr>
     <tr><td><span class="small">{catrow.forumrow.$akt_forum['forum_description']}</span></td></tr>
    </table></td>
   <td class="td1" align="center"><span class="small">{catrow.forumrow.$akt_forum['forum_topics_counter']}</span></td>
   <td class="td2" align="center"><span class="small">{catrow.forumrow.$akt_forum['forum_posts_counter']}</span></td>
   <td class="td1" align="center">
    <table border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td align="center">{catrow.forumrow.$akt_last_post_pic}</td>
     <td align="left"><span class="small">{catrow.forumrow.$akt_last_post_text}</span></td>
    </tr>
    </table>
   </td>
   <td class="td2" align="center"><span class="small">{catrow.forumrow.$akt_forum_mods}</span></td>
  </tr>
 </template:catrow.forumrow>
</template:catrow>
</table>