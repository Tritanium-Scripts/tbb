<template:preview>
 <table class="tablestd" width="100%" border="0" cellspacing="0" cellpadding="3">
 <tr><td class="celltitle" align="left"><span class="fonttitle">{$LNG['Preview']}</span></td></tr>
 <tr><td class="cellstd"><span class="fontnorm">{$preview_post}</span></td></tr>
 </table><br />
</template>
<form method="post" action="index.php?faction=editpost&amp;post_id={$post_id}&amp;mode=edit&amp;doit=1&amp;{$MYSID}" name="tbb_form">
<table width="100%" class="tablestd" border="0" cellspacing="0" cellpadding="3">
<tr><td class="celltitle" align="left" colspan="2"><span class="fonttitle">{$LNG['Edit_post']}</span></td></tr>
<template:errorrow>
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</template>
<template:namerow>
 <tr>
  <td class="cellstd" width="20%"><span class="fontnorm">{LNG_YOUR_NAME}:</span><br /><span class="fontsmall">{LNG_NICK_CONVENTIONS}</span></td>
  <td class="cellalt" width="80%" valign="top"><input class="form_text" type="text" name="p_name" value="{P_NAME}" /></td>
 </tr>
</template>
<tr>
 <td class="cellstd" width="20%" valign="top"><span class="fontnorm">{$LNG['Post_pic']}:</span></td>
 <td class="cellalt" width="80%" valign="top">{$ppics_box}</td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Title']}:</span></td>
 <td class="cellalt" width="80%"><input class="form_text" type="text" size="65" name="p_title" value="{$p_title}" maxlength="60" />&nbsp;<span class="fontsmall">({$title_max_chars})</span></td>
</tr>
<template:bbcoderow>
 <tr>
  <td class="cellstd" width="20%" valign="top"></td>
  <td class="cellalt" width="80%">{$bbcode_box}</td>
 </tr>
</template>
<tr>
 <td class="cellstd" valign="top"><span class="fontnorm">{$LNG['Post']}:</span><br /><br />{$smilies_box}</td>
 <td class="cellalt" width="80%" valign="top"><textarea class="form_textarea" name="p_message_text" rows="14" cols="80" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p_post}</textarea></td>
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
   <input type="checkbox" name="p_htmlcode" value="1" onfocus="this.blur()"{$checked['htmlcode']} /> {$LNG['Enable_htmlcode']}<br />
  </template>
 </span></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Edit_post']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="submit" name="p_preview" value="{$LNG['Preview']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table></form>