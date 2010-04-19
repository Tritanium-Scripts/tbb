<form method="post" action="index.php?faction=vote&amp;poll_id={$poll_data['poll_id']}&amp;z={$z}&amp;$MYSID" />
<table class="tbl" border="0"cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="3"><span class="thnorm">{$lng['Poll']}</span></th>
<tr><td class="td1"><span class="norm"><b>{$poll_data['poll_title']}</b></span>
 <table border="0" cellpadding="2" cellspacing="0">
<template:optionrow>
 <tr>
  <td class="td1"><span class="norm"><input type="radio" name="p_option_id" value="{optionrow.$akt_option['option_id']}"{optionrow.$akt_checked} /></span></td>
  <td class="td1"><span class="norm">{optionrow.$akt_option['option_title']}</span></td>
 </tr>
</template:optionrow>
 </table>
</td></tr>
<tr><td class="buttonrow" align="center"><input class="form_bbutton" type="submit" value="{$lng['Vote']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
<br />