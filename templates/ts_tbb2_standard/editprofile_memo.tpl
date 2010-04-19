<templatefile:"editprofile_header.tpl" />
<form method="post" action="index.php?faction=editprofile&amp;mode=memo&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="cellcat"><span class="fontcat">{$LNG['Memo']}</span></td></tr>
<if:"isset($_GET['doit']) == TRUE"><tr><td class="cellinfobox"><span class="fontinfobox">{$LNG['message_memo_updated']}</span></td></tr></if>
<tr><td class="cellstd"><textarea class="form_textarea" cols="150" rows="25" name="p_user_memo">{$p_user_memo}</textarea></td></tr>
<tr><td class="cellinfobox"><span class="fontinfobox">{$LNG['memo_info']}</span></td></tr>
<tr><td class="cellbuttons" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Update_memo']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
<templatefile:"editprofile_tail.tpl" />
