<form method="post" action="index.php?faction=register&amp;mode=boardrules&amp;doit=1&amp;{$MYSID}">
<input type="hidden" name="p_register_hash" value="{$_SESSION['s_register_hash']}" />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{$lng['Board_rules']}</span></th></tr>
<tr><td class="td1" align="center"><textarea class="form_textarea" rows="15" cols="150">{$lng['board_rules_text']}</textarea></td></tr>
<tr><td class="buttonrow" align="center"><input class="form_bbutton" type="submit" name="p_not_accept" value="{$lng['I_dont_accept_board_rules']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="submit" name="p_accept" value="{$lng['I_accept_board_rules']}" /></td></tr>
</table>
</form>