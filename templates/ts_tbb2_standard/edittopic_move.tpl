<form method="post" action="index.php?faction=edittopic&amp;mode=move&amp;topic_id={$topic_id}&amp;doit=1&amp;{$MYSID}" name="tbb_form">
<table width="100%" class="tablestd" border="0" cellspacing="0" cellpadding="3">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Move_topic']}</span></td></tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Target_forum']}:</span></td>
 <td class="cellstd" width="80%"><select class="form_select" name="p_target_forum_id">
 <template:optionrow>
  <option value="{$akt_option_value}">{$akt_option_text}</option>
 </template>
 </select></td>
</tr>
<tr>
 <td class="cellalt" width="20%"><span class="fontnorm">{$LNG['Create_reference']}:</span></td>
 <td class="cellalt" width="80%"><input type="checkbox" value="1" name="p_create_reference"{$checked['reference']}" /></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Move_topic']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
