<table class="navbar" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td class="navbar" align="right"><a href="index.php?faction=pms&amp;mode=newpm&amp;{$MYSID}">{$LNG['New_private_message']}</a> | <a href="index.php?faction=pms&amp;mode=addfolder&amp;{$MYSID}">{$LNG['Add_folder']}</a></td></tr>
</table>
<br />
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle"><span class="fonttitle">{$LNG['Private_messages']}</span></td></tr>
<tr><td class="cellstd"><span class="fontnorm">
<template:foldertbl>
<p><table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr><td class="cellstd"><span class="fontnorm"><a href="index.php?faction=pms&amp;mode=viewfolder&amp;folder_id={$akt_folder['folder_id']}&amp;{$MYSID}">{$akt_folder['folder_name']}</span></td></tr>
 <tr><td class="cellstd"><span class="fontsmall">&nbsp;&nbsp;&#187; {$akt_unread_messages}<br />&nbsp;&nbsp;&#187; {$akt_read_messages}</span></td></tr>
</table></p>
</template>
</span></td></tr>
</table>