<form method="post" action="index.php?faction=search&amp;mode=viewresults&amp;search_id={$search_id}&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <th class="thsmall"><span class="thnorm">{$lng['Topic']}</span></th>
 <th class="thsmall"><span class="thnorm">{$lng['Author']}</span></th>
 <th class="thsmall"><span class="thnorm">{$lng['Replies']}</span></th>
 <th class="thsmall"><span class="thnorm">{$lng['Views']}</span></th>
 <th class="thsmall"><span class="thnorm">{$lng['Last_post']}</span></th>
</tr>
<template:topicrow>
 <tr>
  <td class="td1"><span class="norm">{$akt_topic_prefix}</span><span class="topiclink"><a href="index.php?faction=viewtopic&amp;topic_id={$akt_topic_data['topic_id']}&amp;{$MYSID}">{$akt_topic_data['topic_title']}</a></span></td>
  <td class="td2" align="center"><span class="norm"><a href="index.php?faction=viewprofile&amp;profile_id={$akt_topic_data['poster_id']}&amp;{$MYSID}">{$akt_topic_data['poster_nick']}</a></span></td>
  <td class="td1" align="center"><span class="small">{$akt_topic_data['topic_replies_counter']}</span></td>
  <td class="td2" align="center"><span class="small">{$akt_topic_data['topic_views_counter']}</span></td>
  <td class="td1" align="right"><span class="small">{$topic_last_post}</span></td>
 </tr>
</template>
<tr><td colspan="5" class="buttonrow"><span class="small"><b>{$lng['Display_options']}:</b> {$lng['Results']} <select class="form_select" name="display_type"><option value="topics"{$checked['display_type_topics']}>{$lng['Display_as_topics']}</option><option value="posts"{$checked['display_type_posts']}>{$lng['Display_as_posts']}</option></select>; {$lng['Sort_by']} <select class="form_select" name="sort_type"><option value="time"{$checked['sort_type_time']}>{$lng['Post_age']}</option><option value="title"{$checked['sort_type_title']}>{$lng['Post_title']}</option><option value="author"{$checked['sort_type_author']}>{$lng['Author']}</option></select> <select class="form_select" name="sort_method"><option value="DESC"{$checked['sort_method_desc']}>{$lng['Descending']}</option><option value="ASC"{$checked['sort_method_asc']}>{$lng['Ascending']}</option></select>; {$lng['Results_per_page']} <select class="form_select" name="results_per_page"><option value="10">10</option><option value="20">20</option><option value="50">50</option><option value="100">100</option></select>&nbsp;&nbsp;&nbsp;<input class="form_bbutton" type="submit" value="{$lng['Go']}" /></td></tr>
</table>
</form>
