<templatefile:"editprofile_header.tpl" />
<form method="post" action="index.php?faction=editprofile&amp;mode=topicsubscriptions&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="cellcat" colspan="3"><span class="fontcat">{$LNG['Topic_subscriptions']}</span></td></tr>
<if:"{$subscriptions_counter} == 0"><tr><td class="cellstd" align="center"><span class="fontnorm">{$LNG['No_topic_subscriptions_found']}</span></td></tr></if>
<template:subscriptionrow>
<tr>
 <td class="cellalt" align="center" width="50"><input type="checkbox" name="topic_ids[]" value="{$akt_topic_subscription['topic_id']}" /></td>
 <td class="cellstd"><span class="fontnorm"><a href="index.php?faction=viewtopic&amp;topic_id={$akt_topic_subscription['topic_id']}&amp;{$MYSID}">{$akt_topic_subscription['topic_title']}</a></span></td>
 <td class="cellalt" align="right"><span class="fontsmall"><a href="index.php?faction=editprofile&amp;mode=topicsubscriptions&amp;doit=1&amp;topic_id={$akt_topic_subscription['topic_id']}&amp;{$MYSID}">{$LNG['delete']}</a></span></td>
</tr>
</template>
<tr><td class="cellbuttons" colspan="3"><input class="form_bbutton" type="submit" value="{$LNG['Delete_selected_topic_subscriptions']}" /></td></tr>
</table>
</form>
<templatefile:"editprofile_tail.tpl" />
