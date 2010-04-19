<form method="post" action="index.php?faction=edittopic&amp;mode=edit&amp;topic_id={TOPIC_ID}&amp;doit=1&amp;{MYSID}" name="tbb_form">
<table width="100%" class="tbl" border="0" cellspacing="0" cellpadding="3">
<tr><th class="thnorm" align="left" colspan="2"><span class="thnorm">{LNG_EDIT_TOPIC}</span></th></tr>
<!-- TPLBLOCK errorrow -->
 <tr><td class="td2" colspan="2"><span class="error">{errorrow.ERROR}</span></td></tr>
<!-- /TPLBLOCK errorrow -->
<tr>
 <td class="td1"><span class="norm">{LNG_TITLE}:</span></td>
 <td class="td1"><input class="form_text" type="text" size="65" maxlength="60" name="p_title" value="{P_TITLE}" /></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{LNG_EDIT_TOPIC}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{LNG_RESET}" /></td></tr>
</table></form>