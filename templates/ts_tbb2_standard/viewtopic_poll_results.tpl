<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="3"><span class="fonttitle">{$LNG['Poll_results']}</span></td></tr>
<tr><td class="cellstd"><span class="fontnorm"><b>{$poll_data['poll_title']}</b></span>
 <table border="0" cellpadding="2" cellspacing="0">
<template:optionrow>
 <tr>
  <td class="cellstd"><span class="fontnorm">{$akt_option['option_title']}</span></td>
  <td class="cellstd"><span class="fontnorm"><img src="{$TEMPLATE_PATH}/images/poll.gif" alt="" border="0" width="{$akt_percent}" height="15" /></span></td>
  <td class="cellstd"><span class="fontsmall">({$akt_percent} %, {$akt_votes})</span></td>
 </tr>
</template>
 </table>
 <span class="fontsmall">({$info_text})</span>
</td></tr>
</table>
<br />