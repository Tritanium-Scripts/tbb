<script type="text/javascript">
<!--
	function insertatcaret(text) {
		if(opener.document.forms['tbb_form'].elements['p_message_text'].createTextRange && opener.document.forms['tbb_form'].elements['p_message_text'].caretPos) {
			var caretPos = opener.document.forms['tbb_form'].elements['p_message_text'].caretPos;
			caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		}
		else opener.document.forms['tbb_form'].elements['p_message_text'].value += text;
	}
//-->
</script>
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{$lng['Smilies']}</span></th></tr>
<tr><td class="td1" align="center"><table border="0" cellpadding="3" cellspacing="0">
<template:smileyrow>
 <tr>
 <template:smileycol>
  <td valign="bottom"><a href="javascript:insertatcaret(' {$akt_smiley['smiley_synonym']} ')"><img src="{$akt_smiley['smiley_gfx']}" alt="{$akt_smiley['smiley_synonym']}" border="0" /></a></td>
 </template>
 </tr>
</template>
</table><span class="small"><a href="javascript:opener.document.forms['tbb_form'].elements['p_message_text'].focus();window.close();">{$lng['Close_window']}</a></span></td></tr>
</table>
