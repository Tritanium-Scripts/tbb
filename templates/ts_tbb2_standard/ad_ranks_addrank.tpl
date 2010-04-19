<form method="post" action="administration.php?faction=ad_ranks&amp;mode=addrank&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Add_rank']}</span></td></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{errorrow.$error}</span></td></tr>
</template:errorrow>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Rank_name']}:</span></td>
 <td class="td2" width="80%"><input class="form_text" type="text" name="p_rank_name" maxlength="255" size="40" value="{$p_rank_name}" /></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Rank_type']}:</span></td>
 <td class="td2" width="80%"><select class="form_select" name="p_rank_type"><option value="0"{$selected['normal_rank']}>{$lng['Normal_rank']}</option><option value="1"{$selected['special_rank']}>{$lng['Special_rank']}</option></select></td>
</tr>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng['Rank_image']}:</span><br /><span class="small">{$lng['rank_image_info']}</span></td>
 <td class="td2" width="80%" valign="top"><textarea class="form_textarea" name="p_rank_gfx" rows="6" cols="60">{$p_rank_gfx}</textarea></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Required_posts']}:</span></td>
 <td class="td2" width="80%"><input class="form_text" type="text" name="p_rank_posts" maxlength="8" size="10" value="{$p_rank_posts}" /></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Add_rank']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>
