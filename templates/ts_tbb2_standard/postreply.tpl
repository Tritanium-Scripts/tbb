<template:preview>
 <table class="tbl" width="100%" border="0" cellspacing="0" cellpadding="3">
 <tr><th class="thnorm" align="left"><span class="thnorm">{preview.$lng['Preview']}</span></th></tr>
 <tr><td class="td1"><span class="norm">{preview.$preview_post}</span></td></tr>
 </table><br />
</template:preview>
<form method="post" action="index.php?faction=postreply&amp;topic_id={$topic_id}&amp;doit=1&amp;{$MYSID}" name="tbb_form">
<table width="100%" class="tbl" border="0" cellspacing="0" cellpadding="3">
<tr><th class="thnorm" align="left" colspan="2"><span class="thnorm">{$lng['Post_reply']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{errorrow.$error}</span></td></tr>
</template:errorrow>
<template:namerow>
 <tr>
  <td class="td1" width="20%"><span class="norm">{namerow.$lng['Your_name']}:</span><br /><span class="small">{namerow.$lng['nick_conventions']}</span></td>
  <td class="td2" width="80%" valign="top"><input class="form_text" type="text" name="p_name" value="{namerow.$p_name}" /></td>
 </tr>
</template:namerow>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng['Post_pic']}:</span></td>
 <td class="td2" width="80%" valign="top">{$ppics_box}</td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Title']}:</span></td>
 <td class="td2" width="80%"><input class="form_text" type="text" size="65" name="p_title" value="{$p_title}" maxlength="60" />&nbsp;<span class="small">({$max_nick_chars})</span></td>
</tr>
<template:bbcoderow>
 <tr>
  <td class="td1" width="20%" valign="top"></td>
  <td class="td2" width="80%">{bbcoderow.$bbcode_box}</td>
 </tr>
</template:bbcoderow>
<tr>
 <td class="td1" valign="top"><span class="norm">{$lng['Post']}:</span><br /><br />{$smilies_box}</td>
 <td class="td2" width="80%" valign="top"><textarea class="form_textarea" name="p_post" rows="14" cols="80" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p_post}</textarea></td>
</tr>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng['Options']}:</span></td>
 <td class="td2" width="80%"><span class="norm">
  <template:smiliescheck>
   <input type="checkbox" name="p_smilies" value="1" onfocus="this.blur()"{smiliescheck.$checked['smilies']} /> {smiliescheck.$lng['Enable_smilies']}<br />
  </template:smiliescheck>
  <template:sigcheck>
   <input type="checkbox" name="p_signature" value="1" onfocus="this.blur()"{sigcheck.$checked['signature']} /> {sigcheck.$lng['Show_signature']}<br />
  </template:sigcheck>
  <template:bbcodecheck>
   <input type="checkbox" name="p_bbcode" value="1" onfocus="this.blur()"{bbcodecheck.$checked['bbcode']} /> {bbcodecheck.$lng['Enable_bbcode']}<br />
  </template:bbcodecheck>
  <template:htmlcodecheck>
   <input type="checkbox" name="p_htmlcode" value="1" onfocus="this.blur()"{htmlcodecheck.$checked['htmlcode']} /> {htmlcodecheck.$lng['Enable_htmlcode']}<br />
  </template:htmlcodecheck>
  <template:subscribecheck>
   <input type="checkbox" name="p_subscribe" value="1" onfocus="this.blur()"{subscribecheck.$checked['subscribe']} /> {subscribecheck.$lng['Subscribe_topic']}<br />
  </template:subscribecheck>
  <template:importantcheck>
   <input type="checkbox" name="p_important" value="1" onfocus="this.blur()"{importantcheck.$checked['important']} /> {importantcheck.$lng['Mark_topic_important']}<br />
  </template:importantcheck>
  <template:closecheck>
   <input type="checkbox" name="p_close" value="1" onfocus="this.blur()"{closecheck.$checked['close']} /> {closecheck.$lng['Close_topic']}<br />
  </template:closecheck>
 </span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Post_reply']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="submit" name="p_preview" value="{$lng['Preview']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table></form>

<table class="tbl" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr><th class="thnorm" colspan="2" align="left"><span class="thnorm">{$lng['Topic_review']}</span></th></tr>
<template:reviewpostrow>
 <tr>
  <td class="{reviewpostrow.$tpl_config['akt_class']}" width="15%" valign="top"><span class="norm">{reviewpostrow.$akt_post['poster_nick']}</span></td>
  <td class="{reviewpostrow.$tpl_config['akt_class']}" width="85%" valign="top"><span class="norm">{reviewpostrow.$akt_post['post_text']}</span></td>
 </tr>
</template:reviewpostrow>
</table>