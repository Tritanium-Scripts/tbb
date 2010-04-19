<form method="post" action="index.php?faction=pms&amp;mode=addfolder&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Add_folder']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{$error}</span></td></tr>
</template>
<tr>
 <td class="td1" witdh="20%"><span class="norm">{$lng['Name']}:</span></td>
 <td class="td1" width="80%"><input class="form_text" type="text" name="p_name" value="{$p_name}" maxlength="255" /></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Add_folder']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>