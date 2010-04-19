<table class="navbar" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td class="navbar" align="right"><a href="index.php?faction=pms&amp;mode=newpm&amp;{$MYSID}">{$lng['New_private_message']}</a> | <a href="index.php?faction=pms&amp;mode=addfolder&amp;{$MYSID}">{$lng['Add_folder']}</a></td></tr>
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{$lng['Private_messages']}</span></th></tr>
<tr><td class="td1"><span class="norm">
<template:foldertbl>
<p><table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr><td class="td1"><span class="norm"><a href="index.php?faction=pms&amp;mode=viewfolder&amp;folder_id={$akt_folder['folder_id']}&amp;{$MYSID}">{$akt_folder['folder_name']}</span></td></tr>
 <tr><td class="td1"><span class="small">&nbsp;&nbsp;&#187; {$akt_unread_messages}<br />&nbsp;&nbsp;&#187; {$akt_read_messages}</span></td></tr>
</table></p>
</template>
</span></td></tr>
</table>