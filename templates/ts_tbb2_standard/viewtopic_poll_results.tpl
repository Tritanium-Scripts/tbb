<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="3"><span class="thnorm">{$lng['Poll_results']}</span></th></tr>
<tr><td class="td1"><span class="norm"><b>{$poll_data['poll_title']}</b></span>
 <table border="0" cellpadding="2" cellspacing="0">
<template:optionrow>
 <tr>
  <td class="td1"><span class="norm">{optionrow.$akt_option['option_title']}</span></td>
  <td class="td1"><span class="norm"><img src="{optionrow.$template_path}/images/poll.gif" alt="" border="0" width="{optionrow.$akt_percent}" height="15" /></span></td>
  <td class="td1"><span class="small">({optionrow.$akt_percent} %, {optionrow.$akt_votes})</span></td>
 </tr>
</template:optionrow>
 </table>
 <span class="small">({$info_text})</span>
</td></tr>
</table>
<br />