<form method="post" action="index.php?faction=pms&amp;mode=newpm&amp;doit=1&amp;{$MYSID}" name="tbb_form">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['New_private_message']}</span></td></tr>
<template:errorrow>
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Recipient']}:</span><br /><span class="fontsmall">{$LNG['recipient_info']}</span></td>
 <td class="cellalt" width="80%" valign="top"><input size=25" class="form_text" type="text" name="p_recipient" value="{$p_recipient}" /></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Subject']}:</span></td>
 <td class="cellalt" width="80%"><input size="60" class="form_text" type="text" name="p_subject" value="{$p_subject}" maxlength="255" /></td>
</tr>
<template:bbcoderow>
 <tr>
  <td class="cellstd" width="20%" valign="top"></td>
  <td class="cellalt" width="80%">{$bbcode_box}</td>
 </tr>
</template>
<tr>
 <td class="cellstd" width="20%" valign="top"><span class="fontnorm">{$LNG['Message']}:</span></td>
 <td class="cellalt" width="80%"><textarea class="form_textarea" rows="14" cols="80" name="p_message_text" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p_message}</textarea></td>
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
  <template:saveoutboxcheck>
   <input type="checkbox" name="p_saveoutbox" value="1" onfocus="this.blur()"{$checked['saveoutbox']} /> {$LNG['Save_pm_outbox']}<br />
  </template>
  <template:rconfirmationcheck>
   <input type="checkbox" name="p_rconfirmation" value="1" onfocus="this.blur()"{$checked['rconfirmation']} /> {$LNG['Request_read_confirmation']}<br />
  </template>
 </span></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Send_private_message']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>