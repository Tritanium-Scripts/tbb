<form method="post" action="administration.php?faction=ad_smilies&amp;mode=add&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" cellpadding="3" cellspacing="0" border="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Add_smiley_topic_pic']}</span></td></tr>
<if:"{$error} != ''">
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</if>
<colgroup>
 <col width="15%" />
 <col width="85%" />
</colgroup>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Type']}:</span></td>
 <td class="cellalt"><select name="p_type" class="form_select"><option value="0"{$checked['p_type_0']}>{$LNG['Smiley']}</option><option value="1"{$checked['p_type_1']}>{$LNG['Topic_pic']}</option></select></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Path_or_url']}:</span></td>
 <td class="cellalt"><input class="form_text" type="text" name="p_gfx" value="{$p_gfx}" size="50" /></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Synonym']}:</span></td>
 <td class="cellalt"><input class="form_text" type="text" name="p_synonym" value="{$p_synonym}" /> <span class="fontsmall">({$LNG['Only_for_smiley']})</span></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Status']}:</span></td>
 <td class="cellalt"><select name="p_status" class="form_select"><option value="1"{$checked['p_status_1']}>{$LNG['visible']}</option><option value="0"{$checked['p_status_0']}>{$LNG['invisible']}</option></select> <span class="fontsmall">({$LNG['Only_for_smiley']})</span></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Add_smiley_topic_pic']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
