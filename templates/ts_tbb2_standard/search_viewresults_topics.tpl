<form method="post" action="index.php?faction=search&amp;mode=viewresults&amp;search_id={$search_id}&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="celltitle"><span class="fonttitle">{$LNG['Topic']}</span></td>
 <td class="celltitle"><span class="fonttitle">{$LNG['Author']}</span></td>
 <td class="celltitle"><span class="fonttitle">{$LNG['Replies']}</span></td>
 <td class="celltitle"><span class="fonttitle">{$LNG['Views']}</span></td>
 <td class="celltitle"><span class="fonttitle">{$LNG['Last_post']}</span></td>
</tr>
<template:topicrow>
 <tr>
  <td class="cellstd"><span class="fontnorm">{$akt_topic_prefix}</span><span class="topiclink"><a href="index.php?faction=viewtopic&amp;topic_id={$akt_topic_data['topic_id']}&amp;{$MYSID}">{$akt_topic_data['topic_title']}</a></span></td>
  <td class="cellalt" align="center"><span class="fontnorm"><a href="index.php?faction=viewprofile&amp;profile_id={$akt_topic_data['poster_id']}&amp;{$MYSID}">{$akt_topic_data['poster_nick']}</a></span></td>
  <td class="cellstd" align="center"><span class="fontsmall">{$akt_topic_data['topic_replies_counter']}</span></td>
  <td class="cellalt" align="center"><span class="fontsmall">{$akt_topic_data['topic_views_counter']}</span></td>
  <td class="cellstd" align="right"><span class="fontsmall">{$topic_last_post}</span></td>
 </tr>
</template>
<tr><td colspan="5" class="cellbuttons"><span class="fontsmall"><b>{$LNG['Display_options']}:</b> {$LNG['Results']} <select class="form_select" name="display_type"><option value="topics"{$checked['display_type_topics']}>{$LNG['Display_as_topics']}</option><option value="posts"{$checked['display_type_posts']}>{$LNG['Display_as_posts']}</option></select>; {$LNG['Sort_by']} <select class="form_select" name="sort_type"><option value="time"{$checked['sort_type_time']}>{$LNG['Post_age']}</option><option value="title"{$checked['sort_type_title']}>{$LNG['Post_title']}</option><option value="author"{$checked['sort_type_author']}>{$LNG['Author']}</option></select> <select class="form_select" name="sort_method"><option value="DESC"{$checked['sort_method_desc']}>{$LNG['Descending']}</option><option value="ASC"{$checked['sort_method_asc']}>{$LNG['Ascending']}</option></select>; {$LNG['Results_per_page']} <select class="form_select" name="results_per_page"><option value="10">10</option><option value="20">20</option><option value="50">50</option><option value="100">100</option></select>&nbsp;&nbsp;&nbsp;<input class="form_bbutton" type="submit" value="{$LNG['Go']}" /></td></tr>
</table>
</form>
