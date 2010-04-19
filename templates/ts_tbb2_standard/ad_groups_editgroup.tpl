<form method="post" action="administration.php?faction=ad_groups&amp;mode=editgroup&amp;group_id={$group_id}&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Edit_group']}</span></td></tr>
<template:errorrow>
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Name']}:</span></td>
 <td class="cellstd" width="85%"><input class="form_text" type="text" name="p_name" value="{$p_name}" maxlength="255" /></td>
</tr>
<tr><td class="cellbuttons" align="center" colspan="2"><input class="form_bbutton" type="submit" value="{$LNG['Edit_group']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>