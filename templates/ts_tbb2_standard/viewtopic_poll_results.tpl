<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="3"><span class="thnorm">{$lng['Poll_results']}</span></th></tr>
<tr><td class="td1"><span class="norm"><b>{$poll_data['poll_title']}</b></span>
 <table border="0" cellpadding="2" cellspacing="0">
<template:optionrow>
 <tr>
  <td class="td1"><span class="norm">{$akt_option['option_title']}</span></td>
  <td class="td1"><span class="norm"><img src="{$TEMPLATE_PATH}/images/poll.gif" alt="" border="0" width="{$akt_percent}" height="15" /></span></td>
  <td class="td1"><span class="small">({$akt_percent} %, {$akt_votes})</span></td>
 </tr>
</template>
 </table>
 <span class="small">({$info_text})</span>
</td></tr>
</table>
<br />