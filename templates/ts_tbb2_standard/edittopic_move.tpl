<form method="post" action="index.php?faction=edittopic&amp;mode=move&amp;topic_id={$topic_id}&amp;doit=1&amp;{$MYSID}" name="tbb_form">
<table width="100%" class="tbl" border="0" cellspacing="0" cellpadding="3">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Move_topic']}</span></th></tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Target_forum']}:</span></td>
 <td class="td1" width="80%"><select class="form_select" name="p_target_forum_id">
 <template:optionrow>
  <option value="{$akt_option_value}">{$akt_option_text}</option>
 </template>
 </select></td>
</tr>
<tr>
 <td class="td2" width="20%"><span class="norm">{$lng['Create_reference']}:</span></td>
 <td class="td2" width="80%"><input type="checkbox" value="1" name="p_create_reference"{$checked['reference']}" /></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Move_topic']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>
