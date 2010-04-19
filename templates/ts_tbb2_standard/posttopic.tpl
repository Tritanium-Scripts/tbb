<template:preview>
 <table class="tbl" width="100%" border="0" cellspacing="0" cellpadding="3">
 <tr><th class="thnorm" align="left"><span class="thnorm">{$lng['Preview']}</span></th></tr>
 <tr><td class="td1"><span class="norm">{$preview_post}</span></td></tr>
 </table><br />
</template>
<form method="post" action="index.php?faction=posttopic&amp;forum_id={$forum_id}&amp;doit=1&amp;{$MYSID}" name="tbb_form">
<table width="100%" class="tbl" border="0" cellspacing="0" cellpadding="3">
<tr><th class="thnorm" align="left" colspan="2"><span class="thnorm">{$lng['Post_new_topic']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{$error}</span></td></tr>
</template>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Topic']}</span></td></tr>
<template:namerow>
 <tr>
  <td class="td1" width="20%"><span class="norm">{$lng['Your_name']}:</span><br /><span class="small">{$lng['nick_conventions']}</span></td>
  <td class="td2" width="80%" valign="top"><input size="20" class="form_text" type="text" name="p_guest_nick" value="{$p_guest_nick}" maxlength="15" /></td>
 </tr>
</template>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng['Post_pic']}:</span></td>
 <td class="td2" width="80%" valign="top">{$ppics_box}</td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Title']}:</span></td>
 <td class="td2" width="80%"><input class="form_text" type="text" size="65" name="p_topic_title" value="{$p_topic_title}" maxlength="60" />&nbsp;<span class="small">({$title_max_chars})</span></td>
</tr>
<template:bbcoderow>
 <tr>
  <td class="td1" width="20%" valign="top"></td>
  <td class="td2" width="80%">{$bbcode_box}</td>
 </tr>
</template>
<tr>
 <td class="td1" valign="top"><span class="norm">{$lng['Post']}:</span><br /><br />{$smilies_box}</td>
 <td class="td2" width="80%"><textarea class="form_textarea" name="p_message_text" rows="14" cols="80" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p_post_text}</textarea></td>
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
   <input type="checkbox" name="p_htmlcode" value="1" onfocus="this.blur()"{$checked['htmlcode']} /> {$lng['Enable_html_code']}<br />
  </template>
  <template:subscribecheck>
   <input type="checkbox" name="p_subscribe" value="1" onfocus="this.blur()"{$checked['subscribe']} /> {$lng['Subscribe_topic']}<br />
  </template>
  <template:importantcheck>
   <input type="checkbox" name="p_important" value="1" onfocus="this.blur()"{$checked['important']} /> {$lng['Mark_topic_important']}<br />
  </template>
  <template:closecheck>
   <input type="checkbox" name="p_close" value="1" onfocus="this.blur()"{$checked['close']} /> {$lng['Close_topic']}<br />
  </template>
 </span></td>
</tr>
<template:pollrow>
 <tr><td class="cat" colspan="2"><a name="pollrow"></a><span class="cat">{$lng['Poll']}</span></td></tr>
 <tr>
  <td class="td1" width="20%" valign="top"><span class="norm">{$lng['Poll_title']}:</span><br /><span class="small">{$lng['add_poll_info']}</span></td>
  <td class="td2" width="80%" valign="top"><input class="form_text" type="text" name="p_poll_title" maxlength="255" size="60" value="{$p_poll_title}" /></td>
 </tr>
 <tr>
  <td class="td1" width="20%" valign="top"><span class="norm">{$lng['Poll_options']}:</span></td>
  <td class="td2" width="80%" valign="top">
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
  <template:optionrow>
   <tr><td><input class="form_text" name="p_poll_options[{$akt_option_key}]" type="text" size="30" value="{$akt_option}" maxlength="255" />&nbsp;&nbsp;&nbsp;<input class="form_button" name="p_poll_delete_options[{$akt_option_key}]" type="submit" value="{$lng['delete']}" onclick="document.forms['tbb_form'].action = document.forms['tbb_form'].action + '#pollrow';" /></td></tr>
  </template>
   <tr><td><input class="form_text" type="text" name="p_poll_option_title" size="30" value="" maxlength="255" />&nbsp;&nbsp;&nbsp;<input class="form_button" name="p_poll_add_option" type="submit" value="{$lng['add']}" onclick="document.forms['tbb_form'].action = document.forms['tbb_form'].action + '#pollrow';" /></td></tr>
  </table>
  </td>
 </tr>
</template>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Post_topic']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="submit" name="p_preview" value="{$lng['Preview']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table></form>