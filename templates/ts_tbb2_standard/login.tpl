<form method="post" action="index.php?faction=login&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng["Login"]}</span></th></tr>
<!-- TPLBLOCK errorrow -->
 <tr><td class="error" colspan="2"><span class="error">{errorrow.$error}</span></td></tr>
<!-- /TPLBLOCK errorrow -->
<tr><td class="cat" colspan="2"><span class="cat">{$lng["Logindata"]}</span></td></tr>
<tr>
 <td class="td1" width="25%"><span class="norm">{$lng["User_name"]}:</span></td>
 <td class="td2" width="75%"><input class="form_text" type="text" name="p_nick" value="{$p_nick}" tabindex="1" /> <span class="small">(<a href="index.php?faction=register&amp;{$MYSID}" tabindex="3">{$lng["Register"]}</a>)</span></td>
</tr>
<tr>
 <td class="td1" width="25%"><span class="norm">{$lng["Password"]}:</span></td>
 <td class="td2" width="75%"><input class="form_text" type="password" name="p_pw" tabindex="2" /></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng["Other_options"]}</span></td></tr>
<tr><td class="td1" colspan="2"><span class="norm"><input type="checkbox" name="p_hide_from_wio" value="1"{$checked["hide_from_wio"]} /> {$lng["Hide_from_who_is_online"]}</span></td></tr>
<tr><td class="buttonrow" colspan="2" align="center"><input type="submit" name="p_submit" value="{$lng["Login"]}" class="form_bbutton" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" name="p_reset" value="{$lng["Reset"]}" /></td></tr>
</table>
</form>