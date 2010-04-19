<form method="post" action="index.php?faction=vote&amp;poll_id={$poll_data['poll_id']}&amp;z={$z}&amp;$MYSID" />
<table class="tablestd" border="0"cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="3"><span class="fonttitle">{$LNG['Poll']}</span></td>
<tr><td class="cellstd"><span class="fontnorm"><b>{$poll_data['poll_title']}</b></span>
 <table border="0" cellpadding="2" cellspacing="0">
<template:optionrow>
 <tr>
  <td class="cellstd"><span class="fontnorm"><input type="radio" name="p_option_id" value="{$akt_option['option_id']}"{$akt_checked} /></span></td>
  <td class="cellstd"><span class="fontnorm">{$akt_option['option_title']}</span></td>
 </tr>
</template>
 </table>
</td></tr>
<tr><td class="cellbuttons" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Vote']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
<br />