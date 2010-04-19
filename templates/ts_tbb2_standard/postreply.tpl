<!-- TPLBLOCK preview -->
 <table class="tbl" width="100%" border="0" cellspacing="0" cellpadding="3">
 <tr><th class="thnorm" align="left"><span class="thnorm">{preview.$lng["Preview"]}</span></th></tr>
 <tr><td class="td1"><span class="norm">{preview.$preview_post}</span></td></tr>
 </table><br />
<!-- /TPLBLOCK preview -->
<form method="post" action="index.php?faction=postreply&amp;topic_id={$topic_id}&amp;doit=1&amp;{$MYSID}" name="tbb_form">
<table width="100%" class="tbl" border="0" cellspacing="0" cellpadding="3">
<tr><th class="thnorm" align="left" colspan="2"><span class="thnorm">{LNG_POST_REPLY}</span></th></tr>
<!-- TPLBLOCK errorrow -->
 <tr><td class="error" colspan="2"><span class="error">{errorrow.ERROR}</span></td></tr>
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
 <td class="td1" width="20%"><span class="norm">{LNG_TITLE}:</span></td>
 <td class="td2" width="80%"><input class="form_text" type="text" size="65" name="p_title" value="{P_TITLE}" maxlength="60" />&nbsp;<span class="small">({LNG_MAXIMUM_CHARS})</span></td>
</tr>
<!-- TPLBLOCK fcoderow -->
 <tr>
  <td class="td1" width="20%" valign="top"><span class="norm">{fcoderow.LNG_FCODE}:</span></td>
  <td class="td2" width="80%">{fcoderow.FCODE_BOX}</td>
 </tr>
<!-- /TPLBLOCK fcoderow -->
<tr>
 <td class="td1" valign="top"><span class="norm">{LNG_POST}:</span><br /><br />{$smilies_box}</td>
 <td class="td2" width="80%" valign="top"><textarea class="form_textarea" name="p_post" rows="14" cols="80" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{P_POST}</textarea></td>
</tr>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{LNG_OPTIONS}:</span></td>
 <td class="td2" width="80%"><span class="norm">
  <!-- TPLBLOCK smiliescheck -->
   <input type="checkbox" name="p_smilies" value="1" onfocus="this.blur()"{smiliescheck.C_SMILIES} /> {smiliescheck.LNG_ENABLE_SMILIES}<br />
  <!-- /TPLBLOCK smiliescheck -->
  <!-- TPLBLOCK sigcheck -->
   <input type="checkbox" name="p_signature" value="1" onfocus="this.blur()"{sigcheck.C_SIGNATURE} /> {sigcheck.LNG_SHOW_SIGNATURE}<br />
  <!-- /TPLBLOCK sigcheck -->
  <!-- TPLBLOCK bbcodecheck -->
   <input type="checkbox" name="p_bbcode" value="1" onfocus="this.blur()"{bbcodecheck.C_BBCODE} /> {bbcodecheck.LNG_ENABLE_BBCODE}<br />
  <!-- /TPLBLOCK bbcodecheck -->
  <!-- TPLBLOCK htmlcodecheck -->
   <input type="checkbox" name="p_htmlcode" value="1" onfocus="this.blur()"{htmlcodecheck.C_HTMLCODE} /> {htmlcodecheck.LNG_ENABLE_HTMLCODE}<br />
  <!-- /TPLBLOCK htmlcodecheck -->
 </span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{LNG_POST_REPLY}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="submit" name="p_preview" value="{LNG_PREVIEW}" /></td></tr>
</table></form>

<table class="tbl" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr><th class="thnorm" colspan="2" align="left"><span class="thnorm">{LNG_TOPIC_REVIEW}</span></th></tr>
<!-- TPLBLOCK reviewpostrow -->
 <tr>
  <td class="{reviewpostrow.AKT_CLASS}" width="15%" valign="top"><span class="norm">{reviewpostrow.POSTER_NICK}</span></td>
  <td class="{reviewpostrow.AKT_CLASS}" width="85%" valign="top"><span class="norm">{reviewpostrow.POST_TEXT}</span></td>
 </tr>
<!-- /TPLBLOCK reviewpostrow -->
</table>