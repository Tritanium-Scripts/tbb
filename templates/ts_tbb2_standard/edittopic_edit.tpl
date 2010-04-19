<form method="post" action="index.php?faction=edittopic&amp;mode=edit&amp;topic_id={$topic_id}&amp;doit=1&amp;{$MYSID}" name="tbb_form">
<table width="100%" class="tbl" border="0" cellspacing="0" cellpadding="3">
<tr><th class="thnorm" align="left" colspan="2"><span class="thnorm">{$lng['Edit_topic']}</span></th></tr>
<template:errorrow>
 <tr><td class="td2" colspan="2"><span class="error">{errorrow.$error}</span></td></tr>
</template:errorrow>
<tr>
 <td class="td1" valign="top"><span class="norm">{$lng['Post_pic']}:</span></td>
 <td class="td1" valign="top">{$ppics_box}</td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Title']}:</span></td>
 <td class="td2"><input class="form_text" type="text" size="65" maxlength="60" name="p_title" value="{$p_title}" /></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Edit_topic']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table></form>