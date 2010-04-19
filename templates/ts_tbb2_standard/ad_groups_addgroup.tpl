<form method="post" action="administration.php?faction=ad_groups&amp;mode=addgroup&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Add_group']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{$error}</span></td></tr>
</template>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Name']}:</span></td>
 <td class="td1" width="85%"><input class="form_text" type="text" name="p_name" value="{$p_name}" maxlength="255" /></td>
</tr>
<tr><td class="buttonrow" align="center" colspan="2"><input class="form_bbutton" type="submit" value="{$lng['Add_group']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>