<form method="post" action="administration.php?faction=ad_smilies&amp;mode=add&amp;doit=1&amp;{$MYSID}">
<table class="tbl" cellpadding="3" cellspacing="0" border="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Add_smiley_topic_pic']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{errorrow.$error}</span></td></tr>
</template:errorrow>
<colgroup>
 <col width="15%" />
 <col width="85%" />
</colgroup>
<tr>
 <td class="td1"><span class="norm">{$lng['Type']}:</span></td>
 <td class="td1"><select name="p_type" class="form_select"><option value="0"{$checked['p_type_0']}>{$lng['Smiley']}</option><option value="1"{$checked['p_type_1']}>{$lng['Topic_pic']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Path_or_url']}:</span></td>
 <td class="td2"><input class="form_text" type="text" name="p_gfx" value="{$p_gfx}" /></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Synonym']}:</span></td>
 <td class="td1"><input class="form_text" type="text" name="p_synonym" value="{$p_synonym}" /> <span class="small">({$lng['Only_for_smiley']})</span></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Status']}:</span></td>
 <td class="td2"><select name="p_status" class="form_select"><option value="1"{$checked['p_status_1']}>{$lng['visible']}</option><option value="0"{$checked['p_status_0']}>{$lng['invisible']}</option></select> <span class="small">({$lng['Only_for_smiley']})</span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Add_smiley_topic_pic']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>