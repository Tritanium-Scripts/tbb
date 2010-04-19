<form method="post" action="index.php?faction=pms&amp;mode=newpm&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng["New_private_message"]}</span></th></tr>
<!-- TPLBLOCK errorrow -->
 <tr><td class="error" colspan="2"><span class="error">{errorrow.$error}</span></td></tr>
<!-- /TPLBLOCK errorrow -->
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng["Recipient"]}:</span><br /><span class="small">{$lng["recipient_info"]}</span></td>
 <td class="td1" width="80%" valign="top"><input size=25" class="form_text" type="text" name="p_recipient" value="{$p_recipient}" /></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng["Subject"]}:</span></td>
 <td class="td1" width="80%"><input size="60" class="form_text" type="text" name="p_subject" value="{$p_subject}" maxlength="255" /></td>
</tr>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng["Message"]}:</span></td>
 <td class="td1" width="80%"><textarea class="form_textarea" rows="14" cols="80" name="p_message">{$p_message}</textarea></td>
</tr>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng["Options"]}:</span></td>
 <td class="td1" width="80%"><span class="norm">
  <!-- TPLBLOCK smiliescheck -->
   <input type="checkbox" name="p_smilies" value="1" onfocus="this.blur()"{smiliescheck.$checked["smilies"]} /> {smiliescheck.$lng["Enable_smilies"]}<br />
  <!-- /TPLBLOCK smiliescheck -->
  <!-- TPLBLOCK sigcheck -->
   <input type="checkbox" name="p_signature" value="1" onfocus="this.blur()"{sigcheck.$checked["signature"]} /> {sigcheck.$lng["Show_signature"]}<br />
  <!-- /TPLBLOCK sigcheck -->
  <!-- TPLBLOCK bbcodecheck -->
   <input type="checkbox" name="p_bbcode" value="1" onfocus="this.blur()"{bbcodecheck.$checked["bbcode"]} /> {bbcodecheck.$lng["Enable_bbcode"]}<br />
  <!-- /TPLBLOCK bbcodecheck -->
  <!-- TPLBLOCK htmlcodecheck -->
   <input type="checkbox" name="p_htmlcode" value="1" onfocus="this.blur()"{htmlcodecheck.$checked["htmlcode"]} /> {htmlcodecheck.$lng["Enable_html_code"]}<br />
  <!-- /TPLBLOCK htmlcodecheck -->
  <!-- TPLBLOCK saveoutboxcheck -->
   <input type="checkbox" name="p_saveoutbox" value="1" onfocus="this.blur()"{saveoutboxcheck.$checked["saveoutbox"]} /> {saveoutboxcheck.$lng["Save_pm_outbox"]}<br />
  <!-- /TPLBLOCK saveoutboxcheck -->
  <!-- TPLBLOCK rconfirmationcheck -->
   <input type="checkbox" name="p_rconfirmation" value="1" onfocus="this.blur()"{rconfirmationcheck.$checked["rconfirmation"]} /> {rconfirmationcheck.$lng["Request_read_confirmation"]}<br />
  <!-- /TPLBLOCK rconfirmationcheck -->
 </span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng["Send_private_message"]}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng["Reset"]}" /></td></tr>
</table>
</form>