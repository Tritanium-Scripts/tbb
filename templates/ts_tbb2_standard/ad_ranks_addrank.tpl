<form method="post" action="administration.php?faction=ad_ranks&amp;mode=addrank&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Add_rank']}</span></td></tr>
<template:errorrow>
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Rank_name']}:</span></td>
 <td class="cellalt" width="80%"><input class="form_text" type="text" name="p_rank_name" maxlength="255" size="40" value="{$p_rank_name}" /></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Rank_type']}:</span></td>
 <td class="cellalt" width="80%"><select class="form_select" name="p_rank_type"><option value="0"{$selected['normal_rank']}>{$LNG['Normal_rank']}</option><option value="1"{$selected['special_rank']}>{$LNG['Special_rank']}</option></select></td>
</tr>
<tr>
 <td class="cellstd" width="20%" valign="top"><span class="fontnorm">{$LNG['Rank_image']}:</span><br /><span class="fontsmall">{$LNG['rank_image_info']}</span></td>
 <td class="cellalt" width="80%" valign="top"><textarea class="form_textarea" name="p_rank_gfx" rows="6" cols="60">{$p_rank_gfx}</textarea></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Required_posts']}:</span></td>
 <td class="cellalt" width="80%"><input class="form_text" type="text" name="p_rank_posts" maxlength="8" size="10" value="{$p_rank_posts}" /></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Add_rank']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
