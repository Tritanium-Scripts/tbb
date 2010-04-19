<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td align="center">
<table border="0" cellpadding="3" cellspacing="0">
<template:smileyrow>
 <tr>
 <template:smileyrow.smileycol>
  <td valign="bottom"><a href="javascript:insertatcaret(' {smileyrow.smileycol.akt_smiley['smiley_synonym']} ')"><img src="{smileyrow.smileycol.akt_smiley['smiley_gfx']}" alt="{smileyrow.smileycol.akt_smiley['smiley_synonym']}" border="0" /></a></td>
 </template:smileyrow.smileycol>
 </tr>
</template:smileyrow>
<tr><td colspan="{$tpl_config['smiliesbox_smilies_per_row']}" align="center"><span class="small"><a href="index.php?faction=viewsmilies&amp;{$MYSID}" onclick="popup('index.php?faction=viewsmilies&amp;{$MYSID}','tbbsmilies','width=300,height=400,scrollbars=yes,toolbar=no'); return false;">{$lng['More_smilies']}</a></span></td></tr>
</table>
</td></tr></table>