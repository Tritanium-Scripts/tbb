<!-- TPLBLOCK preview -->
 <table class="tbl" width="100%" border="0" cellspacing="0" cellpadding="3">
 <tr><th class="thnorm" align="left"><span class="thnorm">{preview.$lng["Preview"]}</span></th></tr>
 <tr><td class="td1"><span class="norm">{preview.$preview_post}</span></td></tr>
 </table><br />
<!-- /TPLBLOCK preview -->
<form method="post" action="index.php?faction=editpost&amp;topic_id={$topic_id}&amp;post_id={$post_id}&amp;mode=edit&amp;doit=1&amp;{$MYSID}" name="tbb_form">
<table width="100%" class="tbl" border="0" cellspacing="0" cellpadding="3">
<tr><th class="thnorm" align="left" colspan="2"><span class="thnorm">{$lng["Edit_post"]}</span></th></tr>
<!-- TPLBLOCK errorrow -->
 <tr><td class="error" colspan="2"><span class="error">{errorrow.$error}</span></td></tr>
<!-- /TPLBLOCK errorrow -->
<!-- TPLBLOCK namerow -->
 <tr>
  <td class="td1" width="20%"><span class="norm">{namerow.LNG_YOUR_NAME}:</span><br /><span class="small">{namerow.LNG_NICK_CONVENTIONS}</span></td>
  <td class="td2" width="80%" valign="top"><input class="form_text" type="text" name="p_name" value="{namerow.P_NAME}" /></td>
 </tr>
<!-- /TPLBLOCK namerow -->
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng["Post_pic"]}:</span></td>
 <td class="td2" width="80%" valign="top">{$ppics_box}</td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng["Title"]}:</span></td>
 <td class="td2" width="80%"><input class="form_text" type="text" size="65" name="p_title" value="{$p_title}" maxlength="60" />&nbsp;<span class="small">({$title_max_chars})</span></td>
</tr>
<!-- TPLBLOCK fcoderow -->
 <tr>
  <td class="td1" width="20%" valign="top"><span class="norm">{fcoderow.LNG_FCODE}:</span></td>
  <td class="td2" width="80%">{fcoderow.FCODE_BOX}</td>
 </tr>
<!-- /TPLBLOCK fcoderow -->
<tr>
 <td class="td1" valign="top"><span class="norm">{$lng["Post"]}:</span><br /><br />{$smilies_box}</td>
 <td class="td2" width="80%" valign="top"><textarea class="form_textarea" name="p_post" rows="14" cols="80" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p_post}</textarea></td>
</tr>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng["Options"]}:</span></td>
 <td class="td2" width="80%"><span class="norm">
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
   <input type="checkbox" name="p_htmlcode" value="1" onfocus="this.blur()"{htmlcodecheck.$checked["htmlcode"]} /> {htmlcodecheck.$lng["Enable_htmlcode"]}<br />
  <!-- /TPLBLOCK htmlcodecheck -->
 </span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng["Edit_post"]}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="submit" name="p_preview" value="{$lng["Preview"]}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng["Reset"]}" /></td></tr>
</table></form>