<template:preview>
 <table class="tbl" width="100%" border="0" cellspacing="0" cellpadding="3">
 <tr><th class="thnorm" align="left"><span class="thnorm">{$lng['Preview']}</span></th></tr>
 <tr><td class="td1"><span class="norm">{$preview_post}</span></td></tr>
 </table><br />
</template>
<form method="post" action="index.php?faction=editpost&amp;post_id={$post_id}&amp;mode=edit&amp;doit=1&amp;{$MYSID}" name="tbb_form">
<table width="100%" class="tbl" border="0" cellspacing="0" cellpadding="3">
<tr><th class="thnorm" align="left" colspan="2"><span class="thnorm">{$lng['Edit_post']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{$error}</span></td></tr>
</template>
<template:namerow>
 <tr>
  <td class="td1" width="20%"><span class="norm">{LNG_YOUR_NAME}:</span><br /><span class="small">{LNG_NICK_CONVENTIONS}</span></td>
  <td class="td2" width="80%" valign="top"><input class="form_text" type="text" name="p_name" value="{P_NAME}" /></td>
 </tr>
</template>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng['Post_pic']}:</span></td>
 <td class="td2" width="80%" valign="top">{$ppics_box}</td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Title']}:</span></td>
 <td class="td2" width="80%"><input class="form_text" type="text" size="65" name="p_title" value="{$p_title}" maxlength="60" />&nbsp;<span class="small">({$title_max_chars})</span></td>
</tr>
<template:bbcoderow>
 <tr>
  <td class="td1" width="20%" valign="top"></td>
  <td class="td2" width="80%">{$bbcode_box}</td>
 </tr>
</template>
<tr>
 <td class="td1" valign="top"><span class="norm">{$lng['Post']}:</span><br /><br />{$smilies_box}</td>
 <td class="td2" width="80%" valign="top"><textarea class="form_textarea" name="p_message_text" rows="14" cols="80" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p_post}</textarea></td>
</tr>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng['Options']}:</span></td>
 <td class="td2" width="80%"><span class="norm">
  <template:smiliescheck>
   <input type="checkbox" name="p_smilies" value="1" onfocus="this.blur()"{$checked['smilies']} /> {$lng['Enable_smilies']}<br />
  </template>
  <template:sigcheck>
   <input type="checkbox" name="p_signature" value="1" onfocus="this.blur()"{$checked['signature']} /> {$lng['Show_signature']}<br />
  </template>
  <template:bbcodecheck>
   <input type="checkbox" name="p_bbcode" value="1" onfocus="this.blur()"{$checked['bbcode']} /> {$lng['Enable_bbcode']}<br />
  </template>
  <template:htmlcodecheck>
   <input type="checkbox" name="p_htmlcode" value="1" onfocus="this.blur()"{$checked['htmlcode']} /> {$lng['Enable_htmlcode']}<br />
  </template>
 </span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Edit_post']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="submit" name="p_preview" value="{$lng['Preview']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table></form>