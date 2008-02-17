<template:preview>
 <table class="tablestd" border="0" cellspacing="0" cellpadding="3" width="100%">
 <tr><td class="celltitle"><span class="fonttitle">{$LNG['Preview']}</span></td></tr>
 <tr><td class="cellstd"><span class="fontnorm">{$preview_post}</span></td></tr>
 </table><br />
</template>
<form method="post" action="index.php?faction=posttopic&amp;forum_id={$forum_id}&amp;doit=1&amp;{$MYSID}" name="tbb_form">
<table class="tablestd" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Post_new_topic']}</span></td></tr>
<template:errorrow>
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Topic']}</span></td></tr>
<template:namerow>
 <tr>
  <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Your_name']}:</span><br /><span class="fontsmall">{$LNG['nick_conventions']}</span></td>
  <td class="cellalt" width="80%" valign="top"><input size="20" class="form_text" type="text" name="p_guest_nick" value="{$p_guest_nick}" maxlength="15" /></td>
 </tr>
</template>
<tr>
 <td class="cellstd" width="20%" valign="top"><span class="fontnorm">{$LNG['Post_pic']}:</span></td>
 <td class="cellalt" width="80%" valign="top">{$ppics_box}</td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Title']}:</span></td>
 <td class="cellalt" width="80%"><input class="form_text" type="text" size="65" name="p_topic_title" value="{$p_topic_title}" maxlength="60" />&nbsp;<span class="fontsmall">({$title_max_chars})</span></td>
</tr>
<template:bbcoderow>
 <tr>
  <td class="cellstd" width="20%" valign="top"></td>
  <td class="cellalt" width="80%">{$bbcode_box}</td>
 </tr>
</template>
<tr>
 <td class="cellstd" valign="top"><span class="fontnorm">{$LNG['Post']}:</span><br /><br />{$smilies_box}</td>
 <td class="cellalt" width="80%"><textarea class="form_textarea" name="p_message_text" rows="14" cols="80" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p_post_text}</textarea></td>
</tr>
<tr>
 <td class="cellstd" width="20%" valign="top"><span class="fontnorm">{$LNG['Options']}:</span></td>
 <td class="cellalt" width="80%"><span class="fontnorm">
  <template:smiliescheck>
   <input type="checkbox" name="p_smilies" value="1" onfocus="this.blur()"{$checked['smilies']} /> {$LNG['Enable_smilies']}<br />
  </template>
  <template:sigcheck>
   <input type="checkbox" name="p_signature" value="1" onfocus="this.blur()"{$checked['signature']} /> {$LNG['Show_signature']}<br />
  </template>
  <template:bbcodecheck>
   <input type="checkbox" name="p_bbcode" value="1" onfocus="this.blur()"{$checked['bbcode']} /> {$LNG['Enable_bbcode']}<br />
  </template>
  <template:htmlcodecheck>
   <input type="checkbox" name="p_htmlcode" value="1" onfocus="this.blur()"{$checked['htmlcode']} /> {$LNG['Enable_html_code']}<br />
  </template>
  <template:subscribecheck>
   <input type="checkbox" name="p_subscribe" value="1" onfocus="this.blur()"{$checked['subscribe']} /> {$LNG['Subscribe_topic']}<br />
  </template>
  <template:importantcheck>
   <input type="checkbox" name="p_important" value="1" onfocus="this.blur()"{$checked['important']} /> {$LNG['Mark_topic_important']}<br />
  </template>
  <template:closecheck>
   <input type="checkbox" name="p_close" value="1" onfocus="this.blur()"{$checked['close']} /> {$LNG['Close_topic']}<br />
  </template>
 </span></td>
</tr>
<template:pollrow>
 <tr><td class="cellcat" colspan="2"><a name="pollrow"></a><span class="fontcat">{$LNG['Poll']}</span></td></tr>
 <tr>
  <td class="cellstd" width="20%" valign="top"><span class="fontnorm">{$LNG['Poll_title']}:</span><br /><span class="fontsmall">{$LNG['add_poll_info']}</span></td>
  <td class="cellalt" width="80%" valign="top"><input class="form_text" type="text" name="p_poll_title" maxlength="255" size="60" value="{$p_poll_title}" /></td>
 </tr>
 <tr>
  <td class="cellstd" width="20%" valign="top"><span class="fontnorm">{$LNG['Poll_options']}:</span></td>
  <td class="cellalt" width="80%" valign="top">
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
  <template:optionrow>
   <tr><td><input class="form_text" name="p_poll_options[{$akt_option_key}]" type="text" size="30" value="{$akt_option}" maxlength="255" />&nbsp;&nbsp;&nbsp;<input class="form_button" name="p_poll_delete_options[{$akt_option_key}]" type="submit" value="{$LNG['delete']}" onclick="document.forms['tbb_form'].action = document.forms['tbb_form'].action + '#pollrow';" /></td></tr>
  </template>
   <tr><td><input class="form_text" type="text" name="p_poll_option_title" size="30" value="" maxlength="255" />&nbsp;&nbsp;&nbsp;<input class="form_button" name="p_poll_add_option" type="submit" value="{$LNG['add']}" onclick="document.forms['tbb_form'].action = document.forms['tbb_form'].action + '#pollrow';" /></td></tr>
  </table>
  </td>
 </tr>
</template>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Post_topic']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="submit" name="p_preview" value="{$LNG['Preview']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table></form>