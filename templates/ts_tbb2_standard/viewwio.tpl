<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Who_is_online']}</span></th></tr>
<template:wiorow>
 <tr>
  <td class="{wiorow.$tpl_config['akt_class']}"><span class="norm">{wiorow.$akt_session['session_user_nick']}</span></td>
  <td class="{wiorow.$tpl_config['akt_class']}"><span class="norm">{wiorow.$akt_session_location}</span></td>
 </tr>
</template:wiorow>
</table>